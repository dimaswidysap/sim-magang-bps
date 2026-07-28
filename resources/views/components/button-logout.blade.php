<li>
    <!-- Tambahkan onsubmit="return confirm(...)" di sini -->
    <form method="POST" action="{{ route('logout') }}" class="w-full m-0" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
        @csrf
        <button type="submit"
            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 text-text-light hover:bg-background hover:text-danger cursor-pointer">

            <!-- Ikon Logout -->
            <div class="relative z-10 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <span class="relative z-10 text-left w-full">Logout</span>
        </button>
    </form>
</li>
