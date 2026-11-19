@extends('layouts.app')

@section('content')
    <main class="relative w-full min-h-screen flex bg-background overflow-x-hidden">


        <section class="flex flex-col flex-1 min-w-0  container-content-mobile">


            <section class="w-full p-2">
                <section class="container-dalam max-w-3xl mx-auto flex flex-col gap-4 font-montserrat">

                    <div>
                        <a href="{{ route('mahasiswa.pengajuan-libur.index') }}"
                            class="inline-flex items-center gap-2 text-xs font-bold text-text-light hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali ke Daftar Pengajuan Libur
                        </a>
                    </div>

                    <div class="bg-surface border border-border rounded-2xl p-6 md:p-8 shadow-xs space-y-6">
                        <div class="border-b border-border pb-4">
                            <h1 class="text-xl font-bold text-text">Edit Pengajuan Surat Libur</h1>
                            <p class="text-xs text-text-light mt-1">
                                Perbarui data pengajuan surat libur sebelum diproses oleh pembimbing ASN.
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="p-4 rounded-xl bg-danger/10 border border-danger/30 text-danger text-xs">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('mahasiswa.pengajuan-libur.update', $pengajuan->id) }}"
                            enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <!-- Rentang Tanggal Libur -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="tanggal_mulai" class="block text-xs font-bold text-text mb-1.5">
                                        Tanggal Mulai Libur <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                        value="{{ old('tanggal_mulai', $pengajuan->tanggal_mulai?->format('Y-m-d')) }}"
                                        required
                                        class="w-full h-10 px-3.5 bg-background border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                                </div>

                                <div>
                                    <label for="tanggal_selesai" class="block text-xs font-bold text-text mb-1.5">
                                        Tanggal Selesai Libur <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                        value="{{ old('tanggal_selesai', $pengajuan->tanggal_selesai?->format('Y-m-d')) }}"
                                        required
                                        class="w-full h-10 px-3.5 bg-background border border-border rounded-lg text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                                </div>
                            </div>

                            <!-- Alasan Libur -->
                            <div>
                                <label for="alasan" class="block text-xs font-bold text-text mb-1.5">
                                    Alasan Pengajuan Libur <span class="text-danger">*</span>
                                </label>
                                <textarea id="alasan" name="alasan" rows="4" required
                                    class="w-full p-3.5 bg-background border border-border rounded-xl text-sm text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">{{ old('alasan', $pengajuan->alasan) }}</textarea>
                            </div>

                            <!-- File Lampiran Saat Ini & Ganti File -->
                            <div class="space-y-2.5">
                                <label for="file_lampiran" class="block text-xs font-bold text-text">
                                    Ganti Lampiran Surat / Bukti (Opsional)
                                </label>

                                <div class="p-3 bg-background border border-border rounded-xl flex items-center justify-between gap-3 text-xs">
                                    <span class="text-text-light truncate">
                                        File saat ini: <strong class="text-text">{{ $pengajuan->nama_file_asli ?? basename($pengajuan->file_lampiran) }}</strong>
                                    </span>
                                    <a href="{{ asset('storage/' . $pengajuan->file_lampiran) }}" target="_blank"
                                        class="shrink-0 font-bold text-primary hover:underline">
                                        Lihat File
                                    </a>
                                </div>

                                <input type="file" id="file_lampiran" name="file_lampiran"
                                    accept="image/jpeg,image/png,image/webp,image/gif,image/bmp,image/tiff,image/avif,image/heic,image/heif,.pdf,.doc,.docx"
                                    class="w-full p-2.5 bg-background border border-border rounded-xl text-xs text-text file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                                <p class="text-[11px] text-text-light">
                                    Kosongkan jika tidak ingin mengubah file lampiran. Format yang diterima: gambar (<strong>kecuali SVG</strong>), <strong>PDF</strong>, dan <strong>Word (.doc / .docx)</strong> maksimal 5 MB.
                                </p>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                                <a href="{{ route('mahasiswa.pengajuan-libur.index') }}"
                                    class="px-4 py-2.5 rounded-lg border border-border bg-background text-xs font-semibold text-text-light hover:text-text transition-colors">
                                    Batal
                                </a>

                                <button type="submit"
                                    class="px-5 py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-white text-xs font-bold transition-colors cursor-pointer shadow-xs">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                </section>
            </section>
        </section>
    </main>

    <script>
        const inputMulai = document.getElementById('tanggal_mulai');
        const inputSelesai = document.getElementById('tanggal_selesai');

        inputMulai?.addEventListener('change', () => {
            if (inputMulai.value) {
                inputSelesai.min = inputMulai.value;
                if (inputSelesai.value && inputSelesai.value < inputMulai.value) {
                    inputSelesai.value = inputMulai.value;
                }
            }
        });
    </script>
@endsection
