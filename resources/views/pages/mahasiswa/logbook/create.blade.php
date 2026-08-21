@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto font-montserrat">
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
            <form data-confirm="Apakah anda sudah yakin?" action="{{ route('magang-logbook-store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
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
                        File Lampiran <span class="normal-case text-text-light/80 font-normal">(Opsional: PNG, JPG, JPEG -
                            Maks 2MB)</span>
                    </label>
                    <input type="file" id="file_lampiran" name="file_lampiran" accept="image/png, image/jpeg, image/jpg"
                        class="w-full text-sm text-text-light border border-border rounded-xl cursor-pointer bg-surface file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light/20 file:text-primary hover:file:bg-primary-light/30 file:cursor-pointer file:transition">
                    @error('file_lampiran')
                        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Aksi -->

                <div class="w-full flex justify-end gap-4 py-5">
                    <x-buttonv2 href="{{ route('mahasiswa-index') }}" color="primary"
                    class="w-full sm:w-auto">

                    Kembali
                </x-buttonv2>

                <x-buttonv2 type="submit"  color="accent-dark"
                    class="w-full sm:w-auto">
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
@endsection
