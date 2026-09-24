@props([
    'order',
    'context' => null,
    'size' => 'normal', // 'normal' | 'compact'
])

@php
    $syncService = app(\App\Services\OrderScheduleSyncService::class);
    $ctx = $context ?? $syncService->getScheduleContext($order);
@endphp

@if($ctx['departure_iso'] && $ctx['arrival_iso'])
<div x-data="tripCountdownTimer('{{ $ctx['departure_iso'] }}', '{{ $ctx['arrival_iso'] }}')"
     x-init="init()"
     class="w-full">
    {{-- Phase 1: Upcoming (Menuju Keberangkatan) --}}
    <div x-show="phase === 'upcoming'"
         class="rounded-2xl border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50/50 p-4 sm:p-5 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                    </span>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-900">
                        Waktu Menuju Keberangkatan
                    </span>
                </div>
                <p class="text-xs text-slate-600">
                    Jadwal Berangkat: <strong class="text-slate-900">{{ $ctx['departure_formatted'] }}</strong>
                </p>
            </div>

            {{-- Digital Countdown Clock --}}
            <div class="flex items-center gap-1.5 sm:gap-2">
                <template x-if="days > 0">
                    <div class="flex items-center gap-1">
                        <div class="flex flex-col items-center justify-center rounded-xl bg-white border border-blue-200 px-2.5 py-1.5 min-w-[48px] shadow-xs">
                            <span class="font-mono text-base sm:text-lg font-black text-blue-700 leading-none" x-text="formatDigits(days)"></span>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Hari</span>
                        </div>
                        <span class="font-bold text-blue-600 text-sm">:</span>
                    </div>
                </template>

                <div class="flex flex-col items-center justify-center rounded-xl bg-white border border-blue-200 px-2.5 py-1.5 min-w-[48px] shadow-xs">
                    <span class="font-mono text-base sm:text-lg font-black text-blue-700 leading-none" x-text="formatDigits(hours)"></span>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Jam</span>
                </div>
                <span class="font-bold text-blue-600 text-sm">:</span>

                <div class="flex flex-col items-center justify-center rounded-xl bg-white border border-blue-200 px-2.5 py-1.5 min-w-[48px] shadow-xs">
                    <span class="font-mono text-base sm:text-lg font-black text-blue-700 leading-none" x-text="formatDigits(minutes)"></span>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Menit</span>
                </div>
                <span class="font-bold text-blue-600 text-sm">:</span>

                <div class="flex flex-col items-center justify-center rounded-xl bg-white border border-blue-200 px-2.5 py-1.5 min-w-[48px] shadow-xs">
                    <span class="font-mono text-base sm:text-lg font-black text-blue-700 leading-none" x-text="formatDigits(seconds)"></span>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Detik</span>
                </div>
            </div>
        </div>

        <div class="mt-3 pt-3 border-t border-blue-200/60 flex items-center justify-between text-[11px] text-blue-800">
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8V5m0 14a9 9 0 110-18 9 9 0 0118 0z"/></svg>
                Check-in otomatis akan aktif saat waktu keberangkatan tiba.
            </span>
            <span class="font-semibold text-blue-900 hidden sm:inline">PO CAN Travel Live</span>
        </div>
    </div>

    {{-- Phase 2: In Transit (Bus Sedang Berjalan) --}}
    <div x-show="phase === 'in_transit'"
         class="rounded-2xl border border-amber-300 bg-gradient-to-r from-amber-50 to-orange-50/50 p-4 sm:p-5 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                    </span>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-900">
                        Bus Sedang Dalam Perjalanan
                    </span>
                    <span class="rounded bg-emerald-100 text-emerald-800 border border-emerald-300 px-2 py-0.5 text-[10px] font-bold">
                        Otomatis Check-In
                    </span>
                </div>
                <p class="text-xs text-slate-600">
                    Estimasi Tiba: <strong class="text-slate-900">{{ $ctx['arrival_formatted'] }}</strong>
                </p>
            </div>

            {{-- Countdown to Arrival --}}
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="text-right mr-1 hidden sm:block">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800 block">Sisa Waktu</span>
                    <span class="text-[11px] text-amber-700">Sampai Tujuan</span>
                </div>

                <div class="flex flex-col items-center justify-center rounded-xl bg-white border border-amber-200 px-2.5 py-1.5 min-w-[48px] shadow-xs">
                    <span class="font-mono text-base sm:text-lg font-black text-amber-700 leading-none" x-text="formatDigits(hours)"></span>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Jam</span>
                </div>
                <span class="font-bold text-amber-600 text-sm">:</span>

                <div class="flex flex-col items-center justify-center rounded-xl bg-white border border-amber-200 px-2.5 py-1.5 min-w-[48px] shadow-xs">
                    <span class="font-mono text-base sm:text-lg font-black text-amber-700 leading-none" x-text="formatDigits(minutes)"></span>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Menit</span>
                </div>
                <span class="font-bold text-amber-600 text-sm">:</span>

                <div class="flex flex-col items-center justify-center rounded-xl bg-white border border-amber-200 px-2.5 py-1.5 min-w-[48px] shadow-xs">
                    <span class="font-mono text-base sm:text-lg font-black text-amber-700 leading-none" x-text="formatDigits(seconds)"></span>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">Detik</span>
                </div>
            </div>
        </div>

        <div class="mt-3 pt-3 border-t border-amber-200/60 flex items-center justify-between text-[11px] text-amber-900">
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Penumpang telah tervalidasi boarding. Status akan otomatis selesai saat tiba.
            </span>
            <span class="font-semibold text-emerald-700">Check-in: {{ $ctx['checked_in_at'] ?? 'Otomatis' }}</span>
        </div>
    </div>

    {{-- Phase 3: Arrived / Completed (Tiba di Tujuan) --}}
    <div x-show="phase === 'completed'"
         class="rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50/50 p-4 sm:p-5 shadow-xs">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="text-sm font-bold text-emerald-950">Perjalanan Telah Selesai</h4>
                        <span class="rounded bg-emerald-200/60 text-emerald-900 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                            Tiba di Tujuan
                        </span>
                    </div>
                    <p class="text-xs text-emerald-800 mt-0.5">
                        Bus telah sampai di terminal tujuan pada {{ $ctx['arrival_formatted'] }}.
                    </p>
                </div>
            </div>
            <span class="rounded-lg bg-emerald-100 px-3 py-1 font-mono text-xs font-bold text-emerald-800 border border-emerald-200 hidden sm:inline-block">
                Otomatis Selesai
            </span>
        </div>
    </div>
</div>
@endif

@once
@push('scripts')
<script>
function tripCountdownTimer(departureIso, arrivalIso) {
    return {
        departureTime: new Date(departureIso).getTime(),
        arrivalTime: new Date(arrivalIso).getTime(),
        now: Date.now(),
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        phase: 'upcoming',
        timer: null,

        init() {
            this.update();
            this.timer = setInterval(() => {
                this.now = Date.now();
                this.update();
            }, 1000);
        },

        update() {
            if (this.now < this.departureTime) {
                this.phase = 'upcoming';
                const diff = Math.max(0, this.departureTime - this.now);
                this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
            } else if (this.now >= this.departureTime && this.now < this.arrivalTime) {
                this.phase = 'in_transit';
                const diff = Math.max(0, this.arrivalTime - this.now);
                this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
            } else {
                this.phase = 'completed';
                this.days = 0;
                this.hours = 0;
                this.minutes = 0;
                this.seconds = 0;
            }
        },

        formatDigits(num) {
            return String(num).padStart(2, '0');
        }
    };
}
</script>
@endpush
@endonce
