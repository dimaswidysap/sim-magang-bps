{{-- {{ $tugasBelumSelesai }} --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 font-montserrat">
    @forelse ($tugasBelumSelesai as $tugas)
        <div
            class="bg-surface border border-border rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col p-5 group">

            <!-- Header Card: Status & Deadline -->
            <div class="flex items-start justify-between gap-2 mb-3">
                {{-- <!-- Status Badge -->
                                    @if (strtolower($tugas->status) === 'tersedia')
                                        <span
                                            class="inline-flex px-2.5 py-1 bg-success/10 text-success text-[10px] font-bold rounded-md uppercase tracking-wide border border-success/20">
                                            {{ $tugas->status }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2.5 py-1 bg-warning/10 text-warning text-[10px] font-bold rounded-md uppercase tracking-wide border border-warning/20">
                                            {{ $tugas->status ?? 'Unknown' }}
                                        </span>
                                    @endif --}}

                <!-- Waktu Deadline -->
                <div class="text-left">
                    <p class="text-[10px] text-text-light font-medium uppercase tracking-wider mb-0.5">
                        Tenggat Waktu</p>
                    <p class="text-xs font-bold text-danger flex items-center justify-end gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y, H:i') : '-' }}
                    </p>


                </div>
            </div>

            <!-- Body Card: Judul & Deskripsi -->
            <div class="flex-1">
                <h3 class="text-lg font-bold text-text mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                    {{ $tugas->judul }}
                </h3>
                <p class="text-sm text-text-light line-clamp-3 mb-4">
                    {{ $tugas->deskripsi }}
                </p>
            </div>

            <!-- Bagian Skills (Keahlian yang dibutuhkan) -->
            @if (isset($tugas->skills) && count($tugas->skills) > 0)
                <div class="mt-2 mb-5">
                    <p class="text-[10px] text-text-light uppercase font-semibold mb-2">Skill
                        Dibutuhkan:</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($tugas->skills as $skill)
                            <span
                                class="inline-flex items-center px-2 py-1 bg-background border border-border rounded text-[10px] font-medium text-text-light">
                                {{ $skill->nama_skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <hr class="border-border mb-4">

            <!-- Footer: Button Aksi -->
            <div class="flex justify-end gap-4">
                {{-- action="{{ route('admin-asn-destroy', $detailAsn->id) }}" --}}
                <form method="POST"
                    data-confirm="Yakin ingin menghapus data asn ini? Semua data terkait ikut terhapus dan tidak bisa dikembalikan!"
                    action="{{ route('asn-tugas-destroy', $tugas->id) }}">
                    @csrf
                    @method('DELETE')


                    <x-main-button
                        class="border border-danger text-danger! text-xs px-4 py-2 rounded-lg  transition-colors shadow-sm inline-flex items-center gap-2"
                        type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 7h12M9 7V5h6v2m-8 0v12a2 2 0 002 2h6a2 2 0 002-2V7M10 11l4 4M14 11l-4 4" />
                        </svg>
                        <span>Hapus tugas</span>
                    </x-main-button>

                </form>
                {{-- href="{{ route('edit-tugas-form', $tugas->id) }}" --}}
                <x-main-button href="{{ route('edit-tugas-form', $tugas->id) }}"
                    class="bg-primary hover:bg-primary-dark  text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2">
                    {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg> --}}
                    <span>Lihat & Edit</span>
                </x-main-button>
            </div>

        </div>
    @empty
        <!-- Tampilan Jika Data Tugas Kosong -->
        <div
            class="col-span-1 md:col-span-2 lg:col-span-3 py-16 bg-surface border border-border rounded-xl text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <h3 class="text-lg font-bold text-text">Belum Ada Tugas Tersedia</h3>
            <p class="text-sm text-text-light mt-1">Saat ini tidak ada tugas magang yang membutuhkan
                bantuan.</p>
        </div>
    @endforelse
</div>
