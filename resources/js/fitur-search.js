document.addEventListener("DOMContentLoaded", function () {
    // 1. Tangkap elemen-elemen yang dibutuhkan
    const searchInput = document.querySelector(".input-search");
    const dataRows = document.querySelectorAll(".data-row");
    const noDataMessage = document.getElementById("noDataMessage");

    // Pastikan elemen input ada sebelum menjalankan event
    if (searchInput) {
        // 2. Gunakan event "input" dan tangkap event object (e)
        searchInput.addEventListener("input", function (e) {
            // Ambil teks pencarian dan ubah ke huruf kecil
            const query = e.target.value.toLowerCase();
            let matchCount = 0;

            // 3. Looping ke setiap baris data
            dataRows.forEach(function (row) {
                // Ambil semua teks di dalam baris tersebut
                const rowText = row.textContent.toLowerCase();

                // 4. Cocokkan teks baris dengan input pencarian
                if (rowText.includes(query)) {
                    // JIKA COCOK: Hapus inline style display agar kembali ke class Tailwind bawaannya (block / md:table-row)
                    row.style.display = "";
                    matchCount++;
                } else {
                    // JIKA TIDAK COCOK: Paksa sembunyikan dengan inline style (mengalahkan md:table-row)
                    row.style.display = "none";
                }
            });

            // 5. Tampilkan pesan "Tidak ditemukan" jika tidak ada baris yang cocok
            if (matchCount === 0) {
                noDataMessage.classList.remove("hidden");
            } else {
                noDataMessage.classList.add("hidden");
            }
        });
    }
});
