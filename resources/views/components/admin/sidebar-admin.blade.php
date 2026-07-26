{{-- container luar --}}
<section class="fixed w-60 h-full p-2 ">

    {{-- container dalam --}}

    <section class="h-full w-full container-dalam">

        {{-- conatiner logo bps --}}

        <figure class="w-1/2 aspect-square flex justify-center items-center">
            <img src="{{ asset('images/assets/logo-bps.png') }}" alt="">
        </figure>

        <nav>
            <ul class="font-montserrat ">
                <li class="font-sm">
                    <a href="{{ route('admin-index') }}">Dashboard</a>
                </li>
                <li class="font-sm">
                    <a href="{{ route('admin-mahasiswa') }}">Mahasiswa</a>
                </li>
            </ul>
        </nav>
    </section>

</section>
