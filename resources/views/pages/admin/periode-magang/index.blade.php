@extends('layouts.app')

@section('content')

<main class=" relative w-full flex bg-background">
{{-- container-sidebar-admin --}}

@include('components.admin.sidebar-admin')

    {{-- container-content --}}
    <section class="flex flex-col flex-1 pl-60">

        {{-- header --}}
        @include('components.admin.header-admin')

        <section class="w-full p-2">
            <section class="container-dalam">
                {{-- {{ $periodeMagang }} --}}

                @include('components.admin.table-periode-magang')
            </section>
        </section>
    </section>
</main>

@endsection
