@php
    $currentStatus = request('status', 'diambil');
    $selectedBulan = $bulan ?? request('bulan');
    $selectedHari = $hari ?? request('hari');
    $isFilteredPeriode = !empty($selectedBulan) || !empty($selectedHari);

    $minDate = null;
    $maxDate = null;
    if (!empty($selectedBulan) && preg_match('/^\d{4}-\d{2}$/', $selectedBulan)) {
        $carbonBulan = \Carbon\Carbon::createFromFormat('Y-m', $selectedBulan);
        $minDate = $carbonBulan->copy()->startOfMonth()->toDateString();
        $maxDate = $carbonBulan->copy()->endOfMonth()->toDateString();
    }
@endphp

<!-- Wrapper Filter -->
<div class="mb-6 font-montserrat w-full space-y-4">
    <!-- Tab Status Tugas -->
    <div class="flex flex-wrap items-center gap-2 md:gap-3">

        <!-- Tombol Diambil -->
        <x-buttonv2 href="{{ request()->fullUrlWithQuery(['status' => 'diambil', 'bulan' => null, 'hari' => null, 'page' => null]) }}"
            color="{{ $currentStatus === 'diambil' ? 'primary' : 'surface' }}" class="grow sm:grow-0">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </x-slot>

            <span>Diambil</span>
        </x-buttonv2>

        <!-- Tombol Menunggu Review -->
        <x-buttonv2 href="{{ request()->fullUrlWithQuery(['status' => 'menunggu_review', 'bulan' => null, 'hari' => null, 'page' => null]) }}"
            color="{{ $currentStatus === 'menunggu_review' ? 'primary' : 'surface' }}" class="grow sm:grow-0">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot>

            <span>Menunggu Review</span>
        </x-buttonv2>

        <!-- Tombol Revisi -->
        <x-buttonv2 href="{{ request()->fullUrlWithQuery(['status' => 'revisi', 'bulan' => null, 'hari' => null, 'page' => null]) }}"
            color="{{ $currentStatus === 'revisi' ? 'primary' : 'surface' }}" class="grow sm:grow-0">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                    <path d="M12 20h9" />
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z" />
                    <path d="M15 5l3 3" />
                </svg>
            </x-slot>

            <span>Revisi</span>
        </x-buttonv2>

        <!-- Tombol Selesai -->
        <x-buttonv2 href="{{ request()->fullUrlWithQuery(['status' => 'selesai', 'page' => null]) }}"
            color="{{ $currentStatus === 'selesai' ? 'primary' : 'surface' }}" class="grow sm:grow-0">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot>

            <span>Selesai</span>
        </x-buttonv2>

    </div>

    {{-- ========================================================= --}}
    {{-- FILTER BERDASARKAN BULAN & HARI (KHUSUS TAB SELESAI)      --}}
    {{-- ========================================================= --}}
    @if ($currentStatus === 'selesai')
        <form method="GET" action="{{ route('tugas-saya') }}" id="form-filter-tugas-saya-selesai"
            class="bg-surface border border-border rounded-[10px] p-4 shadow-sm">
            <input type="hidden" name="status" value="selesai">
            @if (request()->filled('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1">
                    <!-- Filter Bulan -->
                    <div>
                        <label for="filter-bulan-mahasiswa"
                            class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-1.5">
                            Filter Bulan Selesai
                        </label>
                        <input type="month" id="filter-bulan-mahasiswa" name="bulan" value="{{ $selectedBulan }}"
                            class="w-full rounded-lg border border-border bg-background px-3 py-2 text-xs sm:text-sm text-text outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20" />
                    </div>

                    <!-- Filter Hari (di dalam bulan) -->
                    <div>
                        <label for="filter-hari-mahasiswa"
                            class="block text-xs font-semibold text-text-light uppercase tracking-wider mb-1.5">
                            Filter Hari (Tanggal Selesai)
                        </label>
                        <input type="date" id="filter-hari-mahasiswa" name="hari" value="{{ $selectedHari }}"
                            @if ($minDate) min="{{ $minDate }}" @endif
                            @if ($maxDate) max="{{ $maxDate }}" @endif
                            class="w-full rounded-lg border border-border bg-background px-3 py-2 text-xs sm:text-sm text-text outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20" />
                    </div>
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="flex items-center gap-2 shrink-0">
                    <x-buttonv2 type="submit" color="primary" class="text-xs py-2 px-4 inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </x-buttonv2>

                    @if ($isFilteredPeriode)
                        <x-buttonv2
                            href="{{ request()->fullUrlWithQuery(['status' => 'selesai', 'bulan' => null, 'hari' => null, 'page' => null]) }}"
                            color="danger" class="text-xs py-2 px-4 inline-flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset
                        </x-buttonv2>
                    @endif
                </div>
            </div>

            <!-- Info Ringkasan Filter Aktif -->
            <div
                class="mt-3 pt-2.5 border-t border-border/60 flex flex-wrap items-center justify-between gap-2 text-xs text-text-light">
                <div>
                    @if (!empty($selectedHari))
                        Menampilkan tugas selesai pada tanggal
                        <span class="font-bold text-text">
                            {{ \Carbon\Carbon::parse($selectedHari)->translatedFormat('d F Y') }}
                        </span>
                    @elseif (!empty($selectedBulan))
                        Menampilkan tugas selesai pada bulan
                        <span class="font-bold text-text">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $selectedBulan)->translatedFormat('F Y') }}
                        </span>
                    @else
                        Menampilkan seluruh riwayat tugas selesai
                    @endif
                </div>

                @if (isset($dataTugas))
                    <span class="font-semibold text-text">
                        Total: {{ method_exists($dataTugas, 'total') ? $dataTugas->total() : $dataTugas->count() }} tugas
                    </span>
                @endif
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const inputBulan = document.getElementById('filter-bulan-mahasiswa');
                const inputHari = document.getElementById('filter-hari-mahasiswa');

                if (!inputBulan || !inputHari) return;

                inputBulan.addEventListener('change', function () {
                    const valBulan = this.value; // format YYYY-MM

                    if (!valBulan) {
                        inputHari.removeAttribute('min');
                        inputHari.removeAttribute('max');
                        return;
                    }

                    const [tahun, bulan] = valBulan.split('-').map(Number);
                    const hariTerakhir = new Date(tahun, bulan, 0).getDate();
                    const minTanggal = `${valBulan}-01`;
                    const maxTanggal = `${valBulan}-${String(hariTerakhir).padStart(2, '0')}`;

                    inputHari.min = minTanggal;
                    inputHari.max = maxTanggal;

                    if (inputHari.value && !inputHari.value.startsWith(valBulan)) {
                        inputHari.value = '';
                    }
                });

                inputHari.addEventListener('change', function () {
                    if (this.value && !inputBulan.value) {
                        inputBulan.value = this.value.slice(0, 7);
                        inputBulan.dispatchEvent(new Event('change'));
                    }
                });
            });
        </script>
    @endif
</div>
