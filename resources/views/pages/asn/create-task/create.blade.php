@extends('layouts.app')

@section('content')

    <main class="relative w-full flex bg-background min-h-screen">

        @include('components.alert')

        {{-- Container Sidebar ASN --}}
        @include('components.asn.asn-sidebar')

        {{-- Container Content --}}
        <section class="flex flex-col flex-1 md:pl-60 container-content-mobile">

            {{-- Header Mobile & Desktop --}}
            @include('components.header-mobile')
            @include('components.asn.header-asn')

            <div class="w-full px-2 pb-10 pt-8 max-w-5xl mx-auto space-y-6">

                <!-- Page Banner / Header Title -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                    <div>
                        <h1 class="text-2xl font-bold text-text leading-snug">Buat Tugas Baru</h1>
                        <p class="text-sm text-text-light mt-1">
                            Isi formulir di bawah ini untuk mendistribusikan tugas kepada mahasiswa magang.
                        </p>
                    </div>
                </div>

                <!-- Alert Error Validation -->
                @if ($errors->any())
                    <div class="p-4 bg-surface border-l-4 border-danger rounded-xl shadow-sm space-y-2">
                        <div class="flex items-center gap-2 text-danger font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Terdapat kesalahan pada input Anda:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm text-danger/90 pl-7 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM UTAMA --}}
                <form method="POST" action="{{ route('asn-store-tugas') }}" id="form-tugas-asn" class="space-y-6"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- SECTION 1: Detail Utama Tugas -->
                    <div class="bg-surface border border-border rounded-2xl p-5 md:p-7 shadow-sm space-y-6">
                        <div class="flex items-center gap-3 border-b border-border/60 pb-4">
                            <div class="p-2.5 bg-primary/10 text-primary rounded-xl shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-text">Detail & Informasi Tugas</h2>
                                <p class="text-xs text-text-light">Tentukan judul, batas waktu, serta instruksi pelaksanaan
                                    tugas.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul Tugas -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-text mb-2">
                                    Judul Tugas <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="judul" value="{{ old('judul') }}"
                                    placeholder="Contoh: Pembuatan Desain UI/UX Dashboard Analytics"
                                    class="w-full px-4 py-3 bg-background border border-border rounded-xl text-text placeholder:text-text-light/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm">
                            </div>

                            <!-- Deadline -->
                            <div>
                                <label class="block text-sm font-semibold text-text mb-2">
                                    Batas Waktu (Deadline) <span class="text-danger">*</span>
                                </label>
                                <div class="relative">
                                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}"
                                        class="w-full px-4 py-3 bg-background border border-border rounded-xl text-text focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm cursor-pointer">
                                </div>
                            </div>

                            <!-- Upload Lampiran -->
                            <div>
                                <label class="block text-sm font-semibold text-text mb-2">
                                    File Referensi/Template <span class="text-xs font-normal text-text-light">(Maks.
                                        10MB)</span>
                                </label>
                                <input type="file" name="file"
                                    class="w-full text-sm text-text file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:transition-colors bg-background border border-border rounded-xl p-1 cursor-pointer">
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-text mb-2">
                                    Deskripsi & Instruksi Lengkap <span class="text-danger">*</span>
                                </label>
                                <textarea name="deskripsi" rows="5"
                                    placeholder="Tuliskan detail pekerjaan, kriteria hasil akhir, tautan pendukung, atau instruksi khusus secara menyeluruh..."
                                    class="w-full px-4 py-3 bg-background border border-border rounded-xl text-text placeholder:text-text-light/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm leading-relaxed resize-y">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Persyaratan Skill -->
                    <div class="bg-surface border border-border rounded-2xl p-5 md:p-7 shadow-sm space-y-4">
                        <div class="flex items-center gap-3 border-b border-border/60 pb-4">
                            <div class="p-2.5 bg-primary/10 text-primary rounded-xl shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-text">Keahlian / Skill Terkait</h2>
                                <p class="text-xs text-text-light">Pilih satu atau lebih keahlian yang relevan dengan
                                    pengerjaan tugas ini.</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2.5 pt-2">
                            @foreach ($skillList as $skill)
                                <label for="skill-{{ $skill->id }}" class="relative group cursor-pointer select-none">
                                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                        id="skill-{{ $skill->id }}"
                                        {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }} class="peer sr-only">

                                    <div
                                        class="flex items-center gap-2 px-4 py-2 bg-background border border-border rounded-xl text-xs font-semibold text-text-light transition-all peer-checked:bg-primary/10 peer-checked:border-primary peer-checked:text-primary hover:border-primary/50">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 hidden peer-checked:block text-primary" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>{{ $skill->nama_skill }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- SECTION 3: Penugasan Langsung -->
                    <div class="bg-surface border border-border rounded-2xl p-5 md:p-7 shadow-sm space-y-5">
                        <div class="flex items-center gap-3 border-b border-border/60 pb-4">
                            <div class="p-2.5 bg-primary/10 text-primary rounded-xl shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-text">Target Penugasan</h2>
                                <p class="text-xs text-text-light">Tentukan apakah tugas ini ditujukan secara spesifik
                                    untuk mahasiswa tertentu.</p>
                            </div>
                        </div>

                        <!-- Modern Toggle Switch -->
                        <div class="p-4 bg-background border border-border/80 rounded-xl">
                            <label
                                class="flex items-start md:items-center justify-between gap-4 cursor-pointer select-none">
                                <div class="space-y-0.5">
                                    <span class="text-sm font-bold text-text block">Penugasan Spesifik</span>
                                    <span class="text-xs text-text-light block">Aktifkan untuk memilih mahasiswa khusus
                                        yang wajib menyelesaikan tugas ini.</span>
                                </div>

                                <div class="relative shrink-0 mt-1 md:mt-0">
                                    <input type="checkbox" name="penugasan_langsung" value="1"
                                        id="penugasan_langsung" onchange="toggleMahasiswaContainer(this)"
                                        {{ old('penugasan_langsung') ? 'checked' : '' }} class="sr-only peer">

                                    <div
                                        class="block w-12 h-7 bg-border rounded-full peer-checked:bg-accent-dark transition-colors duration-300">
                                    </div>
                                    <div
                                        class="absolute left-1 top-1 bg-surface w-5 h-5 rounded-full transition-transform duration-300 peer-checked:translate-x-5 shadow-sm border border-border">
                                    </div>
                                </div>
                            </label>

                            <!-- Trigger Area Modal Mahasiswa -->
                            <div id="container-pilih-mahasiswa"
                                class="{{ old('penugasan_langsung') ? '' : 'hidden' }} pt-4 mt-4 border-t border-border/60">
                                <button type="button" onclick="openModalMahasiswa()"
                                    class="flex items-center justify-between w-full p-4 bg-surface border border-border hover:border-primary rounded-xl text-left focus:ring-2 focus:ring-primary/20 transition-all group shadow-sm">
                                    <div class="flex items-center gap-3.5">
                                        <div
                                            class="p-2.5 bg-primary/10 text-primary rounded-lg group-hover:bg-primary group-hover:text-surface transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-text text-sm">Kelola Penerima Tugas</span>
                                            <span id="selected-count-text"
                                                class="block text-xs text-text-light mt-0.5 font-medium">0 mahasiswa
                                                dipilih</span>
                                        </div>
                                    </div>
                                    <span
                                        class="px-3.5 py-1.5 bg-accent-dark text-surface text-xs font-bold rounded-lg tracking-wide uppercase shadow-sm">
                                        Pilih
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Footer -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <x-buttonv2 type="submit" color="accent-dark" class="!px-6 !py-3">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-5 h-5">
                                    <path d="M12 5v14"></path>
                                    <path d="M5 12h14"></path>
                                </svg>
                            </x-slot>
                            Publikasikan Tugas
                        </x-buttonv2>
                    </div>

                </form>

            </div>

        </section>

        {{-- MODAL PEMILIHAN MAHASISWA --}}
        @include('pages.asn.create-task.components.modal-pilih-mahasiswa')

    </main>

    <script>
        /**
         * Menampilkan / menyembunyikan container pemilihan mahasiswa
         */
        function toggleMahasiswaContainer(checkbox) {
            const container = document.getElementById('container-pilih-mahasiswa');
            if (!container) return;
            container.classList.toggle('hidden', !checkbox.checked);
        }

        /**
         * Membuka modal mahasiswa
         */
        function openModalMahasiswa() {
            const modal = document.getElementById('modal-pilih-mahasiswa');
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        /**
         * Menutup modal mahasiswa
         */
        function closeModalMahasiswa() {
            const modal = document.getElementById('modal-pilih-mahasiswa');
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        /**
         * Menhitung jumlah mahasiswa yang dipilih
         */
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.mhs-checkbox:checked');
            const countText = document.getElementById('selected-count-text');
            if (!countText) return;

            const total = checkboxes.length;
            if (total > 0) {
                countText.innerHTML = `<span class="text-primary font-bold">${total}</span> mahasiswa dipilih`;
            } else {
                countText.textContent = 'Belum ada mahasiswa dipilih';
            }
        }

        /**
         * Event DOM Content Loaded
         */
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedCount();

            const modal = document.getElementById('modal-pilih-mahasiswa');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModalMahasiswa();
                    }
                });
            }
        });
    </script>

    <script src="{{ asset('js/asn/tugas-alert.js') }}"></script>

@endsection
