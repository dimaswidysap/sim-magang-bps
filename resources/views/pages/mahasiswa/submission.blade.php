@extends('layouts.app')

@section('content')

<h1>Tugas Menunggu Review</h1>

@if ($tugasMenungguReview->isEmpty())
    <p>Tidak ada tugas yang menunggu review saat ini.</p>
@endif

@foreach ($tugasMenungguReview as $tugas)
    <div>
        <p><strong>{{ $tugas->judul }}</strong></p>
        <p>
            Ketua: {{ $tugas->mahasiswaProfile->user->name ?? '-' }}
            @if ($tugas->anggotaDiterima->isNotEmpty())
                + {{ $tugas->anggotaDiterima->count() }} anggota
            @endif
        </p>
        <p>Dikumpulkan: {{ $tugas->submissions->first()?->created_at?->format('d M Y H:i') ?? '-' }}</p>
        @if ($tugas->submissions->first()?->file_path)
            <p>Ada file terlampir</p>
        @else
            <p><em>Hanya pesan teks, tanpa file</em></p>
        @endif
        <p><a href="{{ route('asn-submission-detail', $tugas->id) }}">Lihat Detail</a></p>
    </div>
    <hr>
@endforeach

@endsection
