@extends('layouts.app')

@section('content')

    <main class=" relative w-full flex bg-background">
        {{-- container-sidebar-admin --}}

        @include('components.asn.asn-sidebar')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 pl-60">

            {{-- header --}}
            @include('components.asn.header-asn')

            <section class="w-full p-2">
                <section class="container-dalam">

                    {{-- {{ $daftarMahasiswa }} --}}
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

                    <form method="POST" action="{{ route('asn-store-tugas') }}" class="space-y-8"
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
                                    <label class="block text-sm font-medium text-text-light mb-1.5">Judul Tugas</label>
                                    <input type="text" name="judul" value="{{ old('judul') }}"
                                        placeholder="Contoh: Membuat Desain UI Dashboard"
                                        class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                                </div>

                                <!-- Deadline -->
                                <div class="md:w-1/2">
                                    <label class="block text-sm font-medium text-text-light mb-1.5">Batas Waktu
                                        (Deadline)</label>
                                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}"
                                        class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                                </div>

                                <!-- Deskripsi (Textarea) -->
                                <div>
                                    <label class="block text-sm font-medium text-text-light mb-1.5">Deskripsi
                                        Lengkap</label>
                                    <textarea name="deskripsi" rows="5"
                                        placeholder="Jelaskan detail pekerjaan, kriteria, atau instruksi tambahan di sini..."
                                        class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors resize-y">{{ old('deskripsi') }}</textarea>
                                </div>
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
                                <p class="text-sm text-text-light mb-4">Pilih satu atau lebih keahlian yang relevan dengan
                                    tugas ini.</p>

                                <div class="flex flex-wrap gap-3">
                                    @foreach ($skillList as $skill)
                                        <label for="skill-{{ $skill->id }}"
                                            class="flex items-center gap-2 px-4 py-2 bg-surface border border-border rounded-full cursor-pointer hover:border-primary transition-colors">
                                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                                id="skill-{{ $skill->id }}"
                                                {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }}
                                                class="w-4 h-4 text-primary bg-background border-border rounded focus:ring-primary focus:ring-2 cursor-pointer accent-primary">
                                            <span class="text-sm font-medium text-text">{{ $skill->nama_skill }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <hr class="border-border mt-8">

                        <!-- Footer Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4">

                            <x-main-button
                                class="bg-primary hover:bg-primary-dark  text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                                type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Tambahkan Tugas</span>
                            </x-main-button>
                        </div>

                    </form>
                </section>
            </section>
        </section>
    </main>

@endsection
