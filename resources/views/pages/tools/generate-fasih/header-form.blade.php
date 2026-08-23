@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 font-montserrat my-6 sm:my-10">
        <!-- Section Title -->
        <div class="mb-5 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-text">Langkah 3: Pengaturan Header Dokumen</h1>
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
                    <span>Terdapat kesalahan pada input Anda:</span>
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
            <form action="{{ route('generate.storeHeader') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Dynamic Header Lines Container -->
                <div id="header-lines-container" class="space-y-3">
                    @foreach ($lines as $index => $line)
                        <div
                            class="header-line-row flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 p-3 bg-background border border-border rounded-xl transition">
                            <span class="text-xs font-bold text-text-light uppercase tracking-wider shrink-0 w-20">
                                Baris {{ $index + 1 }}:
                            </span>

                            <input type="text" name="header_lines[]" value="{{ $line }}"
                                placeholder="Masukkan teks baris header..." required
                                class="w-full flex-1 px-4 py-2.5 rounded-xl border border-border text-text bg-surface text-xs sm:text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200">

                            <button type="button" onclick="removeLine(this)"
                                class="w-full sm:w-auto px-3.5 py-2.5 bg-danger/10 hover:bg-danger text-danger hover:text-surface text-xs font-semibold rounded-xl transition duration-200 cursor-pointer flex items-center justify-center gap-1 shrink-0 active:scale-95">
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    @endforeach
                </div>

                <!-- Tombol Tambah Baris -->
                <div>
                    <button type="button" onclick="addLine()"
                        class="w-full py-3 border-2 border-dashed border-border hover:border-primary text-text-light hover:text-primary font-semibold text-xs sm:text-sm rounded-xl transition duration-200 flex items-center justify-center gap-2 cursor-pointer bg-surface/50 hover:bg-primary-light/10 active:scale-98">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Tambah Baris Header</span>
                    </button>
                </div>

                <!-- Tombol Submit -->
                <div
                    class="pt-4 border-t gap-4 border-border/60 flex flex-col sm:flex-row items-stretch sm:items-center sm:justify-end">
                    <x-buttonv2 href="{{ route('generate.form') }}" color="primary" class="w-full sm:w-auto">
                        Kembali
                    </x-buttonv2>
                    <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Simpan & Lanjut
                    </x-buttonv2>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addLine() {
            const container = document.getElementById('header-lines-container');
            const rowCount = container.children.length;

            const div = document.createElement('div');
            div.className =
                'header-line-row flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 p-3 bg-background border border-border rounded-xl transition';
            div.innerHTML = `
            <span class="text-xs font-bold text-text-light uppercase tracking-wider shrink-0 w-20">
                Baris ${rowCount + 1}:
            </span>
            <input
                type="text"
                name="header_lines[]"
                placeholder="Masukkan teks baris header..."
                required
                class="w-full flex-1 px-4 py-2.5 rounded-xl border border-border text-text bg-surface text-xs sm:text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200"
            >
            <button
                type="button"
                onclick="removeLine(this)"
                class="w-full sm:w-auto px-3.5 py-2.5 bg-danger/10 hover:bg-danger text-danger hover:text-surface text-xs font-semibold rounded-xl transition duration-200 cursor-pointer flex items-center justify-center gap-1 shrink-0 active:scale-95"
            >
                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"/>
                </svg>
                <span>Hapus</span>
            </button>
        `;
            container.appendChild(div);
        }

        function removeLine(button) {
            const container = document.getElementById('header-lines-container');
            if (container.children.length <= 1) {
                alert('Minimal harus ada 1 baris header.');
                return;
            }
            button.closest('.header-line-row').remove();
            renumberLines();
        }

        function renumberLines() {
            const rows = document.querySelectorAll('.header-line-row');
            rows.forEach((row, index) => {
                row.querySelector('span').textContent = `Baris ${index + 1}:`;
            });
        }
    </script>
@endsection
