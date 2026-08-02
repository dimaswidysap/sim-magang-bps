@extends('layouts.app')

@section('content')
    <main class=" relative w-full flex bg-background">
        @include('components.admin.sidebar-admin')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 pl-60">

            {{-- header --}}
            @include('components.admin.header-admin')

            @include('components.admin.table-mahasiswa')
        </section>
    </main>
@endsection
