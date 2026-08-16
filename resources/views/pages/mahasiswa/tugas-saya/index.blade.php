@extends('layouts.app')

@section('content')
    <main class=" relative w-full flex bg-background">
        {{-- container-sidebar-admin --}}
        @include('components.header-mobile')
        @include('components.mahasiswa.mahasiswa-sidebar')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 md:pl-60 container-content-mobile">

            {{-- header --}}
            @include('components.mahasiswa.header-mahasiswa')

            <section class="w-full p-2">
                <section class="container-dalam">
                    {{-- {{ $dataTugas }} --}}
                    @include('components.mahasiswa.tugas-saya-filter')
                    @include('components.card-tugas-saya')
                </section>
            </section>
        </section>
    </main>
@endsection
