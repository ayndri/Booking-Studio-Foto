<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Admin\StudioRequest;
use App\Models\Studio;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudioController extends Controller
{
    /**
     * Daftar studio.
     */
    public function index(): View
    {
        return view('backend.admin.studios.index', [
            'studios' => Studio::query()->latest()->paginate(10),
        ]);
    }

    /**
     * Form tambah studio.
     */
    public function create(): View
    {
        return view('backend.admin.studios.create');
    }

    /**
     * Simpan studio baru.
     */
    public function store(StudioRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        Studio::create($data);

        return redirect()->route('admin.studios.index')->with('success', 'Studio berhasil ditambahkan.');
    }

    /**
     * Form edit studio.
     */
    public function edit(Studio $studio): View
    {
        return view('backend.admin.studios.edit', [
            'studio' => $studio,
        ]);
    }

    /**
     * Update data studio.
     */
    public function update(StudioRequest $request, Studio $studio): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $studio->update($data);

        return redirect()->route('admin.studios.index')->with('success', 'Studio berhasil diperbarui.');
    }

    /**
     * Hapus studio.
     */
    public function destroy(Studio $studio): RedirectResponse
    {
        $studio->delete();

        return redirect()->route('admin.studios.index')->with('success', 'Studio berhasil dihapus.');
    }
}
