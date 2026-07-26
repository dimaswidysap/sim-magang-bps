{{-- container luar --}}
<header class="w-full flex p-2">

    <section class="w-full h-full container-dalam">
        <div class="flex gap-2 items-center">
            <figure class="h-12 aspect-square rounded-xl bg-green-300"></figure>
            <p class="font-montserrat font-semibold text-text">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
    </section>

</header>
