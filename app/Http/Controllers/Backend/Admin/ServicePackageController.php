<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Admin\ServicePackageRequest;
use App\Models\ServicePackage;
use App\Models\Studio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ServicePackageController extends Controller
{
    /**
     * Daftar paket layanan.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $sort = (string) $request->query('sort', 'created_at');
        $dir = strtolower((string) $request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        // Kolom yang boleh dipakai untuk sort (whitelist, anti SQL injection).
        $sortable = ['name', 'duration_minutes', 'price', 'is_active', 'created_at'];
        if (!in_array($sort, $sortable, true)) {
            $sort = 'created_at';
        }

        $query = ServicePackage::query()->with('studio');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('studio', fn ($studio) => $studio->where('name', 'like', "%{$search}%"));
            });
        }

        $query->orderBy($sort, $dir);

        return view('backend.admin.service_packages.index', [
            'packages' => $query->paginate(10)->withQueryString(),
            'search' => $search,
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    /**
     * Form tambah paket.
     */
    public function create(): View
    {
        return view('backend.admin.service_packages.create', [
            'studios' => Studio::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Simpan paket baru.
     */
    public function store(ServicePackageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['image_path'] = $this->storeImage($request);
        unset($data['image'], $data['remove_image']);

        ServicePackage::create($data);

        return redirect()->route('admin.service-packages.index')->with('success', 'Paket layanan berhasil ditambahkan.');
    }

    /**
     * Form edit paket.
     */
    public function edit(ServicePackage $servicePackage): View
    {
        return view('backend.admin.service_packages.edit', [
            'servicePackage' => $servicePackage,
            'studios' => Studio::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update paket.
     */
    public function update(ServicePackageRequest $request, ServicePackage $servicePackage): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        unset($data['image'], $data['remove_image']);

        $currentImagePath = $servicePackage->image_path;

        if ($request->boolean('remove_image') && filled($currentImagePath)) {
            $this->deleteStoredImage($currentImagePath);
            $currentImagePath = null;
            $data['image_path'] = null;
        }

        $newImagePath = $this->storeImage($request);
        if ($newImagePath) {
            if (filled($currentImagePath)) {
                $this->deleteStoredImage($currentImagePath);
            }
            $data['image_path'] = $newImagePath;
        }

        $servicePackage->update($data);

        return redirect()->route('admin.service-packages.index')->with('success', 'Paket layanan berhasil diperbarui.');
    }

    /**
     * Hapus paket layanan.
     */
    public function destroy(ServicePackage $servicePackage): RedirectResponse
    {
        $this->deleteStoredImage($servicePackage->image_path);
        $servicePackage->delete();

        return redirect()->route('admin.service-packages.index')->with('success', 'Paket layanan berhasil dihapus.');
    }

    private function storeImage(ServicePackageRequest $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        return (string) $request->file('image')->store('service-packages', 'public');
    }

    private function deleteStoredImage(?string $path): void
    {
        $path = trim((string) $path);

        if (
            $path === ''
            || str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, 'data:')
        ) {
            return;
        }

        if (str_starts_with($path, 'storage/')) {
            $path = ltrim(substr($path, strlen('storage/')), '/');
        }

        Storage::disk('public')->delete($path);
    }
}
