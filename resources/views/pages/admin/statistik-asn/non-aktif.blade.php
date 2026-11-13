@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-6xl mx-auto space-y-6">

            <!-- Header & Navigasi -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <h1 class="text-2xl font-bold text-text leading-snug">Daftar ASN Nonaktif</h1>
                    <p class="text-sm text-text-light mt-1">Daftar pengguna ASN yang saat ini dalam status nonaktif.</p>
                </div>


            </div>

            @include('pages.admin.statistik-asn.navigasi')

            @include('pages.admin.statistik-asn.tabel-component', [
                'items' => $asnNonAktif,
                'emptyMessage' => 'Tidak ada data ASN non-aktif yang ditemukan.',
            ])

        </section>
    </main>
@endsection
