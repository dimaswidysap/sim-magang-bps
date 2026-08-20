@extends('layouts.app')
@vite(['resources/js/validasi-number.js'])
@section('content')

    {{-- {{ $dataUser }} --}}

    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-5xl mx-auto bg-surface rounded-2xl shadow-sm border border-border p-6 md:p-10">

            <!-- Header Halaman -->
            <div class="mb-8 pb-4 border-b border-border flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-bold text-text">Edit Data Mahasiswa</h1>
                    <p class="text-sm text-text-light mt-1">Perbarui informasi akun, data diri, atau status magang mahasiswa.
                    </p>
                </div>
                <!-- Badge Status Saat Ini (Opsional untuk visualisasi cepat) -->
                <div>
                    <span
                        class="px-4 py-1.5 bg-background border border-border text-text-light text-sm font-semibold rounded-full">
                        ID: {{ $dataUser->id }}
                    </span>
                </div>
            </div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-surface border border-danger rounded-lg">
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

            <form method="POST" action="{{ route('admin-mahasiswa-update', $dataUser->id) }}" class="space-y-8"
                data-confirm="Apakah anda yakin ingin melakukan perubahan?">
                @csrf
                @method('PUT')

                <!-- SECTION 1: Data Akun -->
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Data Akun
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-text-light mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $dataUser->name) }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email', $dataUser->email) }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Password Baru</label>
                            <input type="password" name="password" placeholder="••••••••"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                            <p class="text-xs text-text-light mt-1.5 italic">* Kosongkan jika tidak ingin mengubah password.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-light mb-2">Status Akun</label>

                            <!-- Nilai default 0 (false/non-aktif) jika toggle tidak dicentang -->
                            <input type="hidden" name="is_active" value="0">

                            <!-- Toggle Button -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <!--
                            Menggunakan old('is_active') untuk repopulate data jika validasi form gagal,
                            dan fallback ke $dataUser->is_active jika baru pertama kali load halaman.
                        -->
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                    {{ old('is_active', $dataUser->is_active) ? 'checked' : '' }}>

                                <!-- Desain visual Toggle -->
                                <div
                                    class="w-11 h-6 bg-gray-300 rounded-full peer
                    peer-focus:ring-2 peer-focus:ring-primary/50
                    peer-checked:after:translate-x-full peer-checked:after:border-white
                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                    after:bg-white after:border-gray-300 after:border after:rounded-full
                    after:h-5 after:w-5 after:transition-all
                    peer-checked:bg-primary">
                                </div>

                                <!-- Label Dinamis (Aktif/Nonaktif) menyesuaikan isi dari database -->
                                <span class="ml-3 text-sm font-medium text-text">
                                    {{ $dataUser->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </label>

                            <p class="text-xs text-text-light mt-1.5 italic">* Geser toggle untuk mengaktifkan atau
                                menonaktifkan akun.</p>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Data Mahasiswa -->
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                        Data Mahasiswa
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">NIM / NIS</label>
                            <input type="text" name="nim"
                                value="{{ old('nim', $dataUser->mahasiswaProfile->nim ?? '') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Instansi Asal
                                (Kampus/Sekolah)</label>
                            <input type="text" name="instansi_asal"
                                value="{{ old('instansi_asal', $dataUser->mahasiswaProfile->instansi_asal ?? '') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Jenjang</label>
                            @php $jenjangLama = old('jenjang', $dataUser->mahasiswaProfile->jenjang ?? ''); @endphp
                            <select name="jenjang"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                                <option value="">-- Pilih Jenjang --</option>
                                <option value="SMA/SMK" {{ $jenjangLama == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                <option value="D3" {{ $jenjangLama == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="D4" {{ $jenjangLama == 'D4' ? 'selected' : '' }}>D4</option>
                                <option value="S1" {{ $jenjangLama == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ $jenjangLama == 'S2' ? 'selected' : '' }}>S2</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Jurusan</label>
                            <input type="text" name="jurusan"
                                value="{{ old('jurusan', $dataUser->mahasiswaProfile->jurusan ?? '') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">NO HP</label>
                            <input type="number" name="phone" value="{{ old('phone', $dataUser->phone ?? '') }}"
                                class="hanya-angka appearance-none w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Periode, Waktu & Status Magang -->
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Periode, Waktu & Status
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Pilih Periode</label>
                            @php $periodeLama = old('periode_magang_id', $dataUser->mahasiswaProfile->periode_magang_id ?? ''); @endphp
                            <select name="periode_magang_id"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                                <option value="">-- Pilih Periode --</option>
                                @foreach ($periodeList as $periode)
                                    <option value="{{ $periode->id }}"
                                        {{ $periodeLama == $periode->id ? 'selected' : '' }}>
                                        {{ $periode->nama_periode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Status Magang</label>
                            @php $statusLama = old('status', $dataUser->mahasiswaProfile->status ?? 'pending'); @endphp
                            <select name="status"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer font-medium">
                                <option value="pending" {{ $statusLama == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="aktif" {{ $statusLama == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ $statusLama == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $statusLama == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai"
                                value="{{ old('tanggal_mulai', optional($dataUser->mahasiswaProfile->tanggal_mulai ?? null)->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai"
                                value="{{ old('tanggal_selesai', optional($dataUser->mahasiswaProfile->tanggal_selesai ?? null)->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: Skill / Label -->
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Skill / Label
                    </h2>
                    <div class="flex flex-wrap gap-3">
                        @php $skillLama = old('skills', $selectedSkillIds); @endphp
                        @foreach ($skillList as $skill)
                            <label for="skill-{{ $skill->id }}"
                                class="flex items-center gap-2 px-4 py-2 bg-background border border-border rounded-full cursor-pointer hover:border-primary transition-colors">
                                <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                    id="skill-{{ $skill->id }}"
                                    {{ in_array($skill->id, $skillLama) ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary bg-surface border-border rounded focus:ring-primary focus:ring-2 cursor-pointer accent-primary">
                                <span class="text-sm font-medium text-text">{{ $skill->nama_skill }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <hr class="border-border mt-8">

                <!-- Footer Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4">




                    <x-buttonv2 href="{{ route('admin-mahasiswa') }}" color="accent" class="w-full sm:w-auto">
                        Batal
                    </x-buttonv2>

                    <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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
