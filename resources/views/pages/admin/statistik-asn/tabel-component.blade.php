@php
    $emptyMessage = $emptyMessage ?? 'Tidak ada data ASN yang ditemukan.';
@endphp

<!-- Tabel Data -->
<div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-black/5 border-b border-border">
                <tr>
                    <th
                        class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-center w-16">
                        No
                    </th>
                    <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider">
                        Nama
                    </th>
                    <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider text-left">
                        Jabatan
                    </th>
                    <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider">
                        Nomor Telepon
                    </th>
                    <th class="px-5 py-4 text-xs font-semibold text-text-light uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($items as $item)
                    @php
                        $id = data_get($item, 'id', '-');
                        $name = data_get($item, 'name', '-');
                        $email = data_get($item, 'email', '-');
                        $phone = data_get($item, 'phone');
                        $jabatan =
                            data_get($item, 'asnProfile.jabatan') ?? (data_get($item, 'asn_profile.jabatan') ?? '-');

                        // Nomor urut tetap akurat meskipun menggunakan pagination
                        $nomor =
                            method_exists($items, 'firstItem') && $items->firstItem()
                                ? $items->firstItem() + $loop->index
                                : $loop->iteration;
                    @endphp

                    <tr class="hover:bg-black/[0.02] transition-colors duration-200">
                        <td class="px-5 py-4 text-xs font-semibold text-text-light text-center align-middle">
                            {{ $nomor }}
                        </td>
                        <td class="px-5 py-4 text-sm font-bold text-text align-middle">
                            {{ $name }}
                        </td>
                        <td class="px-5 py-4 text-left align-middle">
                            <span
                                class="inline-flex items-center px-2.5 py-1 bg-primary/10 text-primary-dark text-[10px] font-bold uppercase rounded-md border border-primary/20">
                                {{ $jabatan }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-xs text-text-light align-middle">
                            {{ $email }}
                        </td>
                        <td class="px-5 py-4 text-xs text-text-light align-middle">
                            {{ $phone ?? '-' }}
                        </td>
                        <td>
                            <a href={{ route('admin-asn-detail', $id) }}
                                class="px-3 py-1.5 bg-primary/10 text-primary-dark text-xs font-semibold rounded-lg hover:bg-primary/20">
                                Detail
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-xs text-text-light italic">
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
