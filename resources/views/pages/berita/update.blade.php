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

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($berita->attachments as $lampiran)
                                        <!-- Card Lampiran dengan Checkbox Hapus -->
                                        <label
                                            class="relative flex items-start gap-3 p-3 border border-border rounded-xl bg-background cursor-pointer overflow-hidden transition-colors hover:border-danger/50 select-none">

                                            <!-- Checkbox (menggunakan class peer untuk mengatur style elemen disekitarnya) -->
                                            <input type="checkbox" name="hapus_lampiran[]" value="{{ $lampiran->id }}"
                                                class="peer mt-0.5 w-4 h-4 text-danger border-border rounded focus:ring-danger accent-danger relative z-10 cursor-pointer">

                                            <!-- Highlight merah saat dicentang -->
                                            <div
                                                class="absolute inset-0 bg-danger/5 opacity-0 peer-checked:opacity-100 transition-opacity border-danger">
                                            </div>
                                            <div
                                                class="absolute inset-0 border border-transparent peer-checked:border-danger rounded-xl transition-colors pointer-events-none">
                                            </div>

                                            <!-- Info File -->
                                            <div
                                                class="flex-1 overflow-hidden relative z-10 flex flex-col justify-center min-h-[1.5rem]">
                                                <p
                                                    class="text-sm font-semibold text-text truncate peer-checked:text-danger peer-checked:line-through transition-all">
                                                    {{ $lampiran->file_name }}
                                                </p>
                                                <p
                                                    class="text-[10px] text-text-light peer-checked:text-danger mt-0.5 transition-colors">
                                                    Centang untuk menghapus file ini
                                                </p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Input File Lampiran Baru -->
                        <div>
                            <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                Tambah Lampiran Baru <span class="normal-case font-normal text-[11px]">(Opsional)</span>
                            </label>
                            <div
                                class="border-2 border-dashed border-border rounded-xl p-4 bg-[#F8FAFC] hover:border-primary transition-colors">
                                <input type="file" name="lampiran[]" multiple
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
@endsection
