<!-- Custom Confirm Modal -->
<div id="custom-confirm-modal"
    class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none font-montserrat px-4">

    <!-- Modal Content -->
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform scale-95 transition-transform duration-300 overflow-hidden"
        id="modal-content">

        <!-- Body / Top Section -->
        <div class="p-6 md:p-8 flex items-start gap-5">

            <!-- Icon Warning (Lingkaran Oranye Muda) -->
            <div
                class="w-14 h-14 rounded-full bg-orange-50 border border-orange-100 text-amber-500 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <div class="flex-1 pt-1">
                <h3 class="text-xl font-bold text-gray-800 mb-3">Pengingat</h3>

                <!-- Pesan Dinamis (Struktur default diisi sesuai referensi gambar) -->
                <div id="confirm-modal-message" class="text-sm text-gray-600 space-y-3 leading-relaxed">
                    <p>
                        Mahasiswa berikut <span class="font-bold text-gray-700">masih memiliki tugas yang belum
                            selesai</span>:
                    </p>
                    <p class="font-bold text-red-600 text-base">
                        Dimas Widy Saputra
                    </p>
                    <p>
                        Apakah Anda yakin ingin memberikan tugas tambahan ini?
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer / Action Buttons (Background Abu-abu terang) -->
        <div class="bg-gray-50 border-t border-gray-200 px-6 py-5 flex items-center justify-end gap-3">
            <button id="btn-cancel-confirm" type="button"
                class="px-6 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer shadow-sm">
                Batal
            </button>

            <button id="btn-continue-confirm" type="button"
                class="px-6 py-2.5 text-sm font-semibold text-white bg-[#E53E3E] hover:bg-red-700 border border-transparent rounded-lg transition-colors cursor-pointer shadow-sm">
                Tetap Lanjutkan
            </button>
        </div>

    </div>
</div>
