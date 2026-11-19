@extends('layouts.app')

@section('content')
    <main class="relative w-full min-h-screen flex bg-background overflow-x-hidden">
        @include('components.header-mobile')
        @include('components.mahasiswa.mahasiswa-sidebar')

        <section class="flex flex-col flex-1 min-w-0 md:pl-60 container-content-mobile">
            @include('components.mahasiswa.header-mahasiswa')

            <section class="w-full p-2">
                <section class="container-dalam flex flex-col gap-4 font-montserrat">

                    <!-- Header Halaman -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-xl md:text-2xl font-bold text-text">Pengajuan Surat Libur</h1>
                            <p class="text-xs md:text-sm text-text-light mt-0.5">
                                Kelola pengajuan izin/libur magang Anda. Surat libur akan ditinjau oleh pembimbing ASN Anda.
                            </p>
                        </div>

                        @if ($profil->asn_profile_id)
                            <a href="{{ route('mahasiswa.pengajuan-libur.create') }}"
                                class="w-full sm:w-auto shrink-0 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-lg transition-colors shadow-xs inline-flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Ajukan Libur
                            </a>
                        @endif
                    </div>

                    <!-- Alert Notifikasi -->
                    @if (session('success'))
                        <div class="p-4 rounded-xl bg-success/10 border border-success/30 text-success-dark text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="p-4 rounded-xl bg-danger/10 border border-danger/30 text-danger text-sm font-medium">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (! $profil->asn_profile_id)
                        <div class="p-4 rounded-xl bg-warning/15 border border-warning/30 text-warning-dark text-xs md:text-sm font-medium">
                            Anda belum memiliki pembimbing ASN yang ditetapkan. Pengajuan surat libur baru dapat dilakukan setelah pembimbing ASN disetujui.
                        </div>
                    @endif

                    <!-- Filter Status Tab -->
                    <div class="flex flex-wrap items-center gap-2 border-b border-border pb-3">
                        <a href="{{ route('mahasiswa.pengajuan-libur.index', ['status' => 'semua']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'semua' ? 'bg-primary text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Semua ({{ $jumlahSemua }})
                        </a>

                        <a href="{{ route('mahasiswa.pengajuan-libur.index', ['status' => 'pending']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'pending' ? 'bg-warning text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Menunggu Persetujuan ({{ $jumlahPending }})
                        </a>

                        <a href="{{ route('mahasiswa.pengajuan-libur.index', ['status' => 'disetujui']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'disetujui' ? 'bg-success text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Disetujui ({{ $jumlahDisetujui }})
                        </a>

                        <a href="{{ route('mahasiswa.pengajuan-libur.index', ['status' => 'ditolak']) }}"
                            class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $filterStatus === 'ditolak' ? 'bg-danger text-white shadow-xs' : 'bg-surface border border-border text-text-light hover:text-text' }}">
                            Ditolak ({{ $jumlahDitolak }})
                        </a>
                    </div>

                    <!-- Tabel Daftar Surat Libur -->
                    <div class="bg-surface rounded-xl shadow-xs border border-border overflow-hidden w-full">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead
                                    class="hidden md:table-header-group bg-background/50 border-b border-border text-text-light text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="px-4 py-3.5 font-bold text-center whitespace-nowrap">No</th>
                                        <th class="px-4 py-3.5 font-bold whitespace-nowrap">Tanggal Libur</th>
                                        <th class="px-4 py-3.5 font-bold text-center whitespace-nowrap">Durasi</th>
                                        <th class="px-4 py-3.5 font-bold">Alasan</th>
                                        <th class="px-4 py-3.5 font-bold">Pembimbing ASN</th>
                                        <th class="px-4 py-3.5 font-bold text-center whitespace-nowrap">Status</th>
                                        <th class="px-4 py-3.5 font-bold text-center whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="block md:table-row-group divide-y divide-border md:divide-y-0">
                                    @forelse ($daftarPengajuan as $index => $item)
                                        <tr
                                            class="block md:table-row p-4 md:p-0 hover:bg-primary/5 transition-colors md:border-b md:border-border last:border-b-0">
                                            <td class="hidden md:table-cell px-4 py-3.5 text-sm text-text-light text-center whitespace-nowrap">
                                                {{ ($daftarPengajuan->firstItem() ?? 0) + $index }}
                                            </td>

                                            <td class="block md:table-cell md:px-4 md:py-3.5 mb-2 md:mb-0 whitespace-nowrap">
                                                <div class="flex justify-between items-center md:block">
                                                    <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Tanggal:</span>
                                                    <div class="text-xs text-text font-semibold text-right md:text-left">
                                                        <span>{{ $item->tanggal_mulai->translatedFormat('d M Y') }}</span>
                                                        @if (! $item->tanggal_mulai->equalTo($item->tanggal_selesai))
                                                            <span class="text-text-light font-normal"> s.d. </span>
                                                            <span>{{ $item->tanggal_selesai->translatedFormat('d M Y') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="block md:table-cell md:px-4 md:py-3.5 mb-2 md:mb-0 md:text-center whitespace-nowrap">
                                                <div class="flex justify-between md:justify-center items-center">
                                                    <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Durasi:</span>
                                                    <span class="px-2.5 py-0.5 rounded-md bg-primary/10 text-primary text-xs font-bold">
                                                        {{ $item->jumlah_hari }} Hari
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="block md:table-cell md:px-4 md:py-3.5 mb-2 md:mb-0 max-w-xs">
                                                <div class="flex justify-between md:block gap-3">
                                                    <span class="md:hidden text-[10px] font-bold text-text-light uppercase shrink-0">Alasan:</span>
                                                    <p class="text-xs text-text line-clamp-2 text-right md:text-left">
                                                        {{ $item->alasan }}
                                                    </p>
                                                </div>
                                            </td>

                                            <td class="block md:table-cell md:px-4 md:py-3.5 mb-2 md:mb-0">
                                                <div class="flex justify-between items-center md:block">
                                                    <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Pembimbing:</span>
                                                    <span class="text-xs text-text-light">
                                                        {{ $item->pembimbing?->user?->name ?? '-' }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="block md:table-cell md:px-4 md:py-3.5 md:text-center mt-2 md:mt-0 whitespace-nowrap">
                                                <div class="flex justify-between md:justify-center items-center">
                                                    <span class="md:hidden text-[10px] font-bold text-text-light uppercase">Status:</span>
                                                    @if ($item->status === 'disetujui')
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-success/10 border border-success/20 text-success-dark">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                                                            Disetujui
                                                        </span>
                                                    @elseif ($item->status === 'ditolak')
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-danger/10 border border-danger/20 text-danger-dark">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                                                            Ditolak
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-warning/15 border border-warning/30 text-warning-dark">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                                                            Pending
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Opsi: Lihat Detail, Edit, Hapus -->
                                            <td class="block md:table-cell md:px-4 md:py-3.5 md:text-center mt-3 md:mt-0 pt-3 md:pt-0 border-t border-border md:border-0 whitespace-nowrap">
                                                <div class="flex flex-wrap items-center justify-end md:justify-center gap-1.5">
                                                    <a href="{{ route('mahasiswa.pengajuan-libur.show', $item->id) }}"
                                                        class="px-3 py-1.5 rounded-lg bg-background hover:bg-primary text-text hover:text-white border border-border hover:border-primary text-xs font-semibold transition-colors">
                                                        Detail
                                                    </a>

                                                    @if ($item->bisaDieditAtauDihapus())
                                                        <a href="{{ route('mahasiswa.pengajuan-libur.edit', $item->id) }}"
                                                            class="px-3 py-1.5 rounded-lg bg-warning/15 hover:bg-warning text-warning-dark hover:text-white border border-warning/30 text-xs font-semibold transition-colors">
                                                            Edit
                                                        </a>

                                                        <form method="POST"
                                                            action="{{ route('mahasiswa.pengajuan-libur.destroy', $item->id) }}"
                                                            data-confirm="Apakah Anda yakin ingin menghapus pengajuan surat libur ini?"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="px-3 py-1.5 rounded-lg bg-danger/10 hover:bg-danger text-danger hover:text-white border border-danger/20 text-xs font-semibold transition-colors cursor-pointer">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="block md:table-row">
                                            <td colspan="7" class="block md:table-cell px-6 py-12 text-center">
                                                <p class="text-base font-bold text-text">Belum Ada Pengajuan Libur</p>
                                                <p class="text-sm text-text-light mt-1">
                                                    Klik tombol "Ajukan Libur" apabila Anda memerlukan izin libur magang.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($daftarPengajuan->total() > 0)
                            <div class="px-4 py-3.5 bg-background/40 border-t border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <p class="text-xs text-text-light">
                                    Menampilkan <strong class="text-text">{{ $daftarPengajuan->firstItem() }}</strong>
                                    s.d. <strong class="text-text">{{ $daftarPengajuan->lastItem() }}</strong>
                                    dari total <strong class="text-text">{{ $daftarPengajuan->total() }}</strong> pengajuan
                                </p>
                                <div>
                                    {{ $daftarPengajuan->links() }}
                                </div>
                            </div>
                        @endif
                    </div>

                </section>
            </section>
        </section>
    </main>
@endsection
