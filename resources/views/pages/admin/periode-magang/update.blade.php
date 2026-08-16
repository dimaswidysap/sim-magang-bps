@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="container-dalam max-w-3xl mx-auto bg-surface rounded-2xl shadow-sm border border-border p-6 md:p-10">

            <!-- Header Halaman -->
            <div class="mb-8 pb-4 border-b border-border flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-bold text-text">Edit Periode Magang</h1>
                    <p class="text-sm text-text-light mt-1">Perbarui rentang waktu, kuota, atau status gelombang magang.</p>
                </div>
                <!-- Badge ID -->
                <div>
                    <span
                        class="px-4 py-1.5 bg-background border border-border text-text-light text-sm font-semibold rounded-full">
                        ID: {{ $periode->id }}
                    </span>
                </div>
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

            <form method="POST" action="{{ route('admin-periode-update', $periode->id) }}" data-confirm="Apakah anda yakin ingin menerapkan perubahan?">
                @csrf
                @method('PUT')

                <!-- Form Inputs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border mb-8">

                    <!-- Nama Periode (Full Width) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-text-light mb-1.5">Nama Periode</label>
                        <input type="text" name="nama_periode" value="{{ old('nama_periode', $periode->nama_periode) }}"
                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-text-light mb-1.5">Tanggal Mulai</label>
                        <!-- Menggunakan optional() agar tidak error jika nilai tanggal kosong/null di database -->
                        <input type="date" name="tanggal_mulai"
                            value="{{ old('tanggal_mulai', optional($periode->tanggal_mulai)->format('Y-m-d')) }}"
                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label class="block text-sm font-medium text-text-light mb-1.5">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai"
                            value="{{ old('tanggal_selesai', optional($periode->tanggal_selesai)->format('Y-m-d')) }}"
                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                    </div>

                    <!-- Kuota -->
                    <div>
                        <label class="block text-sm font-medium text-text-light mb-1.5">Kuota (Jumlah Mahasiswa)</label>
                        <input type="number" name="kuota" min="1" value="{{ old('kuota', $periode->kuota) }}"
                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-text-light mb-1.5">Status</label>
                        @php $statusLama = old('status', $periode->status); @endphp
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
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors resize-y">{{ old('keterangan', $periode->keterangan) }}</textarea>
                    </div>

                </div>

                <hr class="border-border mb-6">

                <!-- Footer Buttons -->
                <div class="flex justify-end items-center gap-2">

                    <!-- Tombol Batal -->
                    <x-main-button href="{{ url()->previous() }}"
                        class="bg-background text-text border border-border hover:bg-surface text-xs px-4 py-2 rounded-lg transition-colors shadow-sm inline-flex items-center gap-2">
                        <span>Batal</span>
                    </x-main-button>

                    <!-- Tombol Simpan -->
                    <x-main-button
                        class="bg-primary text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                        type="submit">
                        <!-- Ikon Edit / Save -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Simpan Perubahan</span>
                    </x-main-button>

                </div>
            </form>
        </section>
    </main>
@endsection
