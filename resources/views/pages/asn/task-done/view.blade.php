@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-4xl mx-auto space-y-6">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Detail Riwayat Tugas</h1>
                    <p class="text-sm text-text-light mt-1">Informasi lengkap mengenai tugas yang telah berhasil
                        diselesaikan.</p>
                </div>

                <x-main-button href="{{ url()->previous() }}"
                    class="w-full sm:w-auto bg-surface text-text border border-border hover:bg-background text-xs px-4 py-2 rounded-lg transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </x-main-button>
            </div>

            <div class="bg-surface rounded-xl shadow-sm border border-border overflow-hidden">

                <!-- Header Card Detail -->
                <div class="p-6 md:p-8 border-b border-border bg-background">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-2">
                        <h1 class="text-2xl font-bold text-text leading-snug">
                            {{ $tugasDetail->judul }}
                        </h1>

                        <!-- Status Badge -->
                        <div class="shrink-0 mt-1 md:mt-0">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-success/10 text-success text-xs font-bold rounded-full uppercase tracking-wider border border-success/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $tugasDetail->status }}
                            </span>
                        </div>
                    </div>

                    <p class="text-sm text-text-light flex items-center gap-1.5 mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tugas dibuat pada: {{ \Carbon\Carbon::parse($tugasDetail->created_at)->translatedFormat('d F Y') }}
                    </p>
                </div>

                <!-- Body Card Detail -->
                <div class="p-6 md:p-8 space-y-8">

                    <!-- Deskripsi Tugas -->
                    <div>
                        <h2
                            class="text-sm font-semibold text-text-light uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Deskripsi Pekerjaan
                        </h2>
                        <div
                            class="text-sm text-text leading-relaxed bg-[#F8FAFC] border border-border p-5 rounded-xl">
                            {{ $tugasDetail->deskripsi }}</div>
                    </div>

                    <!-- Timeline / Riwayat Waktu -->
                    <div>
                        <h2
                            class="text-sm font-semibold text-text-light uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Timeline Pekerjaan
                        </h2>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 bg-background border border-border rounded-xl">
                            <!-- Waktu Diambil -->
                            <div>
                                <p class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-1">Mulai
                                    Dikerjakan</p>
                                <p class="text-sm font-bold text-text">
                                    {{ $tugasDetail->diambil_at ? \Carbon\Carbon::parse($tugasDetail->diambil_at)->translatedFormat('d M Y, H:i') : '-' }}
                                </p>
                            </div>

                            <!-- Waktu Selesai -->
                            <div>
                                <p class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-1">
                                    Diselesaikan Pada</p>
                                <p class="text-sm font-bold text-success">
                                    {{ $tugasDetail->selesai_at ? \Carbon\Carbon::parse($tugasDetail->selesai_at)->translatedFormat('d M Y, H:i') : '-' }}
                                </p>
                            </div>

                            <!-- Deadline Asli -->
                            <div>
                                <p class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-1">Tenggat
                                    Waktu Asli</p>
                                <p class="text-sm font-bold text-danger">
                                    {{ $tugasDetail->deadline ? \Carbon\Carbon::parse($tugasDetail->deadline)->translatedFormat('d M Y, H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Pihak yang Terlibat -->
                    <div>
                        <h2
                            class="text-sm font-semibold text-text-light uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Pihak Terlibat
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Pembuat (ASN) -->
                            <div class="p-5 bg-background border border-border rounded-xl">
                                <p class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-3">Pemberi
                                    Tugas (ASN)</p>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-primary/10 text-primary-dark flex items-center justify-center font-bold text-sm shrink-0 border border-primary/20">
                                        {{ strtoupper(substr($tugasDetail->asn->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-text">{{ $tugasDetail->asn->name ?? '-' }}</p>
                                        <p class="text-[11px] text-text-light mt-0.5">{{ $tugasDetail->asn->email ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tim Pengerjaan (Mahasiswa) -->
                            <div class="p-5 bg-background border border-border rounded-xl">
                                <p class="text-[11px] font-semibold text-text-light uppercase tracking-wider mb-3">Tim
                                    Pelaksana</p>

                                <div class="space-y-3">
                                    <!-- Ketua -->
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-success/20 text-success-dark flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                            K</div>
                                        <div>
                                            <p class="text-sm font-bold text-text">
                                                <!-- Mengakses relasi user untuk nama jika ada, jika tidak fallback ke NIM -->
                                                {{ $tugasDetail->mahasiswaProfile->user->name ?? 'NIM: ' . ($tugasDetail->mahasiswaProfile->nim ?? '-') }}
                                            </p>
                                            <p class="text-[11px] text-text-light mt-0.5">
                                                {{ $tugasDetail->mahasiswaProfile->jurusan ?? '-' }} -
                                                {{ $tugasDetail->mahasiswaProfile->instansi_asal ?? '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Anggota Tambahan -->
                                    @if (isset($tugasDetail->anggota) && count($tugasDetail->anggota) > 0)
                                        <div class="pt-3 border-t border-border border-dashed">
                                            <p class="text-[11px] text-text-light mb-2">Anggota Tim
                                                ({{ count($tugasDetail->anggota) }} orang):</p>
                                            <ul class="space-y-2">
                                                @foreach ($tugasDetail->anggota as $anggota)
                                                    <li class="flex items-center gap-2 text-sm text-text">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-border"></span>
                                                        <!-- Jika objek anggota memiliki relasi ke mahasiswaProfile->user->name -->
                                                        {{ $anggota->mahasiswaProfile->user->name ?? 'Anggota ID: ' . $anggota->mahasiswa_profile_id }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
