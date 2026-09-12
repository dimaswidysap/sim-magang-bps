@extends('layouts.app')

@section('content')
    <main class="w-full p-4 md:p-8 bg-background min-h-screen font-montserrat">
        <section class="max-w-5xl mx-auto">

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="mb-6 bg-danger/10 border border-danger p-4 rounded-xl flex items-start gap-3">
                    <svg class="h-5 w-5 text-danger shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-danger">Terdapat kesalahan pada input Anda:</h3>
                        <ul class="list-disc list-inside text-sm text-danger mt-1 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="bg-surface border border-border rounded-2xl shadow-sm p-6 md:p-8">
                <form enctype="multipart/form-data" method="POST" action="{{ route('asn-update-tugas', $tugas->id) }}"
                    data-confirm="Apakah anda yakin ingin menerapkan perubahan?">
                    @csrf
                    @method('PUT')

                    <!-- Container untuk menampung ID file lama yang dihapus -->
                    <div id="deleted-attachments-container"></div>

                    <!-- Section: Informasi Tugas -->
                    <div class="mb-8">
                        <h2 class="flex items-center gap-2 text-primary font-bold text-lg mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Informasi Tugas
                        </h2>

                        <!-- Kotak Form Berwarna Abu-abu Lembut -->
                        <div class="bg-[#F8FAFC] border border-border rounded-xl p-5 sm:p-6 space-y-5">

                            <!-- Judul Tugas -->
                            <div>
                                <label class="block text-sm font-medium text-text-light mb-1.5">Judul Tugas</label>
                                <input type="text" name="judul" value="{{ old('judul', $tugas->judul) }}"
                                    class="w-full bg-surface border border-border rounded-lg px-4 py-2.5 text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                                    placeholder="Contoh: Membuat Desain UI Dashboard">
                            </div>

                            <!-- Batas Waktu -->
                            <div>
                                <label class="block text-sm font-medium text-text-light mb-1.5">Batas Waktu
                                    (Deadline)</label>
                                <input type="datetime-local" name="deadline"
                                    value="{{ old('deadline', date('Y-m-d\TH:i', strtotime($tugas->deadline))) }}"
                                    class="w-full md:w-1/2 bg-surface border border-border rounded-lg px-4 py-2.5 text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors cursor-pointer">
                            </div>

                            <!-- Deskripsi Lengkap -->
                            <div>
                                <label class="block text-sm font-medium text-text-light mb-1.5">Deskripsi Lengkap</label>
                                <textarea name="deskripsi" rows="4"
                                    class="w-full bg-surface border border-border rounded-lg px-4 py-3 text-text focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-y"
                                    placeholder="Jelaskan detail pekerjaan, kriteria, atau instruksi tambahan di sini...">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                            </div>

                            <!-- File Lampiran Saat Ini (Tampilan Lama + Tombol X) -->
                            @if ($tugas->attachments && $tugas->attachments->count() > 0)
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                        File Lampiran Saat Ini
                                    </label>
                                    <div class="space-y-2">
                                        @foreach ($tugas->attachments as $attachment)
                                            <div class="flex items-center justify-between p-3 bg-surface border border-border rounded-lg text-sm"
                                                id="attachment-row-{{ $attachment->id }}">
                                                <div class="flex items-center gap-2.5 truncate">
                                                    @if (Str::startsWith($attachment->mime_type, 'image/'))
                                                        <img src="{{ Storage::url($attachment->file_path) }}" alt="preview"
                                                            class="w-8 h-8 rounded object-cover border border-border shrink-0">
                                                    @else
                                                        <svg class="h-5 w-5 text-primary shrink-0" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                        </svg>
                                                    @endif
                                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                                                        class="font-medium text-primary hover:underline truncate">
                                                        {{ $attachment->file_name }}
                                                    </a>
                                                    <span
                                                        class="text-xs text-text-light shrink-0">({{ round($attachment->file_size / 1024, 1) }}
                                                        KB)</span>
                                                </div>

                                                <!-- Tombol X Merah -->
                                                <button type="button" onclick="removeExistingFile({{ $attachment->id }})"
                                                    class="p-1.5 text-danger hover:bg-danger/10 rounded-lg transition-colors shrink-0 ml-2"
                                                    title="Hapus file ini">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Input Upload File Baru (Tampilan Lama Desain Input) -->
                            <div class="font-montserrat">
                                <label class="block text-sm font-semibold text-text-light uppercase tracking-wider mb-2">
                                    Tambah File Baru
                                    <span
                                        class="normal-case font-medium text-[11px] text-text-light/70 ml-1">(Opsional)</span>
                                </label>

                                <div
                                    class="relative border-2 border-dashed border-border rounded-xl p-4 md:p-5 bg-background hover:border-primary/50 transition-colors group">
                                    <input type="file" id="file-input" name="file[]" multiple
                                        onchange="handleNewFiles(this.files)"
                                        class="block w-full text-sm text-text cursor-pointer focus:outline-none
                                            file:mr-4 file:py-2.5 file:px-5
                                            file:rounded-lg file:border-0
                                            file:text-xs file:font-semibold
                                            file:bg-primary file:text-white
                                            hover:file:bg-primary-dark file:transition-colors file:cursor-pointer">

                                    <p class="text-[11px] text-text-light mt-3 flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Dapat memilih lebih dari 1 file. Ukuran maksimal per file <strong>10
                                                MB</strong>.</span>
                                    </p>
                                </div>

                                <!-- Daftar Preview File Baru yang Dipilih -->
                                <div id="new-files-list" class="mt-3 space-y-2"></div>
                            </div>

                        </div>
                    </div>

                    <!-- Section: Penugasan Mahasiswa -->
                    <div class="mb-8">
                        <h2 class="flex items-center gap-2 text-primary font-bold text-lg mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Penugasan Mahasiswa
                        </h2>

                        <div class="bg-[#F8FAFC] border border-border rounded-xl p-5 sm:p-6">
                            <p class="text-sm text-text-light mb-4">
                                Pilih mahasiswa yang ditugaskan secara langsung.
                            </p>

                            @php
                                $assignedIds = old(
                                    'mahasiswa_ids',
                                    array_filter(
                                        array_merge(
                                            [$tugas->mahasiswa_profile_id],
                                            $tugas->anggota->pluck('mahasiswa_profile_id')->toArray(),
                                        ),
                                    ),
                                );
                            @endphp

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @forelse ($mahasiswaList as $mhs)
                                    <label for="mhs-{{ $mhs->id }}"
                                        class="inline-flex items-center gap-2.5 p-3 bg-surface border border-border rounded-xl cursor-pointer hover:bg-background transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5 group">
                                        <input type="checkbox" name="mahasiswa_ids[]" value="{{ $mhs->id }}"
                                            id="mhs-{{ $mhs->id }}"
                                            class="w-4 h-4 text-primary border-border rounded focus:ring-primary"
                                            {{ in_array($mhs->id, $assignedIds) ? 'checked' : '' }}>
                                        <div class="flex flex-col truncate">
                                            <span
                                                class="text-sm font-medium text-text group-has-[:checked]:text-primary-dark truncate">
                                                {{ $mhs->user->name ?? 'Mahasiswa' }}
                                            </span>
                                            <span class="text-xs text-text-light truncate">
                                                {{ $mhs->instansi_asal ?? '-' }}
                                            </span>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-xs text-text-light italic col-span-full">Tidak ada data mahasiswa aktif
                                        saat ini.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Section: Skill yang Dibutuhkan -->
                    <div class="mb-8">
                        <h2 class="flex items-center gap-2 text-primary font-bold text-lg mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Skill yang Dibutuhkan
                        </h2>

                        <div class="bg-[#F8FAFC] border border-border rounded-xl p-5 sm:p-6">
                            <p class="text-sm text-text-light mb-4">
                                Pilih satu atau lebih keahlian yang relevan dengan tugas ini.
                            </p>

                            @php
                                $selectedSkills = old('skills', $tugas->skills->pluck('id')->toArray());
                            @endphp

                            <div class="flex flex-wrap gap-3">
                                @foreach ($skills as $skill)
                                    <label for="skill-{{ $skill->id }}"
                                        class="inline-flex items-center gap-2.5 px-4 py-2 bg-surface border border-border rounded-full cursor-pointer hover:bg-background transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5 group">
                                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                            id="skill-{{ $skill->id }}"
                                            class="w-4 h-4 text-primary border-border rounded focus:ring-primary"
                                            {{ in_array($skill->id, $selectedSkills) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-text group-has-[:checked]:text-primary-dark">
                                            {{ $skill->nama_skill }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <hr class="border-border mb-6">

                    <!-- Footer Buttons -->
                    <div class="flex justify-end items-center gap-3">
                        <x-buttonv2 href="{{ route('task-not-done') }}" color="primary" class="w-full sm:w-auto">
                            Batal
                        </x-buttonv2>

                        <x-buttonv2 type="submit" color="accent-dark" class="w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </x-buttonv2>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
        // Hapus file lama
        function removeExistingFile(attachmentId) {
            const row = document.getElementById(`attachment-row-${attachmentId}`);
            if (row) row.remove();

            const container = document.getElementById('deleted-attachments-container');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'delete_attachments[]';
            hiddenInput.value = attachmentId;
            container.appendChild(hiddenInput);
        }

        // Preview & Hapus file baru
        let selectedNewFiles = [];

        function handleNewFiles(files) {
            selectedNewFiles = [...selectedNewFiles, ...Array.from(files)];
            updateFileInputAndPreview();
        }

        function removeNewFile(index) {
            selectedNewFiles.splice(index, 1);
            updateFileInputAndPreview();
        }

        function updateFileInputAndPreview() {
            const input = document.getElementById('file-input');
            const dataTransfer = new DataTransfer();

            selectedNewFiles.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;

            renderNewFilesPreview();
        }

        function renderNewFilesPreview() {
            const container = document.getElementById('new-files-list');
            container.innerHTML = '';

            selectedNewFiles.forEach((file, index) => {
                const row = document.createElement('div');
                row.className =
                    'flex items-center justify-between p-3 bg-surface border border-border rounded-lg text-sm';

                const leftCol = document.createElement('div');
                leftCol.className = 'flex items-center gap-2.5 truncate';

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'w-8 h-8 rounded object-cover border border-border shrink-0';
                    img.onload = () => URL.revokeObjectURL(img.src);
                    leftCol.appendChild(img);
                } else {
                    leftCol.innerHTML =
                        `<svg class="h-5 w-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>`;
                }

                const nameSpan = document.createElement('span');
                nameSpan.className = 'font-medium text-text truncate';
                nameSpan.textContent = file.name;

                const sizeSpan = document.createElement('span');
                sizeSpan.className = 'text-xs text-text-light shrink-0';
                sizeSpan.textContent = `(${ (file.size / 1024).toFixed(1) } KB)`;

                leftCol.appendChild(nameSpan);
                leftCol.appendChild(sizeSpan);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className =
                    'p-1.5 text-danger hover:bg-danger/10 rounded-lg transition-colors shrink-0 ml-2';
                removeBtn.title = 'Batal upload file ini';
                removeBtn.innerHTML =
                    `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
                removeBtn.onclick = () => removeNewFile(index);

                row.appendChild(leftCol);
                row.appendChild(removeBtn);
                container.appendChild(row);
            });
        }
    </script>
@endsection
