@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-4xl mx-auto space-y-6">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Tulis Berita / Pengumuman</h1>
                    <p class="text-sm text-text-light mt-1">Buat dan publikasikan informasi terbaru untuk mahasiswa magang.</p>
                </div>

                <x-buttonv2 href="{{ route('berita-index') }}" color="accent-dark" class="w-full sm:w-auto">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </x-slot>
                    kembali
                </x-buttonv2>
            </div>

            <!-- Alert Error (Validasi) -->
            @if ($errors->any())
                <div class="bg-danger/10 border border-danger p-4 rounded-xl flex items-start gap-3 shadow-sm">
                    <svg class="h-5 w-5 text-danger shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-danger">Gagal mempublikasikan berita:</h3>
                        <ul class="list-disc list-inside text-sm text-danger mt-1 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden">
                <div class="p-5 border-b border-border bg-background">
                    <h2 class="text-base font-bold text-text flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Formulir Berita
                    </h2>
                </div>

                <form method="POST" action="{{ route('berita-store') }}" enctype="multipart/form-data" class="m-0"
                    data-confirm="Apakah Anda yakin ingin mempublikasikan berita?">
                    @csrf
                    <div class="p-6 md:p-8 space-y-6">

                        <!-- Input Judul -->
                        <div>
                            <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                Judul Berita
                            </label>
                            <input type="text" name="judul" value="{{ old('judul') }}"
                                placeholder="Masukkan judul berita atau pengumuman..."
                                class="w-full rounded-xl border border-border bg-background px-4 py-3 text-text placeholder:text-text-light focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        </div>

                        <!-- Input Konten -->
                        <div>
                            <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                Isi Konten
                            </label>
                            <textarea name="konten" rows="8" placeholder="Tuliskan isi detail berita di sini..."
                                class="w-full rounded-xl border border-border bg-background px-4 py-3 text-text placeholder:text-text-light focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-y">{{ old('konten') }}</textarea>
                        </div>

                        <!-- Input File Lampiran (Bisa Banyak) -->
                        <div>
                            <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                Lampiran Foto / File <span class="normal-case font-normal text-[11px]">(Opsional)</span>
                            </label>
                            <div
                                class="border-2 border-dashed border-border rounded-xl p-4 bg-[#F8FAFC] hover:border-primary transition-colors">
                                <input type="file" name="lampiran[]" id="lampiran-input" multiple
                                    class="block w-full text-sm text-text-light cursor-pointer
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-xs file:font-semibold
                                    file:bg-primary file:text-white
                                    hover:file:bg-primary-dark transition-colors">
                                <p class="text-[11px] text-text-light mt-2.5 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-primary" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Anda dapat memilih lebih dari satu file (Maks. 10 MB per file).
                                </p>

                                <!-- Grid Preview Lampiran -->
                                <div id="preview-container" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 hidden border-t border-border pt-4">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer Form (Tombol Submit) -->
                    <div class="p-6 md:px-8 md:py-5 bg-background border-t border-border flex justify-end">

                        <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </x-slot>
                            Publikasikan Berita
                        </x-buttonv2>
                    </div>
                </form>
            </div>

        </section>
    </main>

    <!-- JavaScript Preview File -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('lampiran-input');
            const previewContainer = document.getElementById('preview-container');

            fileInput.addEventListener('change', function(e) {
                const files = e.target.files;
                previewContainer.innerHTML = ''; // Clear preview sebelumnya

                if (files.length > 0) {
                    previewContainer.classList.remove('hidden');

                    Array.from(files).forEach((file) => {
                        const card = document.createElement('div');
                        card.className = 'relative border border-border rounded-lg p-2 bg-white flex flex-col items-center justify-between text-center shadow-xs overflow-hidden';

                        if (file.type.startsWith('image/')) {
                            // Render Preview Gambar
                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(file);
                            img.className = 'h-24 w-full object-cover rounded-md mb-1.5';
                            img.onload = () => URL.revokeObjectURL(img.src); // Cleanup memori
                            card.appendChild(img);
                        } else {
                            // Render Icon Dokumen non-gambar
                            const ext = file.name.split('.').pop().toUpperCase();
                            const iconBox = document.createElement('div');
                            iconBox.className = 'h-24 w-full bg-background rounded-md mb-1.5 flex flex-col items-center justify-center text-text-light border border-border/50';
                            iconBox.innerHTML = `
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="text-[10px] font-bold text-text-light tracking-wider">${ext}</span>
                            `;
                            card.appendChild(iconBox);
                        }

                        // Nama File
                        const fileName = document.createElement('p');
                        fileName.className = 'text-[11px] font-medium text-text truncate w-full px-1';
                        fileName.title = file.name;
                        fileName.textContent = file.name;
                        card.appendChild(fileName);

                        // Ukuran File
                        const fileSize = document.createElement('span');
                        fileSize.className = 'text-[10px] text-text-light mt-0.5';
                        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                        fileSize.textContent = `${sizeMB} MB`;
                        card.appendChild(fileSize);

                        previewContainer.appendChild(card);
                    });
                } else {
                    previewContainer.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
