<!-- Navigation Tabs -->
<div class="flex items-center justify-between mb-6 border-b border-border overflow-x-auto scrollbar-none">
    <nav class="flex space-x-2 md:space-x-4 min-w-max pb-2">

        <a href="{{ route('magang-aktif') }}"
            class="px-4 py-2 text-xs md:text-sm font-semibold rounded-lg transition-all
            {{ request()->routeIs('magang-aktif') ? 'bg-primary text-white shadow-sm' : 'text-text-light hover:text-text hover:bg-background' }}">
            Aktif
        </a>

        <a href="{{ route('magang-pending') }}"
            class="px-4 py-2 text-xs md:text-sm font-semibold rounded-lg transition-all
            {{ request()->routeIs('magang-pending') ? 'bg-primary text-white shadow-sm' : 'text-text-light hover:text-text hover:bg-background' }}">
            Pending
        </a>

        <a href="{{ route('magang-selesai') }}"
            class="px-4 py-2 text-xs md:text-sm font-semibold rounded-lg transition-all
            {{ request()->routeIs('magang-selesai') ? 'bg-primary text-white shadow-sm' : 'text-text-light hover:text-text hover:bg-background' }}">
            Selesai
        </a>

        <a href="{{ route('magang-batal') }}"
            class="px-4 py-2 text-xs md:text-sm font-semibold rounded-lg transition-all
            {{ request()->routeIs('magang-batal') ? 'bg-primary text-white shadow-sm' : 'text-text-light hover:text-text hover:bg-background' }}">
            Batal
        </a>

    </nav>
    <div class="mb-5 flex justify-start">
        <a href="{{ route('admin-index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-surface border border-border rounded-lg text-xs font-semibold text-text hover:bg-black/5 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>
</div>
