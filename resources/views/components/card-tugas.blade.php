 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 font-montserrat">
     @forelse ($dataTugas as $tugas)
         <div
             class="bg-surface border border-border rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col p-5 group">

             <!-- Header Card: Status & Deadline -->
             <div class="flex items-start justify-between gap-2 mb-3">
                 <!-- Status Badge -->
                 @if (strtolower($tugas->status) === 'tersedia')
                     <span
                         class="inline-flex px-2.5 py-1 bg-success/10 text-success text-xs font-bold rounded-md uppercase tracking-wide border border-success/20">
                         {{ $tugas->status }}
                     </span>
                 @else
                     <span
                         class="inline-flex px-2.5 py-1 bg-warning/10 text-warning text-xs font-bold rounded-md uppercase tracking-wide border border-warning/20">
                         {{ $tugas->status ?? 'Unknown' }}
                     </span>
                 @endif

                 <!-- Waktu Deadline -->
                 <div class="text-right">
                     <p class="text-[10px] text-text-light font-medium uppercase">Tenggat Waktu</p>
                     <p class="text-xs font-semibold text-danger flex items-center justify-end gap-1 mt-0.5">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                 d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                         </svg>
                         {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y, H:i') : '-' }}
                     </p>
                 </div>
             </div>

             <!-- Body Card: Judul & Deskripsi -->
             <div class="flex-1">
                 <h3 class="text-lg font-bold text-text mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                     {{ $tugas->judul }}
                 </h3>
                 <p class="text-sm text-text-light line-clamp-3">
                     {{ $tugas->deskripsi }}
                 </p>
             </div>

             <!-- Info Pembuat Tugas (ASN) -->
             <div class="mt-4 pt-4 border-t border-border flex items-center gap-3">
                 <!-- Avatar Initial ASN -->
                 <div
                     class="w-8 h-8 rounded-full bg-primary-light/20 text-primary-dark flex items-center justify-center font-bold text-xs shrink-0">
                     {{ strtoupper(substr($tugas->asn->name ?? 'A', 0, 1)) }}
                 </div>
                 <div class="overflow-hidden">
                     <p class="text-[10px] text-text-light uppercase font-medium">Dibuat oleh</p>
                     <p class="text-sm font-medium text-text truncate">
                         {{ $tugas->asn->name ?? 'Admin / ASN' }}
                     </p>
                 </div>
             </div>

             <!-- Footer: Button Aksi -->
             <div class="mt-5">

                 <x-main-button
                        class="bg-primary w-full  justify-center hover:bg-primary-dark  text-xs px-4 py-2 rounded-lg text-white transition-colors shadow-sm inline-flex items-center gap-2"
                        href="{{ route('detail-tugas',$tugas->id) }}">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                         <path stroke-linecap="round" stroke-linejoin="round"
                             d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                     </svg>
                        <span> Lihat Detail Tugas</span>
                    </x-main-button>
             </div>

         </div>
     @empty
         <!-- Tampilan Jika Data Tugas Kosong -->
         <div
             class="col-span-1 md:col-span-2 lg:col-span-3 py-16 bg-surface border border-border rounded-xl text-center">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mx-auto text-border mb-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                 <path stroke-linecap="round" stroke-linejoin="round"
                     d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
             </svg>
             <h3 class="text-lg font-bold text-text">Belum Ada Tugas</h3>
             <p class="text-sm text-text-light mt-1">Saat ini tidak ada tugas magang yang tersedia.</p>
         </div>
     @endforelse
 </div>
