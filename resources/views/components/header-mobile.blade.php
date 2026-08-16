<!-- Mobile Header (Hanya tampil di layar kecil < md) -->
<header
    class="fixed top-0 left-0 w-full h-16 z-10 bg-surface border-b border-border flex md:hidden items-center justify-between px-4  font-montserrat shadow-sm">

    <!-- Bagian Kiri: Tombol Hamburger & Judul -->
    <div class="flex items-center gap-3">

        <!-- Tombol Hamburger Menu -->
        <!-- Catatan: Tambahkan event listener JS pada ID ini untuk memunculkan sidebar -->
        <button id="btn-toggle-sidebar" type="button"
            class="p-2 -ml-2 text-text-light hover:text-primary hover:bg-primary/10 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Judul Aplikasi / Halaman -->
        <div class="flex items-center gap-2">
            <!-- Kotak aksen kecil untuk identitas visual -->
            <div class="w-1.5 h-4 bg-primary rounded-full"></div>
            <span class="font-bold text-text text-sm uppercase tracking-widest">
                SIM Magang
            </span>
        </div>

    </div>
</header>


