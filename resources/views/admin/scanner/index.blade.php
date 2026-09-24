<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-amber-600">OPERASIONAL</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-950">Scanner E-Ticket</h1>
                <p class="mt-1 text-sm text-slate-500">Scan QR Code tiket customer untuk melakukan check-in otomatis.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Kembali ke Home
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-7 sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800 flex items-center gap-2.5 shadow-xs mb-6">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-bold text-red-800 flex items-center gap-2.5 shadow-xs mb-6">
                <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center">
            <div class="w-full max-w-lg mb-6">
                <div id="qr-reader" class="w-full rounded-xl overflow-hidden border-2 border-dashed border-blue-300"></div>
            </div>

            <div class="text-center space-y-2">
                <h3 class="font-bold text-slate-900 text-lg">Arahkan QR Code E-Ticket</h3>
                <p class="text-slate-500 text-sm">Pastikan QR Code berada di dalam bingkai kamera.</p>
            </div>

            <form id="qr-form" method="POST" action="{{ route('admin.orders.qr.check-in') }}" class="hidden">
                @csrf
                @method('PATCH')
                <input type="hidden" name="qr_payload" id="qr_payload">
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", 
                { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 }, 
                /* verbose= */ false
            );
            
            html5QrcodeScanner.render(function (decodedText, decodedResult) {
                // handle on success
                document.getElementById('qr_payload').value = decodedText;
                
                // Pause scanner or stop it to prevent multiple submissions
                html5QrcodeScanner.clear();
                
                // Submit form
                document.getElementById('qr-form').submit();
            }, function (error) {
                // handle scan failure, usually better to ignore and keep scanning
            });
        });
    </script>
    @endpush
</x-app-layout>
