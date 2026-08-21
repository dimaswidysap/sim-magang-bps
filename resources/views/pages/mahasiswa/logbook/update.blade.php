@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto font-montserrat">
        <!-- Section Title -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-text">Edit Kegiatan Mandiri</h1>
            <p class="text-sm text-text-light mt-1">Perbarui rincian logbook kegiatan mandiri yang telah Anda catat.</p>
        </div>

        <!-- Alert List Errors -->
        @if ($errors->any())
            <div class="p-4 mb-6 text-sm text-danger bg-danger/10 border border-danger/20 rounded-2xl">
                <div class="flex items-center gap-2 font-semibold mb-2">
                    <svg class="w-5 h-5 shrink-0 fill-current" viewBox="0 0 20 20">
                        <path
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                    </svg>
                    <span>Terdapat beberapa kesalahan input:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Form Container -->
        <div class="bg-surface border border-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form data-confirm="Apakah anda yakin ingin menerapkan perubahan?" method="POST"
                action="{{ route('logbook-mandiri-update', $logbook->id) }}" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Tanggal Kegiatan -->
                <div>
                    <label for="tanggal_kegiatan"
                        class="block text-xs font-bold text-text-light uppercase tracking-wider mb-2">
                        Tanggal Kegiatan <span class="text-danger">*</span>
                    </label>
                    <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan"
                        value="{{ old('tanggal_kegiatan', $logbook->tanggal_kegiatan->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-border text-text bg-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200 text-sm"
                        required>
                </div>

                <!-- Judul Kegiatan -->
                <div>
                    <label for="judul_kegiatan"
                        class="block text-xs font-bold text-text-light uppercase tracking-wider mb-2">
                        Judul Kegiatan <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="judul_kegiatan" name="judul_kegiatan"
                        value="{{ old('judul_kegiatan', $logbook->judul_kegiatan) }}"
                        placeholder="Masukkan judul kegiatan..."
                        class="w-full px-4 py-2.5 rounded-xl border border-border text-text bg-surface placeholder:text-text-light/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200 text-sm"
                        required>
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
                        required>{{ old('deskripsi_kegiatan', $logbook->deskripsi_kegiatan) }}</textarea>
                </div>

                <!-- Preview Lampiran Saat Ini -->
                @if ($logbook->file_lampiran)
                    <div class="p-4 bg-background border border-border rounded-xl space-y-3">
                        <span class="block text-xs font-bold text-text-light uppercase tracking-wider">Lampiran Saat
                            Ini</span>

                        <div class="flex items-center gap-4">
                            @if ($logbook->isGambar())
                                <div
                                    class="rounded-xl overflow-hidden border border-border bg-surface w-28 h-28 flex items-center justify-center shrink-0">
                                    <img src="{{ Storage::url($logbook->file_lampiran) }}" alt="Lampiran"
                                        class="w-full h-full object-cover">
                                </div>
                            @else
                                <a href="{{ Storage::url($logbook->file_lampiran) }}" target="_blank"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-primary-dark underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Lihat File Lampiran
                                </a>
                            @endif
                        </div>

                        <div class="pt-2 border-t border-border/60">
                            <label
                                class="inline-flex items-center gap-2 cursor-pointer text-sm text-danger hover:text-danger/80 transition">
                                <input type="checkbox" name="hapus_lampiran" value="1"
                                    class="w-4 h-4 rounded border-border text-danger focus:ring-danger/20">
                                <span class="font-medium">Hapus lampiran ini</span>
                            </label>
                        </div>
                    </div>
                @endif

                <!-- File Lampiran Baru -->
                <div>
                    <label for="file_lampiran"
                        class="block text-xs font-bold text-text-light uppercase tracking-wider mb-2">
                        Ganti / Tambah Lampiran <span class="normal-case text-text-light/80 font-normal">(Opsional: PNG,
                            JPG, JPEG - Maks 10MB)</span>
                    </label>
                    <input type="file" id="file_lampiran" name="file_lampiran" accept="image/png, image/jpeg, image/jpg"
                        class="w-full text-sm text-text-light border border-border rounded-xl cursor-pointer bg-surface file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light/20 file:text-primary hover:file:bg-primary-light/30 file:cursor-pointer file:transition">
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4 flex items-center justify-end gap-4">
                    <x-buttonv2 href="{{ route('mahasiswa-index') }}" color="primary" class="w-full sm:w-auto">
                        kembali
                    </x-buttonv2>
                    <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                        Simpan Perubahan
                    </x-buttonv2>
                </div>
            </form>
        </div>
    </div>
@endsection
