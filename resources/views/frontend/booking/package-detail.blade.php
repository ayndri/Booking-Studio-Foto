@extends('layouts.frontend')
@section('title', 'Detail Paket - UPFotoStudio')

@push('styles')
<style>
.bk {
    --g:#2f5443;--gd:#1f3d30;--gl:#3d7a5a;--gp:#eef7f2;
    --k:#111;--ks:#555;--km:#999;--bg:#fff;--bg2:#fafaf8;--bg3:#f4f3f0;--br:rgba(0,0,0,.07);
    --w:min(1200px,calc(100% - 48px));
    font-family:'Poppins',sans-serif;color:var(--k);
    width:100vw;max-width:100vw;margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);background:var(--bg3);
}
.bk h1,.bk h2,.bk h3{font-family:'Playfair Display',serif;letter-spacing:-.02em;}
.bkc{width:var(--w);margin-inline:auto;}

/* steps */
.bk-steps-bar{background:var(--bg);border-bottom:1px solid var(--br);padding:20px 0;}
.bk-steps{display:flex;align-items:center;justify-content:center;max-width:480px;margin-inline:auto;}
.bk-step{display:flex;flex-direction:column;align-items:center;gap:7px;flex-shrink:0;}
.bk-num{width:38px;height:38px;border-radius:50%;border:2px solid #ddd;background:#f2f2f0;color:#bbb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.88rem;transition:all 220ms ease;}
.bk-step.active .bk-num{background:var(--g);border-color:var(--g);color:#fff;box-shadow:0 4px 14px rgba(47,84,67,.3);}
.bk-step.done   .bk-num{background:var(--gp);border-color:var(--g);color:var(--g);}
.bk-lbl{font-size:.7rem;font-weight:600;color:#bbb;white-space:nowrap;}
.bk-step.active .bk-lbl{color:var(--g);}
.bk-step.done   .bk-lbl{color:var(--gl);}
.bk-conn{flex:1;height:1.5px;background:#e0ddd8;margin:0 10px 26px;min-width:36px;max-width:80px;}
.bk-conn.done{background:var(--g);}

/* cards */
.bk-card{background:var(--bg);border:1px solid var(--br);border-radius:18px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.05);}
.bk-pad{padding:20px 22px;}

/* pkg info */
.bk-pkg-img{width:100%;aspect-ratio:3/4;object-fit:contain;display:block;background:#f4f3f0;}
.bk-studio-badge{display:inline-block;background:var(--gp);color:var(--g);border-radius:999px;padding:4px 12px;font-size:.65rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px;}
.bk-pkg-name{font-size:clamp(1.1rem,1.7vw,1.55rem);font-weight:700;color:var(--k);margin-bottom:14px;line-height:1.2;}
.bk-perks{list-style:none;padding:0;margin:0;}
.bk-perks li{display:flex;align-items:flex-start;gap:10px;font-size:.86rem;color:var(--ks);padding:7px 0;border-bottom:1px solid var(--br);line-height:1.5;}
.bk-perks li:last-child{border-bottom:none;}
.bk-perks li::before{content:'';flex-shrink:0;margin-top:6px;width:6px;height:6px;border-radius:50%;background:var(--gl);}

/* calendar */
.cal-nav{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;}
.cal-nav-btn{width:30px;height:30px;border-radius:8px;border:1px solid var(--br);background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:.9rem;color:var(--ks);text-decoration:none;transition:background 140ms ease,border-color 140ms ease,color 140ms ease;}
.cal-nav-btn:hover{background:var(--gp);border-color:var(--g);color:var(--g);}
.cal-month{font-weight:600;font-size:.92rem;color:var(--k);}
.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;}
.cal-hdr{text-align:center;font-size:.66rem;font-weight:600;color:var(--km);padding:5px 0;text-transform:uppercase;letter-spacing:.06em;}

/* date buttons — no more <a>, use <button> */
.cal-cell{text-align:center;border-radius:8px;padding:8px 2px;border:1px solid transparent;display:block;font-weight:600;font-size:.84rem;color:var(--k);background:transparent;cursor:pointer;width:100%;transition:background 140ms ease,color 140ms ease;}
.cal-cell:hover:not(.cal-past):not(.cal-muted){background:var(--gp);color:var(--g);}
.cal-cell.cal-muted{color:#ccc;pointer-events:none;}
.cal-cell.cal-past{color:#ccc;background:#f6f6f4;cursor:not-allowed;}
.cal-cell.cal-active{background:var(--g);color:#fff;box-shadow:0 3px 8px rgba(47,84,67,.25);}

/* slot buttons */
.slots-title{font-size:.78rem;font-weight:600;color:var(--ks);margin:16px 0 10px;text-transform:uppercase;letter-spacing:.08em;}
.slot-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:7px;}
.slot-btn{text-align:center;border-radius:9px;padding:9px 4px;border:1.5px solid rgba(0,0,0,.1);color:var(--k);font-weight:600;font-size:.82rem;background:var(--bg);cursor:pointer;transition:background 140ms ease,border-color 140ms ease,color 140ms ease;}
.slot-btn:hover:not(.slot-off){background:var(--gp);border-color:var(--g);color:var(--g);}
.slot-btn.slot-off{background:#f2f2f0;border-color:#e5e5e0;color:#ccc;pointer-events:none;cursor:not-allowed;}
.slot-btn.slot-active{background:var(--g);color:#fff;border-color:var(--g);box-shadow:0 3px 8px rgba(47,84,67,.22);}
.slot-loading{text-align:center;padding:18px;color:var(--km);font-size:.84rem;}

/* summary */
.bk-sum{background:var(--bg);border:1px solid var(--br);border-radius:18px;padding:20px 22px;box-shadow:0 2px 10px rgba(0,0,0,.05);}
.bk-sum-lbl{font-size:.68rem;font-weight:600;color:var(--km);text-transform:uppercase;letter-spacing:.1em;margin-bottom:3px;}
.bk-sum-val{font-size:.94rem;font-weight:600;color:var(--k);margin-bottom:14px;}
.bk-sum-hr{border:none;border-top:1px solid var(--br);margin:12px 0;}
.bk-sum-row{display:flex;justify-content:space-between;align-items:center;font-size:.9rem;color:var(--ks);margin-bottom:6px;}
.bk-price{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--g);}

.bk-next-btn{display:block;width:100%;text-align:center;background:var(--g);color:#fff;border:none;border-radius:999px;padding:13px;font-family:'Poppins',sans-serif;font-size:.86rem;font-weight:600;cursor:pointer;margin-top:14px;transition:background 180ms ease,transform 180ms ease;}
.bk-next-btn:hover{background:var(--gd);transform:translateY(-1px);}
.bk-next-btn:disabled{background:#d0d0cc;cursor:not-allowed;transform:none;}
.bk-back{font-size:.82rem;color:var(--ks);text-decoration:none;display:inline-block;margin-bottom:24px;transition:color 140ms ease;}
.bk-back:hover{color:var(--g);}

@media(max-width:991.98px){.bk{--w:min(1200px,calc(100% - 32px));}}.
@media(max-width:575.98px){.bk{--w:calc(100% - 24px);}.slot-grid{grid-template-columns:repeat(2,1fr);}}
</style>
@endpush

@section('content')
<div class="bk">

    {{-- STEP 1 --}}
    <div class="bk-steps-bar">
        <div class="bk-steps">
            <div class="bk-step active"><div class="bk-num">1</div><span class="bk-lbl">Pilih Jadwal</span></div>
            <div class="bk-conn"></div>
            <div class="bk-step"><div class="bk-num">2</div><span class="bk-lbl">Detail Pesanan</span></div>
            <div class="bk-conn"></div>
            <div class="bk-step"><div class="bk-num">3</div><span class="bk-lbl">Pembayaran</span></div>
        </div>
    </div>

    <div class="bkc" style="padding:36px 0 72px;">
        <a href="{{ route('frontend.pricing', ['studio_id' => $servicePackage->studio_id]) }}" class="bk-back">← Kembali ke Paket</a>

        <div class="row g-4">

            {{-- Package info --}}
            <div class="col-lg-4">
                <div class="bk-card">
                    <img src="{{ $packageImage }}" alt="{{ $servicePackage->name }}" class="bk-pkg-img">
                    <div class="bk-pad">
                        <span class="bk-studio-badge">{{ $servicePackage->studio->name }}</span>
                        <h1 class="bk-pkg-name">{{ $servicePackage->name }}</h1>
                        <ul class="bk-perks">
                            <li>{{ $peopleLabel }}</li>
                            <li>{{ $servicePackage->duration_minutes }} menit sesi foto</li>
                            @foreach($benefits as $b)<li>{{ $b }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Calendar + Slots (JS-driven, no reload on date/time pick) --}}
            <div class="col-lg-5">
                <div class="bk-card bk-pad">
                    <h2 style="font-size:1.25rem;margin-bottom:18px;color:var(--k);">Pilih Tanggal &amp; Waktu</h2>

                    {{-- Month nav: still server-side (new month = new data) --}}
                    <div class="cal-nav">
                        <a class="cal-nav-btn" href="{{ route('frontend.booking.package-detail', ['servicePackage'=>$servicePackage->id,'month'=>$prevMonth,'date'=>\Carbon\Carbon::createFromFormat('Y-m',$prevMonth)->startOfMonth()->toDateString()]) }}">&lsaquo;</a>
                        <span class="cal-month">{{ $monthCursor->translatedFormat('F Y') }}</span>
                        <a class="cal-nav-btn" href="{{ route('frontend.booking.package-detail', ['servicePackage'=>$servicePackage->id,'month'=>$nextMonth,'date'=>\Carbon\Carbon::createFromFormat('Y-m',$nextMonth)->startOfMonth()->toDateString()]) }}">&rsaquo;</a>
                    </div>

                    {{-- Day headers --}}
                    <div class="cal-grid mb-1">
                        @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $d)
                            <div class="cal-hdr">{{ $d }}</div>
                        @endforeach
                    </div>

                    {{-- Date cells — buttons, no href --}}
                    @foreach($calendarWeeks as $week)
                        <div class="cal-grid mb-1">
                            @foreach($week as $day)
                                @if($day['is_past'])
                                    <button type="button" class="cal-cell cal-past" disabled>{{ $day['day'] }}</button>
                                @elseif(!$day['is_current_month'])
                                    <button type="button" class="cal-cell cal-muted" disabled>{{ $day['day'] }}</button>
                                @else
                                    <button type="button"
                                            class="cal-cell {{ $day['is_selected'] ? 'cal-active' : '' }}"
                                            data-date="{{ $day['date'] }}"
                                            onclick="selectDate('{{ $day['date'] }}')">
                                        {{ $day['day'] }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @endforeach

                    {{-- Time slots --}}
                    <p class="slots-title">Pilih Jam Mulai</p>
                    <div class="slot-grid" id="slotGrid">
                        @foreach($availableSlots as $slot)
                            <button type="button"
                                    class="slot-btn {{ !$slot['available'] ? 'slot-off' : '' }} {{ $selectedTime===$slot['time'] ? 'slot-active' : '' }}"
                                    data-time="{{ $slot['time'] }}"
                                    {{ !$slot['available'] ? 'disabled' : '' }}
                                    onclick="selectTime('{{ $slot['time'] }}', {{ $slot['available'] ? 'true' : 'false' }})">
                                {{ $slot['time'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="col-lg-3">
                <div style="position:sticky;top:88px;">
                    <div class="bk-sum">
                        <p class="bk-sum-lbl">Tanggal dipilih</p>
                        <div class="bk-sum-val" id="summaryDatetime">
                            {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('j F Y') }}
                            @if($selectedTime) &bull; {{ $selectedTime }} @endif
                        </div>
                        <hr class="bk-sum-hr">
                        <div class="bk-sum-row"><span>Harga paket</span></div>
                        <div class="bk-price mb-2">Rp{{ number_format($servicePackage->price,0,',','.') }}</div>

                        {{-- Form hidden inputs updated by JS --}}
                        <form method="get" action="{{ route('frontend.booking.order') }}" id="nextForm">
                            <input type="hidden" name="package_id"   value="{{ $servicePackage->id }}">
                            <input type="hidden" name="booking_date" id="formDate" value="{{ $selectedDate }}">
                            <input type="hidden" name="start_time"   id="formTime" value="{{ $selectedTime ?? '' }}">
                            <button type="submit" id="nextBtn" class="bk-next-btn"
                                    {{ empty($selectedTime) ? 'disabled' : '' }}>
                                Lanjut ke Detail &rarr;
                            </button>
                        </form>
                        @if(empty($selectedTime))
                            <p style="font-size:.78rem;color:var(--km);text-align:center;margin-top:8px;">Pilih jam terlebih dahulu</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const SLOTS_URL   = "{{ route('frontend.booking.slots', $servicePackage->id) }}";
let currentDate   = "{{ $selectedDate }}";
let currentTime   = "{{ $selectedTime ?? '' }}";

/* ── Select date (AJAX fetch slots) ── */
async function selectDate(date) {
    if (date === currentDate) return;

    // Update calendar UI
    document.querySelectorAll('.cal-cell[data-date]').forEach(b => b.classList.remove('cal-active'));
    const activeBtn = document.querySelector(`.cal-cell[data-date="${date}"]`);
    if (activeBtn) activeBtn.classList.add('cal-active');

    currentDate = date;
    currentTime = '';
    updateFormInputs();
    updateNextBtn();
    updateSummaryDate();

    // Show loading
    document.getElementById('slotGrid').innerHTML = '<div class="slot-loading">Memuat slot tersedia...</div>';

    try {
        const res   = await fetch(`${SLOTS_URL}?date=${date}`);
        const slots = await res.json();
        renderSlots(slots);
    } catch(e) {
        document.getElementById('slotGrid').innerHTML = '<div class="slot-loading" style="color:#dc2626">Gagal memuat slot. Coba lagi.</div>';
    }
}

/* ── Select time (pure JS, no server call) ── */
function selectTime(time, available) {
    if (!available) return;

    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('slot-active'));
    const btn = document.querySelector(`.slot-btn[data-time="${time}"]`);
    if (btn) btn.classList.add('slot-active');

    currentTime = time;
    updateFormInputs();
    updateNextBtn();
    updateSummaryDate();
}

/* ── Helpers ── */
function updateFormInputs() {
    document.getElementById('formDate').value = currentDate;
    document.getElementById('formTime').value = currentTime;
}

function updateNextBtn() {
    const btn = document.getElementById('nextBtn');
    btn.disabled = !currentTime;
}

function updateSummaryDate() {
    const el = document.getElementById('summaryDatetime');
    const d  = new Date(currentDate + 'T00:00:00');
    const opts = { day: 'numeric', month: 'long', year: 'numeric' };
    const label = d.toLocaleDateString('id-ID', opts);
    el.textContent = label + (currentTime ? ' • ' + currentTime : '');
}

function renderSlots(slots) {
    const grid = document.getElementById('slotGrid');
    grid.innerHTML = '';
    slots.forEach(slot => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'slot-btn' + (slot.available ? '' : ' slot-off');
        btn.dataset.time = slot.time;
        btn.textContent  = slot.time;
        if (!slot.available) { btn.disabled = true; }
        else { btn.addEventListener('click', () => selectTime(slot.time, true)); }
        grid.appendChild(btn);
    });
}
</script>
@endpush
