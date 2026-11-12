
<!-- Navigation Tabs -->
<div class="mb-6 border-b border-border overflow-x-auto scrollbar-none">
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
</div>
