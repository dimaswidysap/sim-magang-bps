@vite(['resources/js/fitur-search.js'])
@extends('layouts.app')

@section('content')
    <main class="relative w-full min-h-screen flex bg-background overflow-x-hidden">
        {{-- container-sidebar-admin --}}
        @include('components.header-mobile')
        @include('components.admin.sidebar-admin')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 min-w-0 md:pl-60 container-content-mobile">

            {{-- header --}}
            @include('components.admin.header-admin')

            <section class="w-full p-2">
                <section class="container-dalam flex flex-col gap-4 font-montserrat">
                    @include('components.admin.table-mahasiswa')
                </section>
            </section>
        </section>
    </main>
@endsection
