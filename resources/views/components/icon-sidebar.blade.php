<section class="w-full flex items-center justify-center gap-3 md:gap-4 p-2 font-montserrat select-none group">

    <!-- Logo BPS dengan efek drop-shadow agar lebih menonjol -->
    <figure
        class="w-12 h-12 md:w-14 md:h-14 flex justify-center items-center shrink-0 transition-transform duration-300 group-hover:scale-105">
        <img src="{{ asset('images/assets/logo-bps.png') }}" alt="Logo Badan Pusat Statistik"
            class="w-full h-full object-contain drop-shadow-md">
    </figure>

    <!-- Garis Pemisah (Separator) Transparan -->
    <div class="h-10 md:h-12 w-px bg-white/20 rounded-full mx-1"></div>

    <!-- Teks SOBAT MAGANG dengan Hierarki Tipografi -->
    <div class="flex flex-col justify-center">
        <!-- Kata Pertama: Tebal dan Tegas -->
        <strong class="font-extrabold text-lg md:text-xl text-white tracking-widest leading-none drop-shadow-sm mb-1.5">
            SOBAT
        </strong>
        <!-- Kata Kedua: Lebih kecil, warna aksen (kuning/oranye BPS), dan jarak huruf renggang -->
        <span
            class="font-semibold text-[10px] md:text-xs text-accent uppercase tracking-[0.25em] leading-none drop-shadow-sm">
            Magang
        </span>
    </div>

</section>
