@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-4xl mx-auto space-y-6">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Edit Berita / Pengumuman</h1>
                    <p class="text-sm text-text-light mt-1">Perbarui informasi, perbaiki konten, atau kelola lampiran berita.
                    </p>
                </div>

                <x-buttonv2 href="{{ route('berita-index') }}" color="accent-dark" class="w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
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
                        <h3 class="text-sm font-semibold text-danger">Gagal menyimpan perubahan:</h3>
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
                        Formulir Edit Berita
                    </h2>
                </div>

                <form method="POST" action="{{ route('berita-update', $berita->id) }}" enctype="multipart/form-data"
                    data-confirm="Apakah Anda yakin ingin menerapkah perubahan?" class="m-0">
                    @csrf
                    @method('PUT')

                    <div class="p-6 md:p-8 space-y-6">

                        <!-- Input Judul -->
                        <div>
                            <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                Judul Berita
                            </label>
                            <input type="text" name="judul" value="{{ old('judul', $berita->judul) }}"
                                placeholder="Masukkan judul berita atau pengumuman..."
                                class="w-full rounded-xl border border-border bg-background px-4 py-3 text-text placeholder:text-text-light focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        </div>

                        <!-- Input Konten -->
                        <div>
                            <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                Isi Konten
                            </label>
                            <textarea name="konten" rows="8" placeholder="Tuliskan isi detail berita di sini..."
                                class="w-full rounded-xl border border-border bg-background px-4 py-3 text-text placeholder:text-text-light focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-y">{{ old('konten', $berita->konten) }}</textarea>
                        </div>

                        <!-- Daftar Lampiran Saat Ini -->
                        @if ($berita->attachments->isNotEmpty())
                            <div>
                                <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-3">
                                    Lampiran Saat Ini
                                </label>

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach ($berita->attachments as $lampiran)
                                        @php
                                            $ext = strtolower(pathinfo($lampiran->file_name, PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                            $filePath = asset('storage/' . ($lampiran->file_path ?? $lampiran->file_name));
                                        @endphp

                                        <!-- Card Lampiran -->
                                        <div id="card-{{ $lampiran->id }}" class="relative border border-border rounded-xl p-2.5 bg-background flex flex-col justify-between transition-all select-none overflow-hidden group">

                                            <!-- Checkbox tersembunyi untuk backend -->
                                            <input type="checkbox" name="hapus_lampiran[]" value="{{ $lampiran->id }}" id="hapus-{{ $lampiran->id }}" class="hidden">

                                            <!-- Tombol Icon Hapus di Pojok Kanan Atas -->
                                            <button type="button" onclick="toggleDeleteExisting('{{ $lampiran->id }}')"
                                                class="absolute top-2 right-2 z-20 p-1.5 rounded-full bg-white/90 shadow-md text-text-light hover:text-danger hover:bg-white transition-colors"
                                                title="Hapus file ini">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                            <!-- Overlay Ketika Ditandai Hapus (High Contrast & Clear UI) -->
                                            <div id="overlay-{{ $lampiran->id }}" class="hidden absolute inset-0 rounded-[14px] bg-slate-900/85 z-30 flex flex-col items-center justify-center p-2 text-center backdrop-blur-xs transition-all">
                                                <div class="w-8 h-8 rounded-full bg-danger/20 flex items-center justify-center text-danger mb-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-white mb-2 tracking-wide">Akan Dihapus</span>
                                                <button type="button" onclick="toggleDeleteExisting('{{ $lampiran->id }}')"
                                                    class="px-3 py-1.5 bg-white hover:bg-slate-100 text-danger text-[11px] font-bold rounded-lg shadow-sm transition-all flex items-center gap-1 active:scale-95">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                    </svg>
                                                    Batal Hapus
                                                </button>
                                            </div>

                                            <!-- Preview Visual (Gambar vs Dokumen) -->
                                            @if ($isImage)
                                                <img src="{{ $filePath }}" alt="{{ $lampiran->file_name }}" class="h-24 overflow-hidden w-full object-cover rounded-lg mb-2 border border-border/40">
                                            @else
                                                <div class="h-24 w-full bg-surface overflow-hidden rounded-lg mb-2 flex flex-col items-center justify-center text-text-light border border-border/50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-[10px] font-bold text-text-light tracking-wider uppercase">{{ $ext }}</span>
                                                </div>
                                            @endif

                                            <!-- Info Nama File -->
                                            <p class="text-[11px] font-semibold text-text truncate w-full px-1" title="{{ $lampiran->file_name }}">
                                                {{ $lampiran->file_name }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Input File Lampiran Baru -->
                        <div>
                            <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                Tambah Lampiran Baru <span class="normal-case font-normal text-[11px]">(Opsional)</span>
                            </label>
                            <div class="border-2 border-dashed border-border rounded-xl p-4 bg-[#F8FAFC] hover:border-primary transition-colors">
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
                                    Anda dapat memilih lebih dari satu file tambahan (Maks. 10 MB per file).
                                </p>

                                <!-- Grid Preview Lampiran Baru -->
                                <div id="preview-container" class="overflow-hidden mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 hidden border-t border-border pt-4">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer Form (Tombol Submit) -->
                    <div class="p-6 md:px-8 md:py-5 bg-background border-t border-border flex justify-end">

                        <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan Perubahan
                        </x-buttonv2>
                    </div>
                </form>
            </div>

        </section>
    </main>

    <!-- JavaScript Handler -->
    <script>
        // Toggle Status Hapus Lampiran Saat Ini
        function toggleDeleteExisting(id) {
            const checkbox = document.getElementById(`hapus-${id}`);
            const overlay = document.getElementById(`overlay-${id}`);
            const card = document.getElementById(`card-${id}`);

            checkbox.checked = !checkbox.checked;

            if (checkbox.checked) {
                overlay.classList.remove('hidden');
                card.classList.add('border-danger', 'ring-1', 'ring-danger');
            } else {
                overlay.classList.add('hidden');
                card.classList.remove('border-danger', 'ring-1', 'ring-danger');
            }
        }

        // Script Preview & Hapus Lampiran Baru
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('lampiran-input');
            const previewContainer = document.getElementById('preview-container');

            let selectedFiles = new DataTransfer();

            fileInput.addEventListener('change', function(e) {
                for (let i = 0; i < e.target.files.length; i++) {
                    selectedFiles.items.add(e.target.files[i]);
                }
                fileInput.files = selectedFiles.files;
                renderPreviews();
            });

            function removeNewFile(index) {
                selectedFiles.items.remove(index);
                fileInput.files = selectedFiles.files;
                renderPreviews();
            }

            function renderPreviews() {
                previewContainer.innerHTML = '';
                const files = selectedFiles.files;

                if (files.length > 0) {
                    previewContainer.classList.remove('hidden');

                    Array.from(files).forEach((file, index) => {
                        const card = document.createElement('div');
                        card.className = 'relative border border-border rounded-xl p-2.5 bg-white flex flex-col items-center justify-between text-center shadow-xs overflow-hidden group select-none';

                        // Tombol Icon Hapus di Pojok Kanan Atas Preview File Baru
                        const deleteBtn = document.createElement('button');
                        deleteBtn.type = 'button';
                        deleteBtn.className = 'absolute top-2 right-2 z-20 p-1.5 rounded-full bg-white/90 shadow-md text-text-light hover:text-danger hover:bg-white transition-colors';
                        deleteBtn.title = 'Hapus file ini';
                        deleteBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        `;
                        deleteBtn.onclick = function() {
                            removeNewFile(index);
                        };
                        card.appendChild(deleteBtn);

                        if (file.type.startsWith('image/')) {
                            // Render Preview Gambar
                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(file);
                            img.className = 'h-24 w-full object-cover rounded-lg mb-2 border border-border/40';
                            img.onload = () => URL.revokeObjectURL(img.src);
                            card.appendChild(img);
                        } else {
                            // Render Icon Dokumen
                            const ext = file.name.split('.').pop().toUpperCase();
                            const iconBox = document.createElement('div');
                            iconBox.className = 'h-24 overflow-hidden w-full bg-background rounded-lg mb-2 flex flex-col items-center justify-center text-text-light border border-border/50';
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
                        fileName.className = 'text-[11px] font-semibold text-text truncate w-full px-1';
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
            }
        });
    </script>
@endsection
