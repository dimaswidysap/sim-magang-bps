 <div class="flex items-center justify-between gap-2 border-b border-border pb-2">
     <nav>
         <a href="{{ route('asn-aktif') }}"
             class="px-4 py-2 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('asn-aktif') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-text-light hover:text-text' }}">
             ASN Aktif
         </a>
         <a href="{{ route('asn-nonaktif') }}"
             class="px-4 py-2 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('asn-nonaktif') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-text-light hover:text-text' }}">
             ASN Nonaktif
         </a>
     </nav>

     <div class="flex items-center gap-3">
         <a href="{{ route('admin-index') }}"
             class="inline-flex items-center gap-2 px-4 py-2 bg-surface border border-border rounded-lg text-xs font-semibold text-text hover:bg-black/5 transition-colors">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
             </svg>
             Kembali
         </a>
     </div>
 </div>
