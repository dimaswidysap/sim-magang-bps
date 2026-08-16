@extends('layouts.app')

@section('content')
    <main class=" relative w-full flex bg-background">
        {{-- container-sidebar-admin --}}
        @include('components.header-mobile')
        @include('components.asn.asn-sidebar')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 md:pl-60 container-content-mobile">

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
