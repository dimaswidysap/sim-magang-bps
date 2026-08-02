<!-- Alert Sukses (Merespons session 'success' dari controller) -->
@if (session('success'))
    <div
        class="mb-6 p-4 bg-success/10 border border-success rounded-lg flex items-center gap-3 shadow-sm font-montserrat mt-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success shrink-0" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd" />
        </svg>
        <span class="text-sm font-medium text-success">{{ session('success') }}</span>
    </div>
@endif

<section class="w-full flex justify-end mt-4 mb-8">
    <x-main-button
        class="bg-primary hover:bg-primary-dark  text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
        href="{{ route('asn.mahasiswa.create') }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
            stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>Tambah data</span>
    </x-main-button>
</section>
<div class="bg-surface rounded-lg shadow-sm border border-border overflow-hidden font-montserrat">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <!-- Table Header -->
            <thead class="bg-background border-b border-border text-text-light text-sm uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Nama Pegawai</th>
                    <th scope="col" class="px-6 py-4 font-semibold">NIP</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Jabatan</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Unit Kerja</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="divide-y divide-border">
                @forelse ($dataAsn as $index => $asn)
                    <tr class="hover:bg-background/50 transition-colors duration-200 group">
                        <!-- Nomor Urut -->
                        <td class="px-6 py-4 text-sm text-text-light text-center">
                            {{ $index + 1 }}
                        </td>

                        <!-- Nama Pegawai -->
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-text">{{ $asn->name ?? '-' }}</p>
                            <!-- Opsional: Menampilkan email kecil di bawah nama -->
                            <p class="text-xs text-text-light mt-0.5">{{ $asn->email ?? '' }}</p>
                        </td>

                        <!-- NIP (Dari relasi asn_profile) -->
                        <td class="px-6 py-4">
                            <span
                                class="text-sm font-mono text-primary-dark bg-primary-light/20 px-2.5 py-1 rounded-md">
                                {{ $asn->asnProfile->nip ?? '-' }}
                            </span>
                        </td>

                        <!-- Jabatan -->
                        <td class="px-6 py-4 text-sm text-text">
                            {{ $asn->asnProfile->jabatan ?? '-' }}
                        </td>

                        <!-- Unit Kerja -->
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium bg-background border border-border text-text-light rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-secondary"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $asn->asnProfile->unit_kerja ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <x-main-button
                                class="bg-primary hover:bg-primary-dark  text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                                href="{{ route('admin-asn-detail', $asn->id) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Detail</span>
                            </x-main-button>
                        </td>
                    </tr>
                @empty
                    <!-- Tampilan Jika Data Kosong -->
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-text-light">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-border"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="font-medium text-text">Belum ada data ASN</p>
                            <p class="text-sm mt-1">Data yang ditambahkan akan muncul di sini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
