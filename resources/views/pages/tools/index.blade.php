@extends('layouts.app')

@section('content')
   <section class="w-full flex flex-col pb-20">
     <nav class="fixed top-0 left-0 right-0 z-50 bg-surface/80 backdrop-blur-md border-b border-border/60 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            @php
                $rutePulang = match (auth()->user()->role) {
                    'admin' => 'admin-index',
                    'asn' => 'asn-index',
                    'mahasiswa' => 'mahasiswa-index',
                    default => 'landing-page', // jaga-jaga kalau role tidak dikenali
                };
            @endphp

            <!-- Tombol Kembali -->


            <x-buttonv2 href="{{ route($rutePulang) }}" color="primary" class="w-full sm:w-auto">
                Kembali
            </x-buttonv2>




            <!-- Status Badge / Title -->
            <div class="flex items-center gap-2">
                <span class="hidden sm:inline-block w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-text-light hidden md:flex">Tools</span>
            </div>
        </div>
    </nav>

    @include('pages.tools.generate-fasih.profil')
    {{-- @include('pages.tools.generate-fasih.profil') --}}
   </section>
@endsection
