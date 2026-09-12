<div id="modalLampiran"
    class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[85vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 id="modalTitle" class="text-sm font-semibold text-gray-800">Lampiran Gambar</h3>
            <button type="button" onclick="closeModalLampiran()"
                class="inline-flex items-center justify-center w-8 h-8 text-danger cursor-pointer transition-colors duration-200 rounded-full hover:bg-red-100 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="modalContent" class="p-5 overflow-y-auto">
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalLampiran');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');

        document.querySelectorAll('.btn-lihat-lampiran').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                // Set judul modal menjadi statis
                modalTitle.innerText = 'Lampiran Gambar';

                modalContent.innerHTML = `
                <div class="text-center py-8 text-gray-500 text-xs">
                    Sedang mengunduh gambar lampiran...
                </div>
            `;
                modal.classList.remove('hidden');

                // Fetch ke endpoint API
                fetch(`/asn/logbook/${id}/lampiran`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.success && res.data.length > 0) {
                            let gridHtml =
                                '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">';
                            res.data.forEach(url => {
                                gridHtml += `
                                <a href="${url}" target="_blank" class="group block border border-gray-100 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                                    <img src="${url}" class="w-full h-44 object-cover group-hover:scale-105 transition duration-200" alt="Lampiran">
                                </a>
                            `;
                            });
                            gridHtml += '</div>';

                            modalContent.innerHTML = gridHtml;
                        } else {
                            modalContent.innerHTML =
                                `<div class="text-center py-8 text-gray-400 text-xs">Tidak ada gambar lampiran pada logbook ini.</div>`;
                        }
                    })
                    .catch(() => {
                        modalContent.innerHTML =
                            `<div class="text-center py-8 text-red-500 text-xs">Gagal mengambil lampiran. Silakan coba lagi.</div>`;
                    });
            });
        });
    });

    function closeModalLampiran() {
        document.getElementById('modalLampiran').classList.add('hidden');
    }
</script>
