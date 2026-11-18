@extends('layouts.app')

@section('content')
    @php
        $activeTab = old('tipe_pendaftaran', 'individu');
        $oldAnggota = old('anggota', [
            ['name' => '', 'email' => ''],
            ['name' => '', 'email' => ''],
        ]);
    @endphp

    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-5xl mx-auto bg-surface rounded-2xl shadow-sm border border-border p-6 md:p-10">

            <!-- Header Halaman -->
            <div class="mb-6 pb-4 border-b border-border">
                <h1 class="text-2xl font-bold text-text">Buat Akun Magang/PKL</h1>
                <p class="text-sm text-text-light mt-1">
                    Pilih mode pendaftaran <strong>Individu</strong> atau <strong>Kelompok</strong>. Peserta akan melengkapi biodata dan periode magang secara mandiri setelah login sebelum mengajukan pembimbing.
                </p>
            </div>

            <!-- Tab Navigasi: Individu vs Kelompok -->
            <div class="mb-8">
                <div class="grid grid-cols-2 p-1.5 bg-background border border-border rounded-xl gap-2">
                    <button type="button" id="tab-btn-individu"
                        class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-sm font-bold transition-all cursor-pointer {{ $activeTab === 'individu' ? 'bg-primary text-white shadow-sm' : 'text-text-light hover:text-text' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Pendaftaran Individu</span>
                    </button>

                    <button type="button" id="tab-btn-kelompok"
                        class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-sm font-bold transition-all cursor-pointer {{ $activeTab === 'kelompok' ? 'bg-primary text-white shadow-sm' : 'text-text-light hover:text-text' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Pendaftaran Kelompok</span>
                    </button>
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

            <form method="POST" action="{{ route('admin.mahasiswa.store') }}" class="space-y-8"
                data-confirm="Apakah Anda yakin ingin menyimpan akun peserta magang ini?">
                @csrf
                <input type="hidden" name="tipe_pendaftaran" id="input-tipe-pendaftaran" value="{{ $activeTab }}">

                <!-- PANEL TAB 1: PENDAFTARAN INDIVIDU -->
                <div id="panel-individu" class="{{ $activeTab === 'individu' ? '' : 'hidden' }} space-y-4">
                    <h2 class="text-lg font-semibold text-primary flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Data Akun Peserta Individu
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-text-light mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap peserta"
                                class="input-individu w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                                class="input-individu w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Password</label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter"
                                class="input-individu w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <!-- PANEL TAB 2: PENDAFTARAN KELOMPOK -->
                <div id="panel-kelompok" class="{{ $activeTab === 'kelompok' ? '' : 'hidden' }} space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-primary flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Data Akun Anggota Kelompok
                            </h2>
                            <p class="text-xs text-text-light mt-0.5">
                                Peserta urutan <strong>ke-1</strong> otomatis menjadi perwakilan kelompok yang mengirimkan tombol <strong>Ajukan Pembimbing</strong>.
                            </p>
                        </div>

                        <button type="button" id="btn-tambah-anggota"
                            class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3.5 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Anggota
                        </button>
                    </div>

                    <div id="container-anggota-kelompok" class="space-y-4">
                        @foreach ($oldAnggota as $idx => $anggota)
                            <div class="anggota-card bg-background p-5 rounded-xl border border-border space-y-4">
                                <div class="flex items-center justify-between border-b border-border pb-2.5">
                                    <div class="flex items-center gap-2">
                                        <span class="anggota-nomor text-sm font-bold text-text">Anggota #{{ $idx + 1 }}</span>
                                        <span class="badge-ketua {{ $idx === 0 ? '' : 'hidden' }} inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-primary/10 text-primary border border-primary/20">
                                            Peserta Pertama (Pengaju Pembimbing)
                                        </span>
                                    </div>

                                    <button type="button"
                                        class="btn-hapus-anggota {{ $idx < 2 ? 'hidden' : '' }} text-xs font-semibold text-danger hover:underline cursor-pointer">
                                        Hapus
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-text-light mb-1.5">Nama Lengkap</label>
                                        <input type="text" name="anggota[{{ $idx }}][name]"
                                            value="{{ $anggota['name'] ?? '' }}" placeholder="Nama anggota"
                                            class="input-kelompok w-full px-3.5 py-2.5 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-text-light mb-1.5">Email</label>
                                        <input type="email" name="anggota[{{ $idx }}][email]"
                                            value="{{ $anggota['email'] ?? '' }}" placeholder="email@domain.com"
                                            class="input-kelompok w-full px-3.5 py-2.5 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-text-light mb-1.5">Password</label>
                                        <input type="password" name="anggota[{{ $idx }}][password]"
                                            placeholder="Minimal 8 karakter"
                                            class="input-kelompok w-full px-3.5 py-2.5 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary transition-colors">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Informasi Alur Baru -->
                <div class="text-xs text-text-light bg-background border border-border rounded-xl p-4 space-y-1.5">
                    <p class="font-semibold text-text">Alur Pendaftaran & Penetapan Pembimbing:</p>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Admin membuat akun peserta (Individu atau Kelompok) tanpa menetapkan pembimbing dan tanggal periode terlebih dahulu.</li>
                        <li>Peserta login menggunakan akun yang dibuat dan melengkapi profil (NIM, instansi, jurusan, serta tanggal mulai & selesai magang).</li>
                        <li>Setelah profil lengkap, tombol <strong>Ajukan Pembimbing</strong> muncul pada akun peserta (atau akun peserta pertama untuk magang kelompok).</li>
                        <li>Admin memverifikasi pengajuan pada menu <strong>Permintaan Magang</strong> untuk memilih pembimbing ASN atau menolak beserta alasannya.</li>
                    </ol>
                </div>

                <hr class="border-border">

                <!-- Footer Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-2">
                    <x-buttonv2 href="{{ route('admin-mahasiswa') }}" color="primary" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="3" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18" />
                        </svg>
                        Kembali
                    </x-buttonv2>

                    <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Akun
                    </x-buttonv2>
                </div>

            </form>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabBtnIndividu = document.getElementById('tab-btn-individu');
            const tabBtnKelompok = document.getElementById('tab-btn-kelompok');
            const panelIndividu = document.getElementById('panel-individu');
            const panelKelompok = document.getElementById('panel-kelompok');
            const inputTipe = document.getElementById('input-tipe-pendaftaran');

            const containerAnggota = document.getElementById('container-anggota-kelompok');
            const btnTambahAnggota = document.getElementById('btn-tambah-anggota');

            function setTab(tipe) {
                inputTipe.value = tipe;
                const isIndividu = tipe === 'individu';

                panelIndividu.classList.toggle('hidden', !isIndividu);
                panelKelompok.classList.toggle('hidden', isIndividu);

                tabBtnIndividu.className = isIndividu
                    ? 'flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-sm font-bold transition-all cursor-pointer bg-primary text-white shadow-sm'
                    : 'flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-sm font-bold transition-all cursor-pointer text-text-light hover:text-text';

                tabBtnKelompok.className = !isIndividu
                    ? 'flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-sm font-bold transition-all cursor-pointer bg-primary text-white shadow-sm'
                    : 'flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg text-sm font-bold transition-all cursor-pointer text-text-light hover:text-text';

                document.querySelectorAll('.input-individu').forEach(el => el.disabled = !isIndividu);
                document.querySelectorAll('.input-kelompok').forEach(el => el.disabled = isIndividu);
            }

            function refreshNomorAnggota() {
                const cards = containerAnggota.querySelectorAll('.anggota-card');
                cards.forEach((card, idx) => {
                    card.querySelector('.anggota-nomor').textContent = `Anggota #${idx + 1}`;
                    card.querySelector('.badge-ketua').classList.toggle('hidden', idx !== 0);

                    const btnHapus = card.querySelector('.btn-hapus-anggota');
                    btnHapus.classList.toggle('hidden', cards.length <= 2);

                    const inputs = card.querySelectorAll('input');
                    if (inputs[0]) inputs[0].name = `anggota[${idx}][name]`;
                    if (inputs[1]) inputs[1].name = `anggota[${idx}][email]`;
                    if (inputs[2]) inputs[2].name = `anggota[${idx}][password]`;
                });
            }

            tabBtnIndividu.addEventListener('click', () => setTab('individu'));
            tabBtnKelompok.addEventListener('click', () => setTab('kelompok'));

            btnTambahAnggota.addEventListener('click', function () {
                const indexBaru = containerAnggota.querySelectorAll('.anggota-card').length;
                const card = document.createElement('div');
                card.className = 'anggota-card bg-background p-5 rounded-xl border border-border space-y-4';
                card.innerHTML = `
                    <div class="flex items-center justify-between border-b border-border pb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="anggota-nomor text-sm font-bold text-text">Anggota #${indexBaru + 1}</span>
                            <span class="badge-ketua hidden inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-primary/10 text-primary border border-primary/20">
                                Peserta Pertama (Pengaju Pembimbing)
                            </span>
                        </div>
                        <button type="button" class="btn-hapus-anggota text-xs font-semibold text-danger hover:underline cursor-pointer">
                            Hapus
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-text-light mb-1.5">Nama Lengkap</label>
                            <input type="text" name="anggota[${indexBaru}][name]" placeholder="Nama anggota"
                                class="input-kelompok w-full px-3.5 py-2.5 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-text-light mb-1.5">Email</label>
                            <input type="email" name="anggota[${indexBaru}][email]" placeholder="email@domain.com"
                                class="input-kelompok w-full px-3.5 py-2.5 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-text-light mb-1.5">Password</label>
                            <input type="password" name="anggota[${indexBaru}][password]" placeholder="Minimal 8 karakter"
                                class="input-kelompok w-full px-3.5 py-2.5 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                `;
                containerAnggota.appendChild(card);
                refreshNomorAnggota();
            });

            containerAnggota.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-hapus-anggota')) {
                    const cards = containerAnggota.querySelectorAll('.anggota-card');
                    if (cards.length > 2) {
                        e.target.closest('.anggota-card').remove();
                        refreshNomorAnggota();
                    }
                }
            });

            setTab(inputTipe.value || 'individu');
            refreshNomorAnggota();
        });
    </script>
@endsection
