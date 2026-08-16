@extends('layouts.app')

@section('content')
    <main class=" relative w-full flex bg-background">
        {{-- container-sidebar-admin --}}
        @include('components.header-mobile')
        @include('components.admin.sidebar-admin')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 md:pl-60 container-content-mobile">

            {{-- header --}}
            @include('components.admin.header-admin')

            <section class="w-full p-2">
                <section class="container-dalam">
                    {{-- {{ $dataSkill }} --}}
                    @include('components.admin.table-skill')
                </section>
            </section>
        </section>
    </main>
@endsection
