document.addEventListener("DOMContentLoaded", () => {
    const btnToggle = document.getElementById("btn-toggle-sidebar");
    const btnClose = document.getElementById("btn-close-sidebar");

    // Pastikan elemen wrapper sidebar utama kamu memiliki class 'sidebar-mobile'
    const sidebar = document.querySelector(".sidebar-mobile");

    // Mencegah error jika elemen tidak ditemukan di halaman
    if (!sidebar) return;

    // Fungsi untuk memunculkan sidebar
    const openSidebar = () => {
        // Menggunakan class utilitas Tailwind '!translate-x-0' untuk meng-override
        // nilai -translate-x-[120%] yang ada di CSS bawaan
        sidebar.classList.add("!translate-x-0");
    };

    // Fungsi untuk menyembunyikan sidebar
    const closeSidebar = () => {
        sidebar.classList.remove("!translate-x-0");
    };

    // 1. Event untuk tombol hamburger (Buka Sidebar)
    if (btnToggle) {
        btnToggle.addEventListener("click", (e) => {
            e.stopPropagation(); // Mencegah event bubbling agar tidak langsung memicu 'click outside'
            openSidebar();
        });
    }

    // 2. Event untuk tombol X (Tutup Sidebar)
    if (btnClose) {
        btnClose.addEventListener("click", closeSidebar);
    }

    // 3. Logika untuk menutup sidebar ketika klik di luar container
    document.addEventListener("click", (e) => {
        // Cek apakah sidebar sedang dalam kondisi terbuka
        const isOpen = sidebar.classList.contains("!translate-x-0");

        if (isOpen) {
            // Memeriksa apakah area yang di-klik berada di dalam sidebar atau tombol toggle
            const isClickInsideSidebar = sidebar.contains(e.target);
            const isClickOnToggleBtn = btnToggle
                ? btnToggle.contains(e.target)
                : false;

            // Jika klik terjadi di luar elemen-elemen tersebut, tutup sidebar
            if (!isClickInsideSidebar && !isClickOnToggleBtn) {
                closeSidebar();
            }
        }
    });
});
