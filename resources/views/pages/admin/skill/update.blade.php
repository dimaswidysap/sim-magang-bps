@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-2xl mx-auto bg-surface rounded-2xl shadow-sm border border-border p-6 md:p-8">

            <!-- Header Halaman -->
            <div class="mb-8 pb-4 border-b border-border flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-bold text-text">Edit Skill</h1>
                    <p class="text-sm text-text-light mt-1">Perbarui penamaan referensi keahlian pada sistem.</p>
                </div>
                <!-- Badge ID -->
                <div>
                    <span
                        class="px-4 py-1.5 bg-background border border-border text-text-light text-sm font-semibold rounded-full">
                        ID: {{ $skill->id }}
                    </span>
                </div>
            </div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-surface border border-danger rounded-lg shadow-sm">
                    <div class="flex items-center gap-2 text-danger font-semibold mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20"
                            fill="currentColor">
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

            <form method="POST" action="{{ route('admin-skill-update', $skill->id) }}"
                data-confirm="Apakah anda yakin ingin menerapkah perubahan?">
                @csrf

                <!-- Form Input -->
                <div class="bg-background p-5 rounded-xl border border-border mb-8">
                    <label class="block text-sm font-medium text-text-light mb-1.5 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Nama Skill / Keahlian
                    </label>
                    <input type="text" name="nama_skill" value="{{ old('nama_skill', $skill->nama_skill) }}"
                        class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                </div>

                <hr class="border-border mb-6">

                <!-- Footer Buttons -->
                <div class="flex justify-end items-center gap-2">

                    <!-- Tombol Batal (Menggunakan Layout Button Anda, disesuaikan warnanya) -->


                    <x-buttonv2 href="{{ route('admin-skill') }}" color="primary" class="w-full sm:w-auto">

                        Batal
                    </x-buttonv2>

                    <!-- Tombol Simpan (Persis layout button Anda) -->


                    <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" stroke-width="3" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Simpan Perubahan
                    </x-buttonv2>

                </div>
            </form>
        </section>
    </main>
@endsection
