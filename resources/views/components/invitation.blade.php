<div class="font-montserrat">
    <!-- Alert Sukses -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-success/10 border border-success rounded-lg flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-medium text-success">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Alert Error -->
    @if (session('error'))
        <div class="mb-6 bg-danger/10 border border-danger p-4 rounded-xl flex items-start gap-3 shadow-sm">
            <svg class="h-5 w-5 text-danger shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-semibold text-danger">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Kondisi Data Kosong -->
    @if ($undangan->isEmpty())
        <div class="py-12 bg-surface border border-border rounded-2xl text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="text-lg font-bold text-text">Kotak Masuk Kosong</h3>
            <p class="text-sm text-text-light mt-1">Saat ini tidak ada undangan tugas yang menunggu.</p>
        </div>
    @else
        {{-- ========================================================= --}}
        {{-- 1. TAMPILAN DESKTOP & TABLET (Tabel Undangan)            --}}
        {{-- ========================================================= --}}
        <div class="hidden md:block overflow-x-auto bg-surface border border-border rounded-[10px] shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black/5 border-b border-border">
                    <tr>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center w-12 align-middle">
                            No</th>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider w-1/3 align-middle">
                            Judul Tugas</th>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider align-middle">
                            Diundang Oleh</th>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider align-middle">
                            Pembuat (ASN)</th>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider align-middle">
                            Deadline</th>
                        <th
                            class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center align-middle">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($undangan as $item)
                        <tr class="hover:bg-black/[0.02] transition-colors duration-200 group">

                            <!-- Nomor -->
                            <td class="px-5 py-4 align-middle text-center text-xs font-semibold text-text-light">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Judul Tugas -->
                            <td class="px-5 py-4 align-middle">
                                <h3
                                    class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $item->tugas->judul }}
                                </h3>
                            </td>

                            <!-- Diundang Oleh -->
                            <td class="px-5 py-4 align-middle">
                                <p class="text-xs font-medium text-text flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-text-light shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $item->pengundang->user->name }}
                                </p>
                            </td>

                            <!-- Pembuat ASN -->
                            <td class="px-5 py-4 align-middle">
                                <p class="text-xs font-medium text-text flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-text-light shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $item->tugas->asn->name }}
                                </p>
                            </td>

                            <!-- Deadline -->
                            <td class="px-5 py-4 align-middle whitespace-nowrap">
                                <p class="text-xs font-bold text-danger flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $item->tugas->deadline?->format('d M Y, H:i') ?? '-' }}
                                </p>
                            </td>

                            <!-- Aksi (Tolak / Terima) -->
                            <td class="px-5 py-4 align-middle text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Form Tolak -->
                                    <form method="POST" data-confirm="Apakah anda yakin ingin menolak undangan ini?"
                                        action="{{ route('mahasiswa-undangan-tolak', $item->id) }}" class="m-0">
                                        @csrf
                                        <x-buttonv2 type="submit" color="danger" class="text-xs py-1.5 px-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Tolak
                                        </x-buttonv2>
                                    </form>

                                    <!-- Form Terima -->
                                    <form method="POST" data-confirm="Apakah anda yakin ingin menerima undangan ini?"
                                        action="{{ route('mahasiswa-undangan-terima', $item->id) }}" class="m-0">
                                        @csrf
                                        <x-buttonv2 type="submit" color="accent-dark" class="text-xs py-1.5 px-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Terima
                                        </x-buttonv2>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ========================================================= --}}
        {{-- 2. TAMPILAN MOBILE (Kartu Undangan Compact)              --}}
        {{-- ========================================================= --}}
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @foreach ($undangan as $item)
                <div
                    class="bg-surface border border-border rounded-[10px] p-4 shadow-sm flex flex-col justify-center gap-3">

                    <!-- Header Mobile: No & Deadline -->
                    <div class="flex items-center justify-between border-b border-border/60 pb-2.5">
                        <span class="text-xs font-bold text-text-light bg-black/5 px-2 py-0.5 rounded">
                            #{{ $loop->iteration }}
                        </span>
                        <p class="text-xs font-bold text-danger flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $item->tugas->deadline?->format('d M Y, H:i') ?? '-' }}
                        </p>
                    </div>

                    <!-- Judul Tugas -->
                    <div>
                        <h3 class="text-base font-bold text-text leading-snug">
                            {{ $item->tugas->judul }}
                        </h3>
                    </div>

                    <!-- Metadata Mobile -->
                    <div class="space-y-1.5 bg-background p-2.5 rounded-md border border-border/50 text-[12px]">
                        <p class="text-text-light flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Diundang oleh: <span
                                class="font-semibold text-text">{{ $item->pengundang->user->name }}</span>
                        </p>
                        <p class="text-text-light flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Pembuat (ASN): <span class="font-semibold text-text">{{ $item->tugas->asn->name }}</span>
                        </p>
                    </div>

                    <!-- Footer Mobile: Aksi Button -->
                    <div class="pt-2 border-t border-border/60 flex items-center justify-end gap-2 mt-1">
                        <!-- Form Tolak -->
                        <form method="POST" data-confirm="Apakah anda yakin ingin menolak undangan ini?"
                            action="{{ route('mahasiswa-undangan-tolak', $item->id) }}"
                            class="m-0 flex-1 sm:flex-initial">
                            @csrf
                            <x-buttonv2 type="submit" color="danger" class="w-full text-xs py-2 justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak
                            </x-buttonv2>
                        </form>

                        <!-- Form Terima -->
                        <form method="POST" data-confirm="Apakah anda yakin ingin menerima undangan ini?"
                            action="{{ route('mahasiswa-undangan-terima', $item->id) }}"
                            class="m-0 flex-1 sm:flex-initial">
                            @csrf
                            <x-buttonv2 type="submit" color="accent-dark"
                                class="w-full text-xs py-2 justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Terima
                            </x-buttonv2>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>
