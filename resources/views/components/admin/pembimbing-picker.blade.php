@props([
    'pembimbingList',
    'selectedId' => null,
    'allowClear' => false,
])

@php
    $selectedIdStr = (string) ($selectedId ?? '');
    $selectedAsn = $pembimbingList->first(fn ($item) => (string) $item->id === $selectedIdStr);
@endphp

<div id="pembimbing-picker-wrapper" class="bg-background p-5 rounded-xl border border-border">
    <input type="hidden" name="asn_profile_id" id="asn_profile_id_input" value="{{ $selectedIdStr }}">

    <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-medium text-text-light">
            Pembimbing Magang Terpilih
        </label>
        @if ($allowClear)
            <button type="button" id="btn-clear-pembimbing"
                class="{{ $selectedAsn ? 'inline-flex' : 'hidden' }} items-center gap-1 text-xs font-semibold text-danger hover:underline cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Kosongkan Pilihan
            </button>
        @endif
    </div>

    <!-- Tampilan Saat Belum Ada Pembimbing Dipilih -->
    <div id="pembimbing-empty-state"
        class="{{ $selectedAsn ? 'hidden' : 'flex' }} flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-surface border border-dashed border-border rounded-xl">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-full bg-background border border-border flex items-center justify-center text-text-light shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-text">Belum ada pembimbing magang dipilih</p>
                <p class="text-xs text-text-light mt-0.5">Klik tombol di samping untuk memilih ASN pembimbing dari daftar kartu.</p>
            </div>
        </div>

        <button type="button" data-open-pembimbing-modal
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-dark text-surface text-xs font-bold rounded-lg transition-colors cursor-pointer shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Pilih Pembimbing
        </button>
    </div>

    <!-- Tampilan Card Ringkasan Pembimbing Terpilih -->
    @php
        $selNama = $selectedAsn?->user?->name ?? '';
        $selNip = $selectedAsn?->nip ?? '-';
        $selJabatan = collect([$selectedAsn?->jabatan, $selectedAsn?->unit_kerja])->filter()->implode(' • ');
        $selJumlah = $selectedAsn?->jumlah_bimbingan ?? 0;
        $selAvatarPath = $selectedAsn?->avatar ?: ($selectedAsn?->user?->avatar ?? null);
        $selAvatarUrl = $selAvatarPath ? asset('storage/' . $selAvatarPath) : '';
        $selInisial = $selNama !== '' ? strtoupper(substr($selNama, 0, 1)) : 'A';
    @endphp

    <div id="pembimbing-selected-card"
        class="{{ $selectedAsn ? 'flex' : 'hidden' }} flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-surface border border-primary/30 rounded-xl shadow-xs">
        <div class="flex items-center gap-3.5 min-w-0">
            <div class="w-12 h-12 rounded-full bg-primary text-surface flex items-center justify-center font-bold text-base overflow-hidden shrink-0 border border-primary/20">
                <img id="selected-pembimbing-avatar" src="{{ $selAvatarUrl }}" alt="Avatar Pembimbing"
                    class="{{ $selAvatarUrl !== '' ? 'block' : 'hidden' }} w-full h-full object-cover">
                <span id="selected-pembimbing-inisial" class="{{ $selAvatarUrl !== '' ? 'hidden' : 'block' }}">
                    {{ $selInisial }}
                </span>
            </div>

            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <p id="selected-pembimbing-nama" class="text-sm font-bold text-text truncate">
                        {{ $selNama }}
                    </p>
                    <span id="selected-pembimbing-badge"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-primary/10 text-primary-dark border border-primary/20">
                        Membimbing: {{ $selJumlah }} orang
                    </span>
                </div>
                <p id="selected-pembimbing-nip" class="text-xs text-text-light mt-0.5">
                    NIP: {{ $selNip }}
                </p>
                <p id="selected-pembimbing-jabatan" class="text-xs text-text-light truncate">
                    {{ $selJabatan !== '' ? $selJabatan : 'Jabatan / Unit kerja belum diisi' }}
                </p>
            </div>
        </div>

        <button type="button" data-open-pembimbing-modal
            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-background hover:bg-primary hover:text-surface text-text border border-border hover:border-primary text-xs font-semibold rounded-lg transition-colors cursor-pointer shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Ganti Pembimbing
        </button>
    </div>

    @if ($pembimbingList->isEmpty())
        <p class="text-xs text-danger mt-2">
            Belum ada akun ASN aktif yang memiliki profil. Silakan tambahkan data ASN terlebih dahulu.
        </p>
    @endif
</div>

<!-- POPUP MODAL PILIH PEMBIMBING MAGANG -->
<div id="modal-pilih-pembimbing"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-xs p-4"
    role="dialog" aria-modal="true" aria-labelledby="modal-pembimbing-title">

    <div id="modal-pilih-pembimbing-box"
        class="bg-surface border border-border rounded-2xl shadow-xl w-full max-w-4xl max-h-[88vh] flex flex-col overflow-hidden">

        <!-- Header Modal -->
        <div class="px-6 py-4 border-b border-border flex items-start justify-between gap-4 bg-surface">
            <div>
                <h3 id="modal-pembimbing-title" class="text-lg font-bold text-text">
                    Pilih Pembimbing Magang (ASN)
                </h3>
                <p class="text-xs text-text-light mt-0.5">
                    Klik salah satu kartu pembimbing di bawah. Daftar diurutkan dari jumlah bimbingan paling sedikit.
                </p>
            </div>
            <button type="button" data-close-pembimbing-modal
                class="p-1.5 rounded-lg text-text-light hover:text-text hover:bg-background transition-colors cursor-pointer"
                aria-label="Tutup popup">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Bar Pencarian -->
        <div class="px-6 py-3.5 bg-background border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-text-light absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                </svg>
                <input type="text" id="search-pembimbing-input"
                    placeholder="Cari nama pembimbing, NIP, jabatan, atau unit kerja..."
                    class="w-full pl-10 pr-4 py-2 bg-surface border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary transition-colors">
            </div>
            <span id="search-pembimbing-count" class="text-xs font-medium text-text-light shrink-0">
                Total: {{ $pembimbingList->count() }} pembimbing
            </span>
        </div>

        <!-- Grid Card Pembimbing (Scrollable) -->
        <div class="p-6 overflow-y-auto flex-1 bg-background/50">
            @if ($pembimbingList->isEmpty())
                <div class="text-center py-12 bg-surface rounded-xl border border-border">
                    <p class="text-sm font-semibold text-text">Tidak ada pembimbing ASN aktif tersedia</p>
                    <p class="text-xs text-text-light mt-1">Pastikan akun ASN berstatus aktif dan sudah memiliki profil.</p>
                </div>
            @else
                <div id="pembimbing-cards-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($pembimbingList as $asn)
                        @php
                            $namaAsn = $asn->user->name ?? 'ASN';
                            $nipAsn = $asn->nip ?? '-';
                            $jabatanAsn = collect([$asn->jabatan, $asn->unit_kerja])->filter()->implode(' • ');
                            $jumlahBimbingan = (int) ($asn->jumlah_bimbingan ?? 0);
                            $avatarPath = $asn->avatar ?: ($asn->user->avatar ?? null);
                            $avatarUrl = $avatarPath ? asset('storage/' . $avatarPath) : '';
                            $inisial = strtoupper(substr($namaAsn, 0, 1));
                            $isSelected = $selectedIdStr === (string) $asn->id;
                            $searchKeywords = strtolower(trim($namaAsn . ' ' . $nipAsn . ' ' . ($asn->jabatan ?? '') . ' ' . ($asn->unit_kerja ?? '')));

                            if ($jumlahBimbingan === 0) {
                                $badgeClass = 'bg-emerald-500/10 text-emerald-700 border-emerald-500/25';
                                $badgeText = 'Tersedia • 0 bimbingan';
                            } elseif ($jumlahBimbingan <= 2) {
                                $badgeClass = 'bg-primary/10 text-primary-dark border-primary/20';
                                $badgeText = "Membimbing: {$jumlahBimbingan} orang";
                            } else {
                                $badgeClass = 'bg-amber-500/10 text-amber-700 border-amber-500/25';
                                $badgeText = "Membimbing: {$jumlahBimbingan} orang";
                            }
                        @endphp

                        <button type="button"
                            class="pembimbing-option-card group text-left p-4 rounded-xl border transition-all cursor-pointer flex flex-col justify-between gap-3 {{ $isSelected ? 'bg-primary/5 border-primary ring-2 ring-primary/25' : 'bg-surface border-border hover:border-primary hover:shadow-sm' }}"
                            data-id="{{ $asn->id }}"
                            data-nama="{{ $namaAsn }}"
                            data-nip="{{ $nipAsn }}"
                            data-jabatan="{{ $jabatanAsn !== '' ? $jabatanAsn : 'Jabatan / Unit kerja belum diisi' }}"
                            data-jumlah="{{ $jumlahBimbingan }}"
                            data-avatar="{{ $avatarUrl }}"
                            data-inisial="{{ $inisial }}"
                            data-search="{{ $searchKeywords }}">

                            <div class="flex items-start gap-3.5">
                                <!-- Foto / Inisial -->
                                <div class="w-12 h-12 rounded-full bg-primary text-surface flex items-center justify-center font-bold text-base overflow-hidden shrink-0 border border-primary/20">
                                    @if ($avatarUrl !== '')
                                        <img src="{{ $avatarUrl }}" alt="{{ $namaAsn }}" class="w-full h-full object-cover">
                                    @else
                                        <span>{{ $inisial }}</span>
                                    @endif
                                </div>

                                <!-- Info ASN -->
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-1">
                                            {{ $namaAsn }}
                                        </p>
                                        <span class="card-check-badge {{ $isSelected ? 'inline-flex' : 'hidden' }} items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary text-surface shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Dipilih
                                        </span>
                                    </div>
                                    <p class="text-xs text-text-light mt-0.5">NIP: {{ $nipAsn }}</p>
                                    <p class="text-xs text-text-light mt-0.5 line-clamp-1">
                                        {{ $jabatanAsn !== '' ? $jabatanAsn : 'Jabatan / Unit kerja belum diisi' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Footer Card: Jumlah Peserta Dibimbing -->
                            <div class="pt-2.5 border-t border-border/70 flex items-center justify-between gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    {{ $badgeText }}
                                </span>

                                <span class="text-xs font-semibold text-primary opacity-80 group-hover:opacity-100">
                                    Pilih →
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>

                <!-- State Pencarian Kosong -->
                <div id="pembimbing-no-result" class="hidden text-center py-10 bg-surface rounded-xl border border-border">
                    <p class="text-sm font-semibold text-text">Pembimbing tidak ditemukan</p>
                    <p class="text-xs text-text-light mt-1">Coba gunakan kata kunci nama, NIP, atau jabatan yang berbeda.</p>
                </div>
            @endif
        </div>

        <!-- Footer Modal -->
        <div class="px-6 py-3.5 border-t border-border bg-surface flex items-center justify-between gap-3">
            <p class="text-xs text-text-light hidden sm:block">
                * Hitungan mencakup peserta magang berstatus <strong>Aktif</strong> maupun terjadwal <strong>Pending</strong>.
            </p>
            <button type="button" data-close-pembimbing-modal
                class="ml-auto px-4 py-2 bg-background hover:bg-border/50 border border-border text-text text-xs font-semibold rounded-lg transition-colors cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modal-pilih-pembimbing');
        const hiddenInput = document.getElementById('asn_profile_id_input');
        const emptyState = document.getElementById('pembimbing-empty-state');
        const selectedCard = document.getElementById('pembimbing-selected-card');
        const clearBtn = document.getElementById('btn-clear-pembimbing');

        const selAvatar = document.getElementById('selected-pembimbing-avatar');
        const selInisial = document.getElementById('selected-pembimbing-inisial');
        const selNama = document.getElementById('selected-pembimbing-nama');
        const selNip = document.getElementById('selected-pembimbing-nip');
        const selJabatan = document.getElementById('selected-pembimbing-jabatan');
        const selBadge = document.getElementById('selected-pembimbing-badge');

        const searchInput = document.getElementById('search-pembimbing-input');
        const countLabel = document.getElementById('search-pembimbing-count');
        const noResultBox = document.getElementById('pembimbing-no-result');
        const cards = Array.from(document.querySelectorAll('.pembimbing-option-card'));

        if (!modal || !hiddenInput) return;

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            if (searchInput) {
                searchInput.value = '';
                filterCards('');
                setTimeout(() => searchInput.focus(), 50);
            }
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function updateCardHighlight(selectedId) {
            cards.forEach((card) => {
                const isMatch = String(card.dataset.id) === String(selectedId);
                const checkBadge = card.querySelector('.card-check-badge');

                if (isMatch) {
                    card.classList.remove('bg-surface', 'border-border');
                    card.classList.add('bg-primary/5', 'border-primary', 'ring-2', 'ring-primary/25');
                    if (checkBadge) {
                        checkBadge.classList.remove('hidden');
                        checkBadge.classList.add('inline-flex');
                    }
                } else {
                    card.classList.add('bg-surface', 'border-border');
                    card.classList.remove('bg-primary/5', 'border-primary', 'ring-2', 'ring-primary/25');
                    if (checkBadge) {
                        checkBadge.classList.add('hidden');
                        checkBadge.classList.remove('inline-flex');
                    }
                }
            });
        }

        function pilihPembimbing(card) {
            const id = card.dataset.id || '';
            const nama = card.dataset.nama || '-';
            const nip = card.dataset.nip || '-';
            const jabatan = card.dataset.jabatan || '-';
            const jumlah = card.dataset.jumlah || '0';
            const avatar = card.dataset.avatar || '';
            const inisial = card.dataset.inisial || 'A';

            hiddenInput.value = id;
            selNama.textContent = nama;
            selNip.textContent = `NIP: ${nip}`;
            selJabatan.textContent = jabatan;
            selBadge.textContent = `Membimbing: ${jumlah} orang`;

            if (avatar !== '') {
                selAvatar.src = avatar;
                selAvatar.classList.remove('hidden');
                selInisial.classList.add('hidden');
            } else {
                selAvatar.src = '';
                selAvatar.classList.add('hidden');
                selInisial.textContent = inisial;
                selInisial.classList.remove('hidden');
            }

            emptyState.classList.add('hidden');
            emptyState.classList.remove('flex');
            selectedCard.classList.remove('hidden');
            selectedCard.classList.add('flex');

            if (clearBtn) {
                clearBtn.classList.remove('hidden');
                clearBtn.classList.add('inline-flex');
            }

            updateCardHighlight(id);
            closeModal();
        }

        function filterCards(keyword) {
            const term = keyword.trim().toLowerCase();
            let visibleCount = 0;

            cards.forEach((card) => {
                const haystack = card.dataset.search || '';
                const matched = term === '' || haystack.includes(term);
                card.classList.toggle('hidden', !matched);
                if (matched) visibleCount++;
            });

            if (countLabel) {
                countLabel.textContent = `Menampilkan: ${visibleCount} dari ${cards.length} pembimbing`;
            }
            if (noResultBox) {
                noResultBox.classList.toggle('hidden', visibleCount > 0 || cards.length === 0);
            }
        }

        document.querySelectorAll('[data-open-pembimbing-modal]').forEach((btn) => {
            btn.addEventListener('click', openModal);
        });

        document.querySelectorAll('[data-close-pembimbing-modal]').forEach((btn) => {
            btn.addEventListener('click', closeModal);
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        cards.forEach((card) => {
            card.addEventListener('click', function () {
                pilihPembimbing(card);
            });
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                hiddenInput.value = '';
                selectedCard.classList.add('hidden');
                selectedCard.classList.remove('flex');
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
                clearBtn.classList.add('hidden');
                clearBtn.classList.remove('inline-flex');
                updateCardHighlight('');
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                filterCards(this.value);
            });
        }
    });
</script>
