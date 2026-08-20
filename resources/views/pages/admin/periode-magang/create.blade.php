@extends('layouts.app')
@vite(['resources/js/validasi-number.js'])
@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-3xl mx-auto bg-surface rounded-2xl shadow-sm border border-border p-6 md:p-10">

            <!-- Header Halaman -->
            <div class="mb-8 pb-4 border-b border-border">
                <h1 class="text-2xl font-bold text-text">Tambah Periode Magang</h1>
                <p class="text-sm text-text-light mt-1">Tentukan rentang waktu, kuota, dan status untuk gelombang magang
                    baru.</p>
            </div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-surface border border-danger rounded-lg shadow-sm">
                    <div class="flex items-center gap-2 text-danger font-semibold mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Terdapat kesalahan pada input Anda:
                    </div>
                    <ul class="list-disc list-inside text-sm text-danger ml-2 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin-periode-store') }}"
                data-confirm="Apakah anda yakin ingin menambah data?">
                @csrf

                <!-- Form Inputs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border mb-8">

                    <!-- Nama Periode (Full Width) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-text-light mb-1.5">Nama Periode</label>
                        <input type="text" name="nama_periode" value="{{ old('nama_periode') }}"
                            placeholder="Contoh: Periode Januari - Maret 2027"
                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-text-light mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label class="block text-sm font-medium text-text-light mb-1.5">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                    </div>

                    <!-- Kuota -->
                    <div>
                        <label class="block text-sm font-medium text-text-light mb-1.5">Kuota (Jumlah Mahasiswa)</label>
                        <input type="text" name="kuota" min="1" value="{{ old('kuota') }}"
                            placeholder="Contoh: 20"
                            class="hanya-angka w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-text-light mb-1.5">Status Awal</label>
                        @php $statusLama = old('status', 'akan_datang'); @endphp
                        <select name="status"
                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                            <option value="akan_datang" {{ $statusLama == 'akan_datang' ? 'selected' : '' }}>Akan Datang
                            </option>
                            <option value="berlangsung" {{ $statusLama == 'berlangsung' ? 'selected' : '' }}>Berlangsung
                            </option>
                            <option value="selesai" {{ $statusLama == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <!-- Keterangan (Full Width) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-text-light mb-1.5">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3" placeholder="Tambahkan catatan khusus untuk periode ini jika diperlukan..."
                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors resize-y">{{ old('keterangan') }}</textarea>
                    </div>

                </div>

                <hr class="border-border mb-6">

                <!-- Footer Buttons -->
                <div class="flex justify-end items-center gap-2">

                    <!-- Tombol Batal -->



                    <x-buttonv2 href="{{ route('admin-periode-magang') }}" color="primary" class="w-full sm:w-auto">

                        Batal
                    </x-buttonv2>



                    <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Periode
                    </x-buttonv2>

                </div>
            </form>
        </section>
    </main>
@endsection
