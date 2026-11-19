<?php

namespace App\Http\Controllers\Asn;

use App\Http\Controllers\Controller;
use App\Models\PengajuanLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanLiburAsnController extends Controller
{
    public function index(Request $request)
    {
        $asnProfileId = Auth::user()->asnProfile?->id;

        if (! $asnProfileId) {
            abort(403, 'Profil ASN Anda belum terdaftar.');
        }

        $filterStatus = (string) $request->query('status', 'pending');
        if (! in_array($filterStatus, ['pending', 'disetujui', 'ditolak', 'semua'], true)) {
            $filterStatus = 'pending';
        }

        $baseQuery = PengajuanLibur::query()
            ->where('asn_profile_id', $asnProfileId);

        $jumlahPending = (clone $baseQuery)->where('status', 'pending')->count();
        $jumlahDisetujui = (clone $baseQuery)->where('status', 'disetujui')->count();
        $jumlahDitolak = (clone $baseQuery)->where('status', 'ditolak')->count();
        $jumlahSemua = (clone $baseQuery)->count();

        $daftarPengajuan = (clone $baseQuery)
            ->when($filterStatus !== 'semua', fn ($q) => $q->where('status', $filterStatus))
            ->with(['mahasiswaProfile.user'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('pages.asn.pengajuan-libur.index', compact(
            'daftarPengajuan',
            'filterStatus',
            'jumlahPending',
            'jumlahDisetujui',
            'jumlahDitolak',
            'jumlahSemua'
        ));
    }

    public function show($id)
    {
        $asnProfileId = Auth::user()->asnProfile?->id;

        if (! $asnProfileId) {
            abort(403, 'Profil ASN Anda belum terdaftar.');
        }

        $pengajuan = PengajuanLibur::where('asn_profile_id', $asnProfileId)
            ->with(['mahasiswaProfile.user', 'pembimbing.user'])
            ->findOrFail($id);

        return view('pages.asn.pengajuan-libur.show', compact('pengajuan'));
    }

    public function setujui(Request $request, $id)
    {
        $asnProfileId = Auth::user()->asnProfile?->id;

        $pengajuan = PengajuanLibur::where('asn_profile_id', $asnProfileId ?? 0)
            ->with('mahasiswaProfile.user')
            ->findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan libur ini sudah direspons sebelumnya.');
        }

        $pengajuan->update([
            'status' => 'disetujui',
            'catatan_asn' => $request->input('catatan_asn') ? trim((string) $request->input('catatan_asn')) : null,
            'direspon_at' => now(),
        ]);

        $namaPeserta = $pengajuan->mahasiswaProfile?->user?->name ?? 'Peserta';

        return redirect()
            ->route('asn.pengajuan-libur.index')
            ->with('success', "Pengajuan libur atas nama {$namaPeserta} berhasil disetujui.");
    }

    public function tolak(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'catatan_asn' => ['required', 'string', 'min:5', 'max:1000'],
            ],
            [
                'catatan_asn.required' => 'Alasan penolakan wajib diisi agar peserta mengetahui alasannya.',
                'catatan_asn.min' => 'Alasan penolakan minimal 5 karakter.',
                'catatan_asn.max' => 'Alasan penolakan maksimal 1000 karakter.',
            ]
        );

        $asnProfileId = Auth::user()->asnProfile?->id;

        $pengajuan = PengajuanLibur::where('asn_profile_id', $asnProfileId ?? 0)
            ->with('mahasiswaProfile.user')
            ->findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan libur ini sudah direspons sebelumnya.');
        }

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_asn' => $validated['catatan_asn'],
            'direspon_at' => now(),
        ]);

        $namaPeserta = $pengajuan->mahasiswaProfile?->user?->name ?? 'Peserta';

        return redirect()
            ->route('asn.pengajuan-libur.index')
            ->with('success', "Pengajuan libur atas nama {$namaPeserta} telah ditolak.");
    }
}
