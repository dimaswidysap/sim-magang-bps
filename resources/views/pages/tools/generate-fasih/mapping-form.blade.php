{{-- resources/views/generate/mapping-form.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 font-montserrat my-6 sm:my-10">
        <!-- Section Title -->
        <div class="mb-5 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-text">Langkah 4: Mapping Field Dokumen</h1>
            <p class="text-xs sm:text-sm text-text-light mt-1 leading-relaxed">
                Pilih label tampilan dan kolom sumber data dari Excel Anda.
            </p>

            <!-- Informational Note Banner -->
            <div
                class="mt-3 p-3 bg-primary-light/10 border border-primary-light/30 rounded-xl text-xs text-text flex items-start gap-2.5">
                <svg class="w-4 h-4 text-primary shrink-0 mt-0.5 fill-current" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <span><strong>Petunjuk:</strong> Tandai satu kolom yang akan dipakai untuk mencocokkan nama folder foto ke
                    baris data (biasanya kolom nama).</span>
            </div>
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
            <form action="{{ route('generate.storeMapping') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Dynamic Mapping Container -->
                <div id="mapping-container" class="space-y-4">
                    @foreach ($mappings as $index => $mapping)
                        <div
                            class="mapping-row p-4 bg-background border border-border rounded-xl flex flex-col md:flex-row md:items-center gap-3 transition">
                            <!-- Label Input -->
                            <div class="w-full md:w-1/3">
                                <label
                                    class="block text-[10px] font-bold text-text-light uppercase tracking-wider mb-1">Label
                                    Tampilan</label>
                                <input type="text" name="labels[]" value="{{ $mapping['label'] }}"
                                    placeholder="Contoh: Nama PPL" required
                                    class="w-full px-3.5 py-2 rounded-xl border border-border text-text bg-surface text-xs sm:text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200">
                            </div>

                            <!-- Column Selection -->
                            <div class="w-full md:w-1/3">
                                <label
                                    class="block text-[10px] font-bold text-text-light uppercase tracking-wider mb-1">Sumber
                                    Data (Excel)</label>
                                <select name="columns[]" required
                                    class="w-full px-3.5 py-2 rounded-xl border border-border text-text bg-surface text-xs sm:text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200">
                                    <option value="">-- Pilih Kolom --</option>
                                    @foreach ($headers as $header)
                                        <option value="{{ $header }}"
                                            {{ $mapping['column'] === $header ? 'selected' : '' }}>
                                            {{ $header }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Match Column Radio -->
                            <div class="w-full md:w-auto md:flex-1 pt-1 md:pt-4">
                                <label
                                    class="inline-flex items-center gap-2 cursor-pointer text-xs text-text hover:text-primary transition select-none">
                                    <input type="radio" name="match_column" value="{{ $index }}"
                                        {{ session('folder_match_column') === $mapping['column'] || ($loop->first && !session('folder_match_column')) ? 'checked' : '' }}
                                        required class="w-4 h-4 text-primary border-border focus:ring-primary/20">
                                    <span class="font-medium">Cocokkan Folder</span>
                                </label>
                            </div>

                            <!-- Action Delete Button -->
                            <div class="w-full md:w-auto pt-1 md:pt-4 flex justify-end">
                                <button type="button" onclick="removeMapping(this)"
                                    class="w-full md:w-auto px-3.5 py-2 bg-danger/10 hover:bg-danger text-danger hover:text-surface text-xs font-semibold rounded-xl transition duration-200 cursor-pointer flex items-center justify-center gap-1 shrink-0 active:scale-95">
                                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Add Row Button -->
                <div>
                    <button type="button" onclick="addMapping()"
                        class="w-full py-3 border-2 border-dashed border-border hover:border-primary text-text-light hover:text-primary font-semibold text-xs sm:text-sm rounded-xl transition duration-200 flex items-center justify-center gap-2 cursor-pointer bg-surface/50 hover:bg-primary-light/10 active:scale-98">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Tambah Field Mapping</span>
                    </button>
                </div>

                <!-- Submit Button -->
                <div
                    class="pt-4 gap-4 border-t border-border/60 flex flex-col sm:flex-row items-stretch sm:items-center sm:justify-end">
                    <x-buttonv2 href="{{ route('generate.headerForm') }}" color="primary" class="w-full sm:w-auto">

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

    <!-- HTML Template for Dynamic JS Addition -->
    <template id="mapping-row-template">
        <div
            class="mapping-row p-4 bg-background border border-border rounded-xl flex flex-col md:flex-row md:items-center gap-3 transition">
            <!-- Label Input -->
            <div class="w-full md:w-1/3">
                <label class="block text-[10px] font-bold text-text-light uppercase tracking-wider mb-1">Label
                    Tampilan</label>
                <input type="text" name="labels[]" placeholder="Contoh: Nama PPL" required
                    class="w-full px-3.5 py-2 rounded-xl border border-border text-text bg-surface text-xs sm:text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200">
            </div>

            <!-- Column Selection -->
            <div class="w-full md:w-1/3">
                <label class="block text-[10px] font-bold text-text-light uppercase tracking-wider mb-1">Sumber Data
                    (Excel)</label>
                <select name="columns[]" required
                    class="w-full px-3.5 py-2 rounded-xl border border-border text-text bg-surface text-xs sm:text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition duration-200">
                    <option value="">-- Pilih Kolom --</option>
                    @foreach ($headers as $header)
                        <option value="{{ $header }}">{{ $header }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Match Column Radio -->
            <div class="w-full md:w-auto md:flex-1 pt-1 md:pt-4">
                <label
                    class="inline-flex items-center gap-2 cursor-pointer text-xs text-text hover:text-primary transition select-none">
                    <input type="radio" name="match_column" value="" required
                        class="w-4 h-4 text-primary border-border focus:ring-primary/20">
                    <span class="font-medium">Cocokkan Folder</span>
                </label>
            </div>

            <!-- Action Delete Button -->
            <div class="w-full md:w-auto pt-1 md:pt-4 flex justify-end">
                <button type="button" onclick="removeMapping(this)"
                    class="w-full md:w-auto px-3.5 py-2 bg-danger/10 hover:bg-danger text-danger hover:text-surface text-xs font-semibold rounded-xl transition duration-200 cursor-pointer flex items-center justify-center gap-1 shrink-0 active:scale-95">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Hapus</span>
                </button>
            </div>
        </div>
    </template>

    <script>
        function addMapping() {
            const container = document.getElementById('mapping-container');
            const template = document.getElementById('mapping-row-template').content.cloneNode(true);

            container.appendChild(template);
            renumberMatchRadios();
        }

        function removeMapping(button) {
            const container = document.getElementById('mapping-container');
            if (container.querySelectorAll('.mapping-row').length <= 1) {
                alert('Minimal harus ada 1 field mapping.');
                return;
            }

            button.closest('.mapping-row').remove();
            renumberMatchRadios();
        }

        function renumberMatchRadios() {
            const rows = document.querySelectorAll('.mapping-row');
            let hasChecked = false;

            rows.forEach((row, index) => {
                const radio = row.querySelector('input[type="radio"]');
                if (radio.checked) hasChecked = true;
                radio.value = index;
            });

            if (!hasChecked && rows.length > 0) {
                rows[0].querySelector('input[type="radio"]').checked = true;
            }
        }
    </script>
@endsection
