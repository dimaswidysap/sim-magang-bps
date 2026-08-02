<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AsnProfile;

class AdminAsn extends Controller
{
    //menampilkan semua data asn
    public function detailAsn($id)
    {
        // 1. Panggil relasi with() sebelum findOrFail()
        $detailAsn = User::with('asnProfile')->findOrFail($id);

        // 2. Kirim data ke view menggunakan compact()
        return view('pages.admin.asn.view', compact('detailAsn'));
    }

    // menampilan form update asn
    public function formUpdateAsn($id)
    {
        $dataAsn = User::query()->where('id', $id)->where('role', 'asn')->with('asnProfile')->firstOrFail();

        return view('pages.admin.asn.update', compact('dataAsn'));
    }

    // update data ke databases
    public function updateAsn(Request $request, $id)
    {
        $user = User::query()->where('id', $id)->where('role', 'asn')->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'nip' => 'required|string|unique:asn_profiles,nip,' . optional($user->asnProfile)->id,
            'jabatan' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $validated, $user) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                // checkbox: kalau di-uncheck, field ini tidak terkirim sama
                // sekali - boolean() otomatis jadi false di kondisi itu,
                // bukan malah error/diabaikan.
                'is_active' => $request->boolean('is_active'),
                // Password cuma diganti kalau diisi - kalau dikosongkan,
                // password lama tetap dipakai.
                'password' => !empty($validated['password']) ? Hash::make($validated['password']) : $user->password,
            ]);

            // updateOrCreate: kalau profile ASN ternyata belum ada (misal
            // dulu dibuat lewat cara lain tanpa profile), otomatis dibuatkan.
            AsnProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => $validated['nip'],
                    'jabatan' => $validated['jabatan'] ?? null,
                    'unit_kerja' => $validated['unit_kerja'] ?? null,
                ],
            );
        });

        return redirect()->route('admin-asn')->with('success', 'Data ASN berhasil diperbarui.');
    }

    // menampilkan form tambah data
    public function showForm()
    {
        return view('pages.admin.asn.create');
    }

    // memasukan data ke dalam databases
    public function storeAsn(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'nip' => 'required|string|unique:asn_profiles,nip',
            'jabatan' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
        ]);

        // DB::transaction: kalau insert AsnProfile gagal setelah User berhasil
        // dibuat, keduanya ikut dibatalkan - mencegah akun ASN "setengah jadi"
        // (punya login tapi tanpa data NIP/jabatan).
        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'asn',
                'is_active' => true,
            ]);

            AsnProfile::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'jabatan' => $validated['jabatan'] ?? null,
                'unit_kerja' => $validated['unit_kerja'] ?? null,
            ]);
        });

        return redirect()->route('admin-asn')->with('success', 'Data ASN berhasil ditambahkan.');
    }

    // menghapus data asn
    public function destroyAsn(Request $request, $id)
{
    $user = User::query()->where('id', $id)
        ->where('role', 'asn')
        ->firstOrFail();

    if ($user->id === auth()->id()) {
        return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
    }

    // asn_id di tabel `tugas` pakai cascadeOnDelete, jadi baris ini
    // otomatis menghapus semua tugas yang dibuat ASN ini beserta
    // tugas_submissions terkait (karena tugas_submissions cascade ke tugas).
    $jumlahTugas = $user->tugasDibuat()->count();

    $user->delete();

    return redirect()->route('admin-asn')
        ->with('success', "Data ASN berhasil dihapus. {$jumlahTugas} tugas yang pernah dibuat ASN ini ikut terhapus permanen.");
}
}
