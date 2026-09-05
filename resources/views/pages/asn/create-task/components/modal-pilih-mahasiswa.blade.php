{{-- resources/views/pages/asn/create-task/components/modal-pilih-mahasiswa.blade.php --}}

<div id="modal-pilih-mahasiswa"
    class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-gray-900/50 backdrop-blur-sm px-4">

    <div
        class="bg-surface w-full max-w-3xl rounded-xl shadow-lg border border-border flex flex-col max-h-[85vh] transform transition-all">


        {{-- Header Modal --}}
        <div class="flex justify-between items-center p-5 border-b border-border">

            <div>

                <h3 class="text-lg font-bold text-text">
                    Pilih Mahasiswa Magang
                </h3>

                <p class="text-sm text-text-light">
                    Pilih satu atau lebih mahasiswa untuk penugasan ini.
                </p>

            </div>


            <button type="button" onclick="closeModalMahasiswa()"
                class="text-text-light hover:text-danger hover:bg-background p-2 rounded-lg transition-colors">

                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>

                </svg>

            </button>

        </div>


        {{-- Body: List Mahasiswa --}}
        <div class="p-5 overflow-y-auto flex-1 bg-background">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                @foreach ($daftarMahasiswa as $mhs)
                    <label for="mhs-modal-{{ $mhs->id }}"
                        class="flex items-center justify-between p-4 bg-surface border border-border rounded-xl cursor-pointer hover:border-primary hover:shadow-sm transition-all group">


                        <div class="flex items-center gap-4">


                            {{-- Avatar Circle --}}
                            <div
                                class="w-10 h-10 rounded-full bg-primary-light/20 text-primary flex items-center justify-center font-bold text-sm uppercase">

                                {{ substr($mhs->user->name, 0, 1) }}

                            </div>


                            {{-- Informasi Mahasiswa --}}
                            <div>

                                <div class="font-semibold text-sm text-text group-hover:text-primary transition-colors">

                                    {{ $mhs->user->name }}

                                </div>


                                <div class="text-xs text-text-light mt-0.5">

                                    {{ $mhs->jenjang }} - {{ $mhs->jurusan }}

                                </div>


                                <div
                                    class="text-[10px] font-medium text-text-light mt-1 px-2 py-0.5 bg-background rounded-md inline-block">

                                    {{ $mhs->instansi_asal }}

                                </div>

                            </div>

                        </div>


                        {{-- Checkbox --}}
                        <div class="pl-3">

                            <input type="checkbox" name="mahasiswa_ids[]" value="{{ $mhs->id }}"
                                id="mhs-modal-{{ $mhs->id }}" {{-- PENTING:
                                    Menghubungkan checkbox dengan form utama
                                    meskipun checkbox berada di luar <form>
                                --}} form="form-tugas-asn"
                                {{ in_array($mhs->id, old('mahasiswa_ids', [])) ? 'checked' : '' }}
                                class="mhs-checkbox w-5 h-5 text-accent-dark bg-background border-border rounded focus:ring-accent-dark cursor-pointer accent-accent-dark transition-all"
                                onchange="updateSelectedCount()">

                        </div>

                    </label>
                @endforeach

            </div>

        </div>


        {{-- Footer Modal --}}
        <div class="p-5 border-t border-border flex justify-end gap-3 bg-surface rounded-b-xl">


            {{-- Tombol Batal --}}
            <button type="button" onclick="closeModalMahasiswa()"
                class="px-5 py-2.5 border border-border rounded-lg text-sm text-text font-medium hover:bg-background transition-colors">

                Batal

            </button>


            {{-- Tombol Simpan Pilihan --}}
            <button type="button" onclick="closeModalMahasiswa()"
                class="px-6 py-2.5 bg-accent-dark text-surface text-sm font-semibold rounded-lg hover:bg-accent transition-colors shadow-sm">

                Simpan Pilihan

            </button>

        </div>

    </div>

</div>
