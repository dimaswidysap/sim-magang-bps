@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-4xl mx-auto space-y-6">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Tulis Berita / Pengumuman</h1>
                    <p class="text-sm text-text-light mt-1">Buat dan publikasikan informasi terbaru untuk mahasiswa magang.
                    </p>
                </div>

                <x-main-button href="{{ route('berita-index') }}"
                    class="w-full sm:w-auto bg-surface text-text border border-border hover:bg-background text-xs px-4 py-2.5 rounded-lg transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </x-main-button>
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

                <form method="POST" action="{{ route('berita-store') }}" enctype="multipart/form-data" class="m-0" data-confirm="Apakah Anda yakin ingin mempublikasikan berita?">
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
                                    Anda dapat memilih lebih dari satu file (Maks. 10 MB per file).
                                </p>
                            </div>
                        </div>

                    </div>

                    <!-- Footer Form (Tombol Submit) -->
                    <div class="p-6 md:px-8 md:py-5 bg-background border-t border-border flex justify-end">
                        <x-main-button type="submit"
                            class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-xs px-6 py-2.5 rounded-lg text-white transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Publikasikan Berita</span>
                        </x-main-button>
                    </div>
                </form>
            </div>

        </section>
    </main>
@endsection
