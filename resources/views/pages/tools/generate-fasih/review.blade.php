{{-- resources/views/generate/review.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 font-montserrat my-6 sm:my-10 space-y-6 sm:space-y-8">
        <!-- Section Title & Navigation Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-text">Langkah 5: Generate Script</h1>
                <p class="text-xs sm:text-sm text-text-light mt-1 leading-relaxed">
                    Konfigurasi Anda telah siap. Unduh script Python dan ikuti panduan penggunaan di bawah.
                </p>
            </div>

            <!-- Tombol Aksi Top Header (Kembali & Ulang) -->
            <div class="flex items-center gap-2 shrink-0">

                <x-buttonv2 href="{{ route('generate.mappingForm') }}" color="primary" class="w-full sm:w-auto">

                    Kembali
                </x-buttonv2>



                <x-buttonv2 href="{{ route('generate.form') }}" color="danger"
                    data-confirm="Apakah anda yakin ingin mengulang?" class="w-full sm:w-auto">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.609-1.276z"
                            clip-rule="evenodd" />
                    </svg>
                    Ulang Prosedur
                </x-buttonv2>



            </div>
        </div>

        <!-- Ringkasan Konfigurasi Card -->
        <div class="bg-surface border border-border rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-border/60">
                <div>
                    <h2 class="text-lg font-bold text-text">Ringkasan Konfigurasi</h2>
                    <p class="text-xs text-text-light mt-0.5">Periksa kembali detail pengaturan dokumen Anda.</p>
                </div>

                <!-- Tombol Download Utama -->
                <x-buttonv2 href="{{ route('generate.downloadScript') }}" color="accent-dark" class="w-full sm:w-auto">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Download script.py
                </x-buttonv2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Header Dokumen List -->
                <div class="space-y-2">
                    <span class="block text-xs font-bold text-text-light uppercase tracking-wider">Header Dokumen</span>
                    <ol class="space-y-2 list-none p-0 m-0">
                        @foreach ($headerLines as $line)
                            <li
                                class="flex items-center gap-2.5 p-2.5 bg-background border border-border rounded-xl text-xs text-text font-medium">
                                <span
                                    class="flex items-center justify-center w-5 h-5 rounded-md bg-primary-light/20 text-primary font-bold text-[10px] shrink-0">
                                    {{ $loop->iteration }}
                                </span>
                                <span class="truncate">{{ $line }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <!-- Mapping Field & Match Column -->
                <div class="space-y-4">
                    <div class="space-y-2">
                        <span class="block text-xs font-bold text-text-light uppercase tracking-wider">Mapping Field</span>
                        <ul class="space-y-2 list-none p-0 m-0">
                            @foreach ($fieldMappings as $mapping)
                                <li
                                    class="flex items-center justify-between p-2.5 bg-background border border-border rounded-xl text-xs text-text">
                                    <span class="font-semibold text-text">{{ $mapping['label'] }}</span>
                                    <div class="flex items-center gap-1.5 text-text-light font-mono text-[11px]">
                                        <span>&rarr;</span>
                                        <span
                                            class="px-2 py-0.5 rounded bg-surface border border-border font-semibold text-primary">{{ $mapping['column'] }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div
                        class="p-3 bg-primary-light/10 border border-primary-light/30 rounded-xl flex items-center justify-between">
                        <span class="text-xs font-semibold text-text">Kolom Cocok Folder:</span>
                        <span class="px-2.5 py-1 bg-primary text-surface font-bold text-xs rounded-lg shadow-2xs">
                            {{ $matchColumn }}
                        </span>
                    </div>
                </div>
            </div>



            <!-- Panduan Instalasi & Cara Pakai Card -->
            <div class="bg-surface border border-border rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-xs space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-text">Panduan Instalasi & Cara Pakai</h2>
                    <p class="text-xs text-text-light mt-0.5">Ikuti petunjuk di bawah ini untuk menjalankan script di
                        komputer
                        Anda.</p>
                </div>

                <div class="space-y-6 text-xs sm:text-sm text-text leading-relaxed">
                    <!-- Step 1 -->
                    <div class="flex gap-3 sm:gap-4 items-start">
                        <span
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-primary text-surface font-bold text-xs sm:text-sm shrink-0">1</span>
                        <div class="space-y-1 pt-1">
                            <h3 class="font-bold text-text text-sm sm:text-base">Install Python</h3>
                            <p class="text-text-light">
                                Download dan install Python dari
                                <a href="https://www.python.org/downloads/" target="_blank"
                                    class="text-primary hover:text-primary-dark font-semibold underline">python.org</a>
                                (minimal versi 3.9). Saat instalasi di Windows, pastikan mencentang opsi <strong
                                    class="text-text font-bold">"Add Python to PATH"</strong>.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex gap-3 sm:gap-4 items-start">
                        <span
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-primary text-surface font-bold text-xs sm:text-sm shrink-0">2</span>
                        <div class="space-y-2 pt-1 w-full overflow-hidden">
                            <h3 class="font-bold text-text text-sm sm:text-base">Install Dependency</h3>
                            <p class="text-text-light">Buka Terminal / Command Prompt di folder tempat <code
                                    class="px-1.5 py-0.5 rounded bg-background border border-border text-xs text-primary font-mono">script.py</code>
                                disimpan, lalu jalankan command:</p>
                            <pre class="p-3.5 bg-footer text-surface rounded-xl text-xs font-mono overflow-x-auto border border-border/20">pip install pandas python-docx Pillow openpyxl</pre>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex gap-3 sm:gap-4 items-start">
                        <span
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-primary text-surface font-bold text-xs sm:text-sm shrink-0">3</span>

                        <div class="space-y-3 pt-1 w-full overflow-hidden">
                            <div>
                                <h3 class="font-bold text-text text-sm sm:text-base">Susun Folder</h3>
                                <p class="text-text-light text-xs sm:text-sm mt-0.5">Pastikan struktur folder seperti
                                    berikut (nama file/folder bebas, yang penting berada di satu direktori):</p>
                            </div>

                            <!-- Visual File Tree Explorer Container -->
                            <div
                                class="p-3.5 bg-surface border border-border/80 rounded-2xl font-mono text-xs text-text shadow-xs space-y-1.5">

                                <!-- Root File: script.py -->
                                <div
                                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-background/80 transition-colors">
                                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-3 18" />
                                    </svg>
                                    <span class="font-medium text-text">script.py</span>
                                    <span
                                        class="ml-auto text-[10px] px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-600 font-sans font-semibold">Python</span>
                                </div>

                                <!-- Root File: data_sensus.xlsx -->
                                <div
                                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-background/80 transition-colors">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <span class="font-medium text-text">data_sensus.xlsx</span>
                                    <span
                                        class="ml-auto text-[10px] px-1.5 py-0.5 rounded bg-emerald-600/10 text-emerald-700 font-sans font-semibold">Excel</span>
                                </div>

                                <!-- Root Folder: folder_foto/ -->
                                <div class="space-y-1">
                                    <div
                                        class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-primary-light/10 text-primary font-semibold">
                                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                                            <path
                                                d="M2 6a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                        </svg>
                                        <span>folder_foto/</span>
                                    </div>

                                    <!-- Subfolders Container with Tree Guide Line -->
                                    <div class="ml-4 pl-3 border-l-2 border-border/60 space-y-2 pt-1">

                                        <!-- Subfolder 1 -->
                                        <div class="space-y-1">
                                            <div
                                                class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-background/80 transition-colors">
                                                <svg class="w-3.5 h-3.5 text-primary/70 shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3.75 9.776c.112-.017.227-.026.344-.026h15.816c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932h12.378a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                                                </svg>
                                                <span class="font-medium text-text">nama_ppl_1/</span>
                                            </div>

                                            <!-- Images inside Subfolder 1 -->
                                            <div class="ml-3 pl-3 border-l-2 border-border/40 space-y-1">
                                                <div
                                                    class="flex items-center gap-2 px-2 py-0.5 text-text-light text-[11px]">
                                                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                    </svg>
                                                    <span>foto1.jpg</span>
                                                </div>
                                                <div
                                                    class="flex items-center gap-2 px-2 py-0.5 text-text-light text-[11px]">
                                                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                    </svg>
                                                    <span>foto2.jpg</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Subfolder 2 -->
                                        <div class="space-y-1">
                                            <div
                                                class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-background/80 transition-colors">
                                                <svg class="w-3.5 h-3.5 text-primary/70 shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3.75 9.776c.112-.017.227-.026.344-.026h15.816c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932h12.378a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                                                </svg>
                                                <span class="font-medium text-text">nama_ppl_2/</span>
                                            </div>

                                            <!-- Images inside Subfolder 2 -->
                                            <div class="ml-3 pl-3 border-l-2 border-border/40 space-y-1">
                                                <div
                                                    class="flex items-center gap-2 px-2 py-0.5 text-text-light text-[11px]">
                                                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                    </svg>
                                                    <span>foto1.jpg</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex gap-3 sm:gap-4 items-start">
                        <span
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-primary text-surface font-bold text-xs sm:text-sm shrink-0">4</span>
                        <div class="space-y-2 pt-1 w-full overflow-hidden">
                            <h3 class="font-bold text-text text-sm sm:text-base">Jalankan Script</h3>
                            <pre class="p-3.5 bg-footer text-surface rounded-xl text-xs font-mono overflow-x-auto border border-border/20">python script.py</pre>
                            <p class="text-text-light">
                                Jika ditemukan lebih dari satu file Excel atau folder, script akan menampilkan pilihan
                                interaktif di terminal — ketik angka sesuai pilihan Anda.
                            </p>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="flex gap-3 sm:gap-4 items-start">
                        <span
                            class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-primary text-surface font-bold text-xs sm:text-sm shrink-0">5</span>
                        <div class="space-y-1 pt-1">
                            <h3 class="font-bold text-text text-sm sm:text-base">Hasil Dokumen</h3>
                            <p class="text-text-light leading-relaxed">
                                Setelah proses selesai, akan muncul file <code
                                    class="px-1.5 py-0.5 rounded bg-background border border-border text-xs text-primary font-mono font-bold">HASIL_DOKUMEN.docx</code>
                                di folder yang sama. Jika terdapat foto yang ditolak (misal terdeteksi hasil tangkapan
                                kamera/screen), akan otomatis dibuatkan file <code
                                    class="px-1.5 py-0.5 rounded bg-background border border-border text-xs text-danger font-mono font-bold">DAFTAR_PELANGGARAN.docx</code>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
