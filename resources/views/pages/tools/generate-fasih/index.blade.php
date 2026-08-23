@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 font-montserrat my-6 sm:my-10">
        <!-- Section Title -->
        <div class="mb-5 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-text">Langkah 1: Upload File Excel</h1>
            <p class="text-xs sm:text-sm text-text-light mt-1 leading-relaxed">Unggah berkas Excel untuk membaca header kolom
                dan mengekstrak data.</p>
        </div>

        <!-- Alert List Errors -->
        @if ($errors->any())
            <div
                class="p-3.5 sm:p-4 mb-5 sm:mb-6 text-xs sm:text-sm text-danger bg-danger/10 border border-danger/20 rounded-xl sm:rounded-2xl">
                <div class="flex items-center gap-2 font-semibold mb-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 fill-current" viewBox="0 0 20 20">
                        <path
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                    </svg>
                    <span>Terdapat kesalahan pada berkas yang diunggah:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Form Container -->
        <div class="bg-surface border border-border rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-xs">
            <form action="{{ route('generate.uploadExcel') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5 sm:space-y-6">
                @csrf

                <!-- Field Input Excel -->
                <div>
                    <label for="excel_file" class="block text-xs font-bold text-text-light uppercase tracking-wider mb-2">
                        Pilih File Excel <span class="normal-case text-text-light/80 font-normal">(.xlsx atau .xls)</span>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls" required
                        class="w-full text-xs sm:text-sm text-text-light border border-border rounded-xl cursor-pointer bg-surface file:mr-2 sm:file:mr-4 file:py-2.5 file:px-3 sm:file:px-4 file:rounded-lg sm:file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light/20 file:text-primary hover:file:bg-primary-light/30 file:cursor-pointer file:transition">
                </div>

                <!-- Tombol Aksi (Full width di HP, Auto alignment di Tablet/Desktop) -->
                <div class="pt-2 flex flex-col gap-4 sm:flex-row items-stretch sm:items-center sm:justify-end">
                    <x-buttonv2 href="{{ route('tools-index') }}" color="primary" class="w-full sm:w-auto">
                        Kembali
                    </x-buttonv2>

                    <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                            <path
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" />
                        </svg>
                        Upload & Baca Header
                    </x-buttonv2>
                </div>
            </form>
        </div>
    </div>
@endsection
