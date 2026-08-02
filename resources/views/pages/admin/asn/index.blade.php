@extends('layouts.app')

@section('content')
    <main class="relative w-full flex">
        @include('components.admin.sidebar-admin')

        <section class="flex flex-col flex-1 pl-60">
            @include('components.admin.header-admin')

            <section class="w-full p-2">
                <section class="container-dalam">

                    {{-- {{ $dataAsn }} --}}
                    @include('components.admin.table-asn')
                </section>
            </section>
        </section>
    </main>
@endsection
