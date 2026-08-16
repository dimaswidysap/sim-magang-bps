@extends('layouts.app')

@section('content')
    <main class=" relative w-full flex bg-background">
        {{-- container-sidebar-admin --}}

        @include('components.asn.asn-sidebar')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 pl-60">

            {{-- header --}}
            @include('components.asn.header-asn')

            <section class="w-full p-2">
                <section class="container-dalam">
                    @include('components.submission-card')
                </section>
            </section>
        </section>
        </section>
        </section>
    </main>
@endsection
