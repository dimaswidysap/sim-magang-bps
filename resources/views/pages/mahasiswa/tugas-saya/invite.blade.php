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

            <!-- Alert Error -->
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

            <!-- Card Form Utama -->
            <div class="bg-surface border border-border rounded-2xl shadow-sm p-6 md:p-8">
                <form method="POST" action="{{ route('mahasiswa-tugas-undang', $tugas->id) }}">
                    @csrf

                    <!-- Section Pemilihan Mahasiswa -->
                    <div class="mb-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                            <div>
                                <label class="block text-sm font-bold text-text">
                                    Pilih Mahasiswa Magang <span class="text-danger">*</span>
                                </label>
                                <p class="text-xs text-text-light mt-0.5">
                                    Anda dapat memilih lebih dari 1 mahasiswa sekaligus.
                                </p>
                            </div>

                            <!-- Input Pencarian -->
                            <div class="relative w-full sm:w-56">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-text-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input type="text" id="searchMahasiswa" placeholder="Cari nama atau NIM..."
                                    class="w-full pl-9 pr-3 py-1.5 text-xs bg-background border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                            </div>
                        </div>

                        @if ($daftarMahasiswa->isNotEmpty())
                            <!-- Indikator Jumlah Terpilih -->
                            <div class="flex justify-end mb-3">
                                <span class="text-xs font-semibold text-text-light bg-background border border-border px-3 py-1.5 rounded-lg" id="selectedCountText">
                                    0 mahasiswa dipilih
                                </span>
                            </div>
                        @endif

                        <!-- Grid Card Mahasiswa -->
                        @if ($daftarMahasiswa->isEmpty())
                            <div class="py-8 bg-background border border-border rounded-xl text-center">
                                <p class="text-sm text-text-light font-medium">Tidak ada mahasiswa yang tersedia untuk diundang.</p>
                            </div>
                        @else
                            <div id="mahasiswaGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-2 max-h-96 overflow-y-auto pr-1">
                                @foreach ($daftarMahasiswa as $mhs)
                                    @php
                                        $isSelected = is_array(old('mahasiswa_profile_ids')) && in_array($mhs->id, old('mahasiswa_profile_ids'));
                                    @endphp
                                    <label class="mahasiswa-card relative block cursor-pointer group" data-search="{{ strtolower($mhs->user->name . ' ' . $mhs->nim) }}">
                                        <!-- Checkbox (Array name[]) -->
                                        <input type="checkbox" name="mahasiswa_profile_ids[]" value="{{ $mhs->id }}"
                                            class="mhs-checkbox peer hidden"
                                            {{ $isSelected ? 'checked' : '' }}>

                                        <!-- Card Layout -->
                                        <div class="flex items-center gap-3 p-3.5 bg-background border border-border rounded-xl transition-all peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:ring-1 peer-checked:ring-primary group-hover:border-primary/40">

                                            <!-- Avatar Inisial -->
                                            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary font-bold text-sm flex items-center justify-center shrink-0 border border-primary/20">
                                                {{ strtoupper(substr($mhs->user->name, 0, 2)) }}
                                            </div>

                                            <!-- Informasi Mahasiswa -->
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-text truncate group-hover:text-primary transition-colors">
                                                    {{ $mhs->user->name }}
                                                </p>
                                                <p class="text-xs text-text-light font-medium">
                                                    NIM: {{ $mhs->nim }}
                                                </p>
                                            </div>



                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Pesan jika hasil pencarian kosong -->
                            <div id="noMatchMessage" class="hidden py-8 bg-background border border-border rounded-xl text-center">
                                <p class="text-sm text-text-light font-medium">Mahasiswa tidak ditemukan.</p>
                            </div>
                        @endif
                    </div>

                    <hr class="border-border mb-6">

                    <!-- Footer Buttons -->
                    <div class="flex justify-end items-center gap-3">
                        <x-buttonv2 href="{{ route('tugas-saya') }}" color="primary" class="w-full sm:w-auto">
                            Batal
                        </x-buttonv2>

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

    <!-- Script Interaktif (Search Bar & Counter) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchMahasiswa');
            const cards = document.querySelectorAll('.mahasiswa-card');
            const checkboxes = document.querySelectorAll('.mhs-checkbox');
            const selectedCountText = document.getElementById('selectedCountText');
            const noMatchMsg = document.getElementById('noMatchMessage');

            // Update Hitungan Mahasiswa Dipilih
            function updateCounter() {
                const checkedCount = document.querySelectorAll('.mhs-checkbox:checked').length;
                if (selectedCountText) {
                    selectedCountText.textContent = `${checkedCount} mahasiswa dipilih`;
                }
            }

            // Sync counter saat pertama kali di-load
            updateCounter();

            // Event listener saat checkbox dicentang/dilepas
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateCounter);
            });

            // Fitur Filter Pencarian (Search Bar)
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    let hasVisibleCard = false;

                    cards.forEach(card => {
                        const searchData = card.getAttribute('data-search');
                        if (searchData.includes(query)) {
                            card.classList.remove('hidden');
                            hasVisibleCard = true;
                        } else {
                            card.classList.add('hidden');
                        }
                    });

                    if (noMatchMsg) {
                        if (!hasVisibleCard && cards.length > 0) {
                            noMatchMsg.classList.remove('hidden');
                        } else {
                            noMatchMsg.classList.add('hidden');
                        }
                    }
                });
            }
        });
    </script>
@endsection
