{{-- container luar --}}
<header class="w-full flex p-2">

    <section class="w-full h-full container-dalam">
        <div class="flex gap-2 items-center">
            <figure class="h-12 aspect-square rounded-xl bg-primary"></figure>
            <p class="font-montserrat font-semibold text-text">{{ auth()->user()->name }}</p>
        </div>
    </section>

</header>
