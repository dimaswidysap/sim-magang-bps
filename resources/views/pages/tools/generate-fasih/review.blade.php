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



                <x-buttonv2 href="{{ route('generate.form') }}" color="danger" data-confirm="Apakah anda yakin ingin mengulang?"  class="w-full sm:w-auto">
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
                        <div class="space-y-2 pt-1 w-full overflow-hidden">
                            <h3 class="font-bold text-text text-sm sm:text-base">Susun Folder</h3>
                            <p class="text-text-light">Pastikan struktur folder seperti berikut (nama file/folder bebas,
                                yang
                                penting berada di satu direktori):</p>
                            <pre
                                class="p-3.5 bg-footer text-surface rounded-xl text-xs font-mono overflow-x-auto border border-border/20 leading-relaxed">script.py
data_sensus.xlsx
folder_foto/
    nama_ppl_1/
        foto1.jpg
        foto2.jpg
    nama_ppl_2/
        foto1.jpg</pre>
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
