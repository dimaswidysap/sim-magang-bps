@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto font-montserrat p-4 md:p-8">
        <!-- Section Title -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-text">Tambah Logbook Harian</h1>
            <p class="text-sm text-text-light mt-1">Catat aktivitas dan perkembangan kegiatan magang Anda.</p>
        </div>

        <!-- Alert Session Error -->
        @if (session('error'))
            <div
                class="p-4 mb-6 text-sm text-danger bg-danger/10 border border-danger/20 rounded-2xl flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0 fill-current" viewBox="0 0 20 20">
                    <path
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Card Form Container -->
        <div class="bg-surface border border-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form data-confirm="Apakah anda sudah yakin?" action="{{ route('magang-logbook-store') }}" method="POST"
                enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Tanggal Kegiatan -->
                <div>
                    <label for="tanggal_kegiatan"
                        class="block text-xs font-bold text-text-light uppercase tracking-wider mb-2">
                        Tanggal Kegiatan <span class="text-danger">*</span>
                    </label>
                    <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" max="{{ date('Y-m-d') }}"
                        value="{{ old('tanggal_kegiatan', date('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-border text-text bg-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200 text-sm"
                        required>
                    @error('tanggal_kegiatan')
                        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Judul Kegiatan -->
                <div>
                    <label for="judul_kegiatan"
                        class="block text-xs font-bold text-text-light uppercase tracking-wider mb-2">
                        Judul Kegiatan <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="judul_kegiatan" name="judul_kegiatan" value="{{ old('judul_kegiatan') }}"
                        placeholder="Masukkan judul kegiatan..."
                        class="w-full px-4 py-2.5 rounded-xl border border-border text-text bg-surface placeholder:text-text-light/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200 text-sm"
                        required>
                    @error('judul_kegiatan')
                        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi Kegiatan -->
                <div>
                    <label for="deskripsi_kegiatan"
                        class="block text-xs font-bold text-text-light uppercase tracking-wider mb-2">
                        Deskripsi Kegiatan <span class="text-danger">*</span>
                    </label>
                    <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="5"
                        placeholder="Jelaskan detail kegiatan yang dilakukan..."
                        class="w-full px-4 py-2.5 rounded-xl border border-border text-text bg-surface placeholder:text-text-light/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200 text-sm resize-y"
                        required>{{ old('deskripsi_kegiatan') }}</textarea>
                    @error('deskripsi_kegiatan')
                        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Lampiran -->
                <div>
                    <label for="file_lampiran"
                        class="block text-xs font-bold text-text-light uppercase tracking-wider mb-2">
                        File Lampiran <span class="normal-case text-text-light/80 font-normal">(Opsional: Bisa lebih dari 1
                            file PNG/JPG - Maks 2MB/file)</span>
                    </label>
                    <!-- Ditambahkan atribut 'multiple' -->
                    <input type="file" id="file_lampiran" name="file_lampiran[]"
                        accept="image/png, image/jpeg, image/jpg" multiple
                        class="w-full text-sm text-text-light border border-border rounded-xl cursor-pointer bg-surface file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light/20 file:text-primary hover:file:bg-primary-light/30 file:cursor-pointer file:transition">

                    @error('file_lampiran')
                        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
                    @enderror

                    <!-- Container untuk Preview Gambar -->
                    <div id="image_preview_container"
                        class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 empty:hidden"></div>
                </div>

                <!-- Tombol Aksi -->
                <div class="w-full flex justify-end gap-4 py-5">
                    <x-buttonv2 href="{{ route('mahasiswa-index') }}" color="primary" class="w-full sm:w-auto">
                        Kembali
                    </x-buttonv2>

                    <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                            stroke="currentColor" class="w-4 h-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Masukan ke Logbook
                    </x-buttonv2>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Preview Multi-Image (Akumulasi File) -->
    <script>
        const inputLampiran = document.getElementById('file_lampiran');
        const previewContainer = document.getElementById('image_preview_container');

        // DataTransfer untuk menyimpan file secara persisten antar pilihan
        const dataTransfer = new DataTransfer();

        inputLampiran.addEventListener('change', function(event) {
            const files = event.target.files;

            // Masukkan file yang baru dipilih ke dalam DataTransfer
            if (files.length > 0) {
                Array.from(files).forEach(file => {
                    // Mencegah duplikasi file berdasarkan nama dan ukuran (opsional, tapi disarankan)
                    const isDuplicate = Array.from(dataTransfer.files).some(
                        existingFile => existingFile.name === file.name && existingFile.size === file
                        .size
                    );

                    if (!isDuplicate) {
                        dataTransfer.items.add(file);
                    }
                });
            }

            // Timpa daftar file pada input HTML dengan daftar file yang sudah diakumulasi
            inputLampiran.files = dataTransfer.files;

            // Render ulang preview
            renderPreview();
        });

        function renderPreview() {
            previewContainer.innerHTML = ''; // Kosongkan preview container dulu

            Array.from(inputLampiran.files).forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    // Card wrapper dengan relative positioning dan group hover
                    const card = document.createElement('div');
                    card.className =
                        'relative flex flex-col items-center gap-2 p-2 border border-border rounded-xl bg-background/50 shadow-sm group';

                    // Gambar
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-24 object-cover rounded-lg border border-border';

                    // Nama File
                    const fileName = document.createElement('span');
                    fileName.className =
                        'text-[10px] font-medium text-text-light truncate w-full text-center px-1';
                    fileName.textContent = file.name;
                    fileName.title = file.name; // Tooltip saat di-hover

                    // Tombol Hapus (Silang)
                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.className =
                        'absolute -top-2 -right-2 bg-danger text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-md opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer';
                    deleteBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    `;

                    // Aksi saat tombol hapus diklik
                    deleteBtn.onclick = function() {
                        dataTransfer.items.remove(index); // Hapus dari DataTransfer berdasarkan index
                        inputLampiran.files = dataTransfer.files; // Update ulang input file
                        renderPreview(); // Render ulang tampilan
                    };

                    card.appendChild(img);
                    card.appendChild(fileName);
                    card.appendChild(deleteBtn); // Masukkan tombol hapus ke dalam card
                    previewContainer.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endsection
