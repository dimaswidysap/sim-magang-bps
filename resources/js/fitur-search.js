document.addEventListener("DOMContentLoaded", function () {
    // 1. Tangkap elemen-elemen yang dibutuhkan
    const searchInput = document.querySelector(".input-search");
    const dataRows = document.querySelectorAll(".data-row");
    const noDataMessage = document.getElementById("noDataMessage");

    // console.log(searchInput);

    // Pastikan elemen input ada sebelum menjalankan event
    if (searchInput) {
        // 2. Gunakan event "input" dan tangkap event object (e) seperti yang sudah dipelajari
        searchInput.addEventListener("input", function (e) {
            // Ambil teks pencarian dan ubah ke huruf kecil untuk pencarian yang tidak case-sensitive
            const query = e.target.value.toLowerCase();
            let matchCount = 0;

            // 3. Looping ke setiap baris data
            dataRows.forEach(function (row) {
                // Ambil semua teks di dalam baris tersebut (nama, peran, dll)
                const rowText = row.textContent.toLowerCase();

                // 4. Cocokkan teks baris dengan input pencarian
                if (rowText.includes(query)) {
                    // Jika cocok, tampilkan baris (hilangkan class 'hidden' dari Tailwind)
                    row.classList.remove("hidden");
                    matchCount++;
                } else {
                    // Jika tidak cocok, sembunyikan baris
                    row.classList.add("hidden");
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
