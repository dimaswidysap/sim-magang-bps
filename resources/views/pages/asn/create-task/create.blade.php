@extends('layouts.app')

@section('content')

    <main class="relative w-full flex bg-background">

        @include('components.alert')

        {{-- container-sidebar-admin --}}
        @include('components.asn.asn-sidebar')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 md:pl-60 container-content-mobile">

            {{-- header --}}
            @include('components.header-mobile')
            @include('components.asn.header-asn')

            <section class="w-full p-2">
                <section class="container-dalam">

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-surface border border-danger rounded-lg shadow-sm">

                            <div class="flex items-center gap-2 text-danger font-semibold mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>

                                Terdapat kesalahan pada input Anda:
                            </div>

                            <ul class="list-disc list-inside text-sm text-danger ml-2 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>
                    @endif

                    {{-- FORM UTAMA --}}
                    <form method="POST" action="{{ route('asn-store-tugas') }}" id="form-tugas-asn" class="space-y-8"
                        enctype="multipart/form-data">

                        @csrf

                        <!-- SECTION 1: Detail Tugas -->
                        <div>

                            <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>

                                Tambah Tugas
                            </h2>

                            <div class="grid grid-cols-1 gap-6 bg-background p-5 rounded-xl border border-border">

                                <!-- Judul Tugas -->
                                <div>
                                    <label class="block text-sm font-medium text-text-light mb-1.5">
                                        Judul Tugas
                                    </label>

                                    <input type="text" name="judul" value="{{ old('judul') }}"
                                        placeholder="Contoh: Membuat Desain UI Dashboard"
                                        class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                                </div>

                                <!-- Deadline -->
                                <div class="md:w-1/2">
                                    <label class="block text-sm font-medium text-text-light mb-1.5">
                                        Batas Waktu (Deadline)
                                    </label>

                                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}"
                                        class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                                </div>

                                <!-- Deskripsi -->
                                <div>
                                    <label class="block text-sm font-medium text-text-light mb-1.5">
                                        Deskripsi Lengkap
                                    </label>

                                    <textarea name="deskripsi" rows="5"
                                        placeholder="Jelaskan detail pekerjaan, kriteria, atau instruksi tambahan di sini..."
                                        class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors resize-y">{{ old('deskripsi') }}</textarea>
                                </div>

                                <!-- Lampiran -->
                                <div>

                                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>

                                        Lampiran (Opsional)

                                    </h2>

                                    <div class="bg-background p-5 rounded-xl border border-border">

                                        <label class="block text-sm font-medium text-text-light mb-1.5">
                                            File Referensi/Template (maks. 10 MB)
                                        </label>

                                        <input type="file" name="file"
                                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- SECTION 2: Persyaratan Skill -->
                        <div>

                            <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>

                                Skill yang Dibutuhkan

                            </h2>

                            <div class="bg-background p-5 rounded-xl border border-border">

                                <p class="text-sm text-text-light mb-4">
                                    Pilih satu atau lebih keahlian yang relevan dengan tugas ini.
                                </p>

                                <div class="flex flex-wrap gap-3">

                                    @foreach ($skillList as $skill)
                                        <label for="skill-{{ $skill->id }}"
                                            class="flex items-center gap-2 px-4 py-2 bg-surface border border-border rounded-full cursor-pointer hover:border-primary transition-colors">

                                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                                id="skill-{{ $skill->id }}"
                                                {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }}
                                                class="w-4 h-4 text-primary bg-background border-border rounded focus:ring-primary focus:ring-2 cursor-pointer accent-primary">

                                            <span class="text-sm font-medium text-text">
                                                {{ $skill->nama_skill }}
                                            </span>

                                        </label>
                                    @endforeach

                                </div>

                            </div>

                        </div>


                        <!-- SECTION 3: Penugasan Langsung -->
                        <div>

                            <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z" />

                                </svg>

                                Penugasan Langsung

                            </h2>

                            <div class="bg-background p-5 rounded-xl border border-border">

                                <label class="flex items-center gap-3 mb-2 cursor-pointer select-none">

                                    <input type="checkbox" name="penugasan_langsung" value="1" id="penugasan_langsung"
                                        onchange="toggleMahasiswaContainer(this)"
                                        {{ old('penugasan_langsung') ? 'checked' : '' }}
                                        class="w-5 h-5 text-accent-dark bg-surface border-border rounded focus:ring-accent-dark cursor-pointer accent-accent-dark">

                                    <span class="text-base font-semibold text-text">
                                        Tugaskan langsung ke mahasiswa tertentu
                                    </span>

                                </label>

                                <p class="text-sm text-text-light mb-4 ml-8">
                                    Aktifkan ini jika tugas ditujukan khusus untuk anak magang terpilih.
                                </p>


                                {{-- Trigger Area --}}
                                <div id="container-pilih-mahasiswa"
                                    class="{{ old('penugasan_langsung') ? '' : 'hidden' }} relative w-full pl-8">

                                    <button type="button" onclick="openModalMahasiswa()"
                                        class="flex items-center justify-between w-full p-4 bg-surface border border-border rounded-xl text-left hover:border-primary focus:ring-2 focus:ring-primary/20 transition-all group shadow-sm">

                                        <div class="flex items-center gap-4">

                                            <div
                                                class="p-3 bg-primary/10 text-primary rounded-lg group-hover:bg-primary group-hover:text-surface transition-colors">

                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                    </path>

                                                </svg>

                                            </div>

                                            <div>

                                                <span class="block font-bold text-text text-base">
                                                    Kelola Mahasiswa
                                                </span>

                                                <span id="selected-count-text"
                                                    class="block text-sm text-text-light mt-0.5 font-medium">
                                                    0 mahasiswa dipilih
                                                </span>

                                            </div>

                                        </div>

                                        <span
                                            class="px-4 py-2 bg-accent-dark text-surface text-xs font-bold rounded-lg tracking-wide uppercase">
                                            Pilih
                                        </span>

                                    </button>

                                </div>

                            </div>

                        </div>


                        <hr class="border-border mt-8">


                        <!-- Footer Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4 pb-10 md:px-4">

                            <x-buttonv2 type="submit" color="accent-dark">

                                <x-slot name="icon">

                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="5" stroke-linecap="round"
                                        stroke-linejoin="round" class="w-5 h-5">

                                        <path d="M12 5v14"></path>
                                        <path d="M5 12h14"></path>

                                    </svg>

                                </x-slot>

                                Tambahkan Tugas

                            </x-buttonv2>

                        </div>

                    </form>

                </section>
            </section>

        </section>


        {{-- MODAL --}}
        @include('pages.asn.create-task.components.modal-pilih-mahasiswa')

    </main>


    <script>
        /**
         * Menampilkan / menyembunyikan container pemilihan mahasiswa
         */
        function toggleMahasiswaContainer(checkbox) {

            const container = document.getElementById('container-pilih-mahasiswa');

            if (!container) {
                return;
            }

            container.classList.toggle('hidden', !checkbox.checked);

        }


        /**
         * Membuka modal mahasiswa
         */
        function openModalMahasiswa() {

            const modal = document.getElementById('modal-pilih-mahasiswa');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');

            document.body.style.overflow = 'hidden';

        }


        /**
         * Menutup modal mahasiswa
         */
        function closeModalMahasiswa() {

            const modal = document.getElementById('modal-pilih-mahasiswa');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');

            document.body.style.overflow = '';

        }


        /**
         * Menghitung jumlah mahasiswa yang dipilih
         */
        function updateSelectedCount() {

            const checkboxes = document.querySelectorAll(
                '.mhs-checkbox:checked'
            );

            const countText = document.getElementById(
                'selected-count-text'
            );

            if (!countText) {
                return;
            }

            const total = checkboxes.length;

            if (total > 0) {

                countText.innerHTML =
                    `<span class="text-primary font-bold">${total}</span> mahasiswa dipilih`;

            } else {

                countText.textContent =
                    'Belum ada mahasiswa dipilih';

            }

        }


        /**
         * Saat halaman selesai dimuat
         */
        document.addEventListener('DOMContentLoaded', function() {

            // Update jumlah mahasiswa
            updateSelectedCount();


            // Tutup modal jika klik area luar modal
            const modal = document.getElementById(
                'modal-pilih-mahasiswa'
            );

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
