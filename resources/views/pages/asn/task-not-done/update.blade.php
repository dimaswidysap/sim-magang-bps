@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-5xl mx-auto">

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="mb-6 bg-danger/10 border border-danger p-4 rounded-xl flex items-start gap-3">
                    <svg class="h-5 w-5 text-danger shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-danger">Terdapat kesalahan pada input Anda:</h3>
                        <ul class="list-disc list-inside text-sm text-danger mt-1 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="bg-surface border border-border rounded-2xl shadow-sm p-6 md:p-8">
                <form enctype="multipart/form-data" method="POST" action="{{ route('asn-update-tugas', $tugas->id) }}"
                    data-confirm="Apakah anda yakin ingin menerapkan perubahan?">
                    @csrf
                    @method('PUT')

                    <!-- Section: Informasi Tugas -->
                    <div class="mb-8">
                        <h2 class="flex items-center gap-2 text-primary font-bold text-lg mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Informasi Tugas
                        </h2>

                        <!-- Kotak Form Berwarna Abu-abu Lembut -->
                        <div class="bg-[#F8FAFC] border border-border rounded-xl p-5 sm:p-6 space-y-5">

                            <!-- Judul Tugas -->
                            <div>
                                <label class="block text-sm font-medium text-text-light mb-1.5">Judul Tugas</label>
                                <input type="text" name="judul" value="{{ old('judul', $tugas->judul) }}"
                                    class="w-full bg-surface border border-border rounded-lg px-4 py-2.5 text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                                    placeholder="Contoh: Membuat Desain UI Dashboard">
                            </div>

                            <!-- Batas Waktu -->
                            <div>
                                <label class="block text-sm font-medium text-text-light mb-1.5">Batas Waktu
                                    (Deadline)</label>
                                <input type="datetime-local" name="deadline"
                                    value="{{ old('deadline', date('Y-m-d\TH:i', strtotime($tugas->deadline))) }}"
                                    class="w-full md:w-1/2 bg-surface border border-border rounded-lg px-4 py-2.5 text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors cursor-pointer">
                            </div>

                            <!-- Deskripsi Lengkap -->
                            <div>
                                <label class="block text-sm font-medium text-text-light mb-1.5">Deskripsi Lengkap</label>
                                <textarea name="deskripsi" rows="4"
                                    class="w-full bg-surface border border-border rounded-lg px-4 py-3 text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-y"
                                    placeholder="Jelaskan detail pekerjaan, kriteria, atau instruksi tambahan di sini...">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                            </div>
                            {{-- update file --}}

                            <div class="font-montserrat">
                                <!-- Label -->
                                <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                    Tambah File Baru
                                    <span
                                        class="normal-case font-medium text-[11px] text-text-light/70 ml-1">(Opsional)</span>
                                </label>

                                <!-- Area Input File -->
                                <div
                                    class="relative border-2 border-dashed border-border rounded-xl p-4 md:p-5 bg-background hover:border-primary/50 transition-colors group">

                                    <!-- Input File (Custom Button Styling via Tailwind 'file:' modifier) -->
                                    <input type="file" name="file"
                                        class="block w-full text-sm text-text cursor-pointer focus:outline-none
            file:mr-4 file:py-2.5 file:px-5
            file:rounded-lg file:border-0
            file:text-xs file:font-semibold
            file:bg-primary file:text-white
            hover:file:bg-primary-dark file:transition-colors file:cursor-pointer">

                                    <!-- Keterangan Tambahan / Helper Text -->
                                    <p class="text-[11px] text-text-light mt-3 flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Ukuran maksimal file adalah <strong>10 MB</strong>.</span>
                                    </p>

                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- Section: Skill yang Dibutuhkan -->
                    <div class="mb-8">
                        <h2 class="flex items-center gap-2 text-primary font-bold text-lg mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Skill yang Dibutuhkan
                        </h2>

                        <!-- Kotak Container Checkbox -->
                        <div class="bg-[#F8FAFC] border border-border rounded-xl p-5 sm:p-6">
                            <p class="text-sm text-text-light mb-4">
                                Pilih satu atau lebih keahlian yang relevan dengan tugas ini.
                            </p>

                            @php
                                $selectedSkills = old('skills', $tugas->skills->pluck('id')->toArray());
                            @endphp

                            <!-- Grid/Flex untuk Checkbox berbentuk Pill -->
                            <div class="flex flex-wrap gap-3">
                                @foreach ($skills as $skill)
                                    <label for="skill-{{ $skill->id }}"
                                        class="inline-flex items-center gap-2.5 px-4 py-2 bg-surface border border-border rounded-full cursor-pointer hover:bg-background transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5 group">
                                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                            id="skill-{{ $skill->id }}"
                                            class="w-4 h-4 text-primary border-border rounded focus:ring-primary"
                                            {{ in_array($skill->id, $selectedSkills) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-text group-has-[:checked]:text-primary-dark">
                                            {{ $skill->nama_skill }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <hr class="border-border mb-6">

                    <!-- Footer Buttons -->
                    <div class="flex justify-end items-center gap-3">
                        <x-main-button href="{{ route('task-not-done') }}"
                            class="bg-background text-text border border-border hover:bg-surface text-xs px-4 py-2 rounded-lg transition-colors shadow-sm inline-flex items-center gap-2">
                            <span>Batal</span>
                        </x-main-button>

                        <x-main-button type="submit"
                            class="bg-primary hover:bg-primary-dark text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Perubahan</span>
                        </x-main-button>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
