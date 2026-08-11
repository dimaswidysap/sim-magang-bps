<!-- Alert Modal Overlay -->
<section id="modal-alert" class="hidden fixed inset-0 h-screen w-full z-50 items-center justify-center bg-black/50 backdrop-blur-sm px-4 font-montserrat">
    <!-- Modal Card -->
    <div class="w-full max-w-md bg-surface rounded-2xl shadow-2xl border border-border overflow-hidden">
        <!-- Konten Modal -->
        <div class="p-6 md:p-8">
            <div class="flex items-start gap-4">
                <!-- Icon Warning -->
                <div class="w-12 h-12 rounded-full bg-warning/10 text-warning flex items-center justify-center shrink-0 border border-warning/20 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <!-- Pesan Error -->
                <div>
                    <h3 class="text-lg font-bold text-text">Peringatan Tugas Aktif!</h3>
                    <p class="text-sm text-text-light mt-2 leading-relaxed">
                        Mahasiswa berikut <strong>masih memiliki tugas yang belum selesai</strong>:
                    </p>
                    <!-- FIX: elemen baru untuk menampung nama, diisi JS -->
                    <p class="text-sm font-semibold text-danger mt-1" id="nama-mahasiswa-aktif"></p>
                    <p class="text-sm text-text-light mt-2 leading-relaxed">
                        Apakah Anda yakin ingin memberikan tugas tambahan ini?
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer / Tombol Aksi -->
        <div class="bg-background border-t border-border p-5 flex items-center justify-end gap-3">
            <!-- Tombol Abort (Batal) -->
            <button
                type="button"
                id="btn-batal"
                class="bg-surface text-text border border-border hover:bg-gray-100 text-xs px-5 py-2.5 rounded-lg transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                <span>Batal</span>
            </button>

            <!-- Tombol Continue (Lanjutkan) -->
            <button
                type="button"
                id="btn-lanjutkan"
                class="bg-danger hover:bg-red-700 text-xs px-5 py-2.5 rounded-lg text-white transition-colors shadow-sm inline-flex justify-center items-center gap-2">
                <span>Tetap Lanjutkan</span>
            </button>
        </div>
    </div>
</section>
