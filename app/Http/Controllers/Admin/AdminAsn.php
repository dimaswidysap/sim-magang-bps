<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'required|string|max:20',
                'password' => 'nullable|string|min:8',
                'nip' => 'required|string|unique:asn_profiles,nip,' . optional($user->asnProfile)->id,
                'jabatan' => 'nullable|string|max:255',
                'unit_kerja' => 'nullable|string|max:255',
            ],
            [
                // Pesan error untuk field name
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.string' => 'Nama lengkap harus berupa teks.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',

                // Pesan error untuk field email
                'email.required' => 'Alamat email wajib diisi.',
                'email.email' => 'Format email tidak valid. Contoh: nama@domain.com',
                'email.unique' => 'Alamat email sudah digunakan oleh pengguna lain. Silakan gunakan email lain.',

                // Pesan error untuk field phone
                'phone.required' => 'Nomor telepon wajib diisi.',
                'phone.string' => 'Nomor telepon harus berupa number.',
                'phone.max' => 'Nomor telepon maksimal 20 karakter.',

                // Pesan error untuk field password
                'password.string' => 'Kata sandi harus berupa teks.',
                'password.min' => 'Kata sandi minimal 8 karakter.',

                // Pesan error untuk field nip
                'nip.required' => 'NIP wajib diisi.',
                'nip.string' => 'NIP harus berupa number.',
                'nip.unique' => 'NIP sudah digunakan oleh ASN lain. Silakan gunakan NIP lain.',

                // Pesan error untuk field jabatan
                'jabatan.string' => 'Jabatan harus berupa teks.',
                'jabatan.max' => 'Jabatan maksimal 255 karakter.',

                // Pesan error untuk field unit_kerja
                'unit_kerja.string' => 'Unit kerja harus berupa teks.',
                'unit_kerja.max' => 'Unit kerja maksimal 255 karakter.',
            ],
        );

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
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:13',
                'password' => 'required|string|min:8',
                'nip' => 'required|string|unique:asn_profiles,nip',
                'jabatan' => 'nullable|string|max:255',
                'unit_kerja' => 'nullable|string|max:255',
            ],
            [
                // Pesan error untuk field name
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.string' => 'Nama lengkap harus berupa teks.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',

                // Pesan error untuk field email
                'email.required' => 'Alamat email wajib diisi.',
                'email.email' => 'Format email tidak valid. Contoh: nama@domain.com',
                'email.unique' => 'Alamat email sudah terdaftar. Silakan gunakan email lain.',

                // Pesan error untuk field phone
                'phone.string' => 'Nomor telepon harus berupa angka.',
                'phone.max' => 'Nomor telepon maksimal 12 angka.',

                // Pesan error untuk field password
                'password.required' => 'Kata sandi wajib diisi.',
                'password.string' => 'Kata sandi harus berupa teks.',
                'password.min' => 'Kata sandi minimal 8 karakter.',

                // Pesan error untuk field nip
                'nip.required' => 'NIP wajib diisi.',
                'nip.string' => 'NIP harus berupa angka.',
                'nip.unique' => 'NIP sudah terdaftar. Silakan gunakan NIP lain.',

                // Pesan error untuk field jabatan
                'jabatan.string' => 'Jabatan harus berupa teks.',
                'jabatan.max' => 'Jabatan maksimal 255 karakter.',

                // Pesan error untuk field unit_kerja
                'unit_kerja.string' => 'Unit kerja harus berupa teks.',
                'unit_kerja.max' => 'Unit kerja maksimal 255 karakter.',
            ],
        );

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
        $user = User::query()->where('id', $id)->where('role', 'asn')->firstOrFail();

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        // asn_id di tabel `tugas` pakai cascadeOnDelete, jadi baris ini
        // otomatis menghapus semua tugas yang dibuat ASN ini beserta
        // tugas_submissions terkait (karena tugas_submissions cascade ke tugas).
        $jumlahTugas = $user->tugasDibuat()->count();

        $user->delete('destroyAsn');

        return redirect()
            ->route('admin-asn')
            ->with('success', "Data ASN berhasil dihapus. {$jumlahTugas} tugas yang pernah dibuat ASN ini ikut terhapus permanen.");
    }
}
