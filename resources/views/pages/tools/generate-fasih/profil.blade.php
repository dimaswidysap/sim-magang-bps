<div
    class="relative min-h-screen md:h-screen w-full font-montserrat pt-24 pb-8 px-4 sm:px-6 lg:px-8 flex flex-col justify-between items-center overflow-y-auto md:overflow-hidden bg-background">

    <!-- Ambient Glow Ornaments -->
    <div class="absolute -top-20 -left-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-primary-light/15 rounded-full blur-3xl pointer-events-none">
    </div>
    <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-primary-light/5 rounded-full blur-3xl pointer-events-none">
    </div>

    <div class="max-w-4xl w-full my-auto space-y-6 sm:space-y-8 relative z-10">

        <!-- Header Section -->
        <div class="text-center space-y-2 sm:space-y-3 max-w-2xl mx-auto">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-light/10 border border-primary/20 text-primary text-[11px] font-bold tracking-wide">
                <span>Code Generator Dokumen Fasih</span>
            </div>

            <h1 class="text-2xl sm:text-4xl font-extrabold text-text tracking-tight leading-tight">
                Alur Kerja <span
                    class="bg-gradient-to-r from-primary to-primary-dark bg-clip-text text-transparent">Generator
                    Dokumen</span>
            </h1>

            <p class="text-xs sm:text-sm text-text-light leading-relaxed">
                4 langkah mudah otomatisasi pembuatan laporan Word dan pencocokan foto sensus secara presisi.
            </p>
        </div>

        <!-- 2x2 Responsive Step Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">

            <!-- Step 1 -->
            <div
                class="group bg-surface border border-border/80 hover:border-primary/50 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-md transition-all duration-300 flex items-start gap-3.5">
                <div
                    class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-primary-light/15 border border-primary/20 text-primary font-bold text-xs sm:text-sm flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-surface transition-colors duration-300">
                    01
                </div>
                <div class="space-y-0.5">
                    <h3 class="font-bold text-text text-xs sm:text-sm group-hover:text-primary transition-colors">Upload
                        Data Excel</h3>
                    <p class="text-[11px] sm:text-xs text-text-light leading-relaxed">
                        Unggah file Excel sumber data sensus untuk membaca struktur header kolom.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div
                class="group bg-surface border border-border/80 hover:border-primary/50 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-md transition-all duration-300 flex items-start gap-3.5">
                <div
                    class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-primary-light/15 border border-primary/20 text-primary font-bold text-xs sm:text-sm flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-surface transition-colors duration-300">
                    02
                </div>
                <div class="space-y-0.5">
                    <h3 class="font-bold text-text text-xs sm:text-sm group-hover:text-primary transition-colors">Atur
                        Kop & Header</h3>
                    <p class="text-[11px] sm:text-xs text-text-light leading-relaxed">
                        Tentukan baris teks judul/kop yang ditampilkan di bagian atas laporan.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div
                class="group bg-surface border border-border/80 hover:border-primary/50 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-md transition-all duration-300 flex items-start gap-3.5">
                <div
                    class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-primary-light/15 border border-primary/20 text-primary font-bold text-xs sm:text-sm flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-surface transition-colors duration-300">
                    03
                </div>
                <div class="space-y-0.5">
                    <h3 class="font-bold text-text text-xs sm:text-sm group-hover:text-primary transition-colors">
                        Mapping Field & Foto</h3>
                    <p class="text-[11px] sm:text-xs text-text-light leading-relaxed">
                        Pasangkan atribut data serta tentukan kolom acuan pencocokan folder.
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div
                class="group bg-surface border border-border/80 hover:border-primary/50 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-md transition-all duration-300 flex items-start gap-3.5">
                <div
                    class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-primary-light/15 border border-primary/20 text-primary font-bold text-xs sm:text-sm flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-surface transition-colors duration-300">
                    04
                </div>
                <div class="space-y-0.5">
                    <h3 class="font-bold text-text text-xs sm:text-sm group-hover:text-primary transition-colors">Unduh
                        Script Python</h3>
                    <p class="text-[11px] sm:text-xs text-text-light leading-relaxed">
                        Dapatkan <code
                            class="px-1 py-0.2 rounded bg-background border border-border text-[10px] text-primary font-mono">script.py</code>
                        yang siap dijalankan secara otomatis.
                    </p>
                </div>
            </div>

        </div>

        <!-- Action CTA Button -->
        <div class="text-center pt-2">
            <a href="{{ route('generate.form') }}" class="inline-block group">
                <button type="button"
                    class="inline-flex items-center justify-center gap-3 px-8 py-3.5 bg-primary hover:bg-primary-dark text-surface font-bold text-xs sm:text-sm rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/35 transition-all duration-300 transform group-hover:-translate-y-0.5 cursor-pointer active:scale-95">
                    <span>Mulai Pembuatan Sekarang</span>
                    <svg class="w-4 h-4 fill-current transition-transform duration-300 group-hover:translate-x-1"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </a>
        </div>

    </div>

    <!-- Minimalist Footer Note -->
    <div class="text-[11px] text-text-light relative z-10 text-center py-2">
        Otomatisasi Laporan Sensus &copy; {{ date('Y') }}
    </div>
</div>
