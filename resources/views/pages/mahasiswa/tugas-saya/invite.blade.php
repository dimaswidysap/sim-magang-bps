@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-2xl mx-auto">

            <!-- Header Halaman -->
            <div class="mb-6 pb-4 border-b border-border">
                <h1 class="text-2xl font-bold text-text leading-snug">Undang Teman ke Tugas</h1>
                <p class="text-sm text-text-light mt-1 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Target Tugas: <span class="font-semibold text-text">{{ $tugas->judul }}</span>
                </p>
            </div>

            <!-- Alert Error (Menggabungkan session error & validation errors) -->
            @if (session('error') || $errors->any())
                <div class="mb-6 bg-danger/10 border border-danger p-4 rounded-xl flex items-start gap-3 shadow-sm">
                    <svg class="h-5 w-5 text-danger shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-danger">Terdapat kesalahan:</h3>
                        <ul class="list-disc list-inside text-sm text-danger mt-1 space-y-1">
                            @if (session('error'))
                                <li>{{ session('error') }}</li>
                            @endif
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Card Form -->
            <div class="bg-surface border border-border rounded-2xl shadow-sm p-6 md:p-8">
                <form method="POST" action="{{ route('mahasiswa-tugas-undang', $tugas->id) }}">
                    @csrf

                    <!-- Pilihan Mahasiswa -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-text-light mb-2">
                            Pilih Mahasiswa <span class="text-danger">*</span>
                        </label>

                        <div class="relative">
                            <!-- Icon User -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-text-light" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>

                            <select name="mahasiswa_profile_id"
                                class="w-full pl-10 pr-4 py-2.5 bg-[#F8FAFC] border border-border rounded-lg text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors cursor-pointer appearance-none">
                                <option value="">-- Cari & Pilih Rekan Mahasiswa --</option>
                                @foreach ($daftarMahasiswa as $mhs)
                                    <option value="{{ $mhs->id }}"
                                        {{ old('mahasiswa_profile_id') == $mhs->id ? 'selected' : '' }}>
                                        {{ $mhs->user->name }} - (NIM: {{ $mhs->nim }})
                                    </option>
                                @endforeach
                            </select>

                            <!-- Dropdown Icon (Chevron) -->
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-text-light" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-[11px] text-text-light mt-2">Pilih mahasiswa dari daftar rekan magang yang belum
                            memiliki tugas atau belum diundang.</p>
                    </div>

                    <hr class="border-border mb-6">

                    <!-- Footer Buttons -->
                    <div class="flex justify-end items-center gap-3">

                        <!-- Tombol Batal -->


                        <x-buttonv2 href="{{ route('tugas-saya') }}" color="primary" class="w-full sm:w-auto">
                            Batal
                        </x-buttonv2>

                        <!-- Tombol Kirim Undangan -->


                        <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Undangan
                        </x-buttonv2>
                    </div>

                </form>
            </div>
        </section>
    </main>
@endsection
