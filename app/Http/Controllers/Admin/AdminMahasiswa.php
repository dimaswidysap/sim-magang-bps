<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Models\PeriodeMagang;
use App\Models\Skill;

class AdminMahasiswa extends Controller
{
    //
    public function showForm()
    {
        $periodeList = PeriodeMagang::orderBy('tanggal_mulai', 'desc')->get();
        $skillList = Skill::orderBy('nama_skill', 'asc')->get();

        return view('pages.admin.mahasiswa.create', compact('periodeList', 'skillList'));
    }

    public function detailMahasiswa($id)
    {
        // Mengambil data user berdasarkan ID. Gunakan findOrFail agar otomatis
        // menampilkan error 404 jika ID tidak ditemukan di database.
        $detailUser = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->with('mahasiswaProfile.skills') // sekalian ambil skill kalau perlu
            ->firstOrFail();
        // Jangan lupa mengirimkan variabel $detailUser ke dalam view menggunakan compact()
        return view('pages.admin.mahasiswa.view', compact('detailUser'));
    }

    public function storeMahasiswa(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        return redirect()->route('admin-mahasiswa')->with('success', 'Data mahasiswa berhasil ditambahkan. Mahasiswa perlu melengkapi profil (NIM, instansi, dll) setelah login.');
    }

    public function formMahasiswaEdit($id)
    {
        // Tambahkan query() setelah User
        $dataUser = User::query()->where('id', $id)->where('role', 'mahasiswa')->with('mahasiswaProfile.skills')->firstOrFail();

        // Tambahkan query() setelah PeriodeMagang
        $periodeList = PeriodeMagang::query()->orderBy('tanggal_mulai', 'desc')->get();

        // Tambahkan query() setelah Skill
        $skillList = Skill::query()->orderBy('nama_skill','asc')->get();

        // Ambil daftar id skill yang sudah dipilih mahasiswa ini, buat mempermudah
        // cek "checked" di form. Kalau belum punya profile, otomatis array kosong.
        $selectedSkillIds = $dataUser->mahasiswaProfile ? $dataUser->mahasiswaProfile->skills->pluck('id')->toArray() : [];

        return view('pages.admin.mahasiswa.update', compact('dataUser', 'periodeList', 'skillList', 'selectedSkillIds'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $user = User::query()->where('id', $id)->where('role', 'mahasiswa')->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'nim' => 'required|string|unique:mahasiswa_profiles,nim,' . optional($user->mahasiswaProfile)->id,
            'instansi_asal' => 'required|string|max:255',
            'jenjang' => 'nullable|in:SMA/SMK,D3,D4,S1,S2',
            'jurusan' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:13',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:pending,aktif,selesai,dibatalkan',
            'periode_magang_id' => 'nullable|exists:periode_magang,id',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        DB::transaction(function () use ($validated, $user) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone'=>$validated['phone'],
                // Password cuma diganti kalau diisi - kalau dikosongkan,
                // password lama tetap dipakai (tidak ditimpa jadi kosong/null).
                'password' => !empty($validated['password']) ? Hash::make($validated['password']) : $user->password,
            ]);

            // updateOrCreate: kalau profile belum ada (mahasiswa belum pernah
            // isi sendiri), otomatis dibuatkan. Kalau sudah ada, di-update.
            $profile = MahasiswaProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'periode_magang_id' => $validated['periode_magang_id'] ?? null,
                    'nim' => $validated['nim'],
                    'instansi_asal' => $validated['instansi_asal'],
                    'jenjang' => $validated['jenjang'] ?? null,
                    'jurusan' => $validated['jurusan'] ?? null,
                    'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                    'status' => $validated['status'],
                ],
            );

            // sync() (bukan attach()): otomatis hapus skill yang di-uncheck dan
            // tambah yang baru dicentang. Kalau skills[] tidak dikirim sama
            // sekali (semua di-uncheck), ?? [] memastikan semua skill lama
            // ikut terhapus dari pivot, bukan malah diabaikan.
            $profile->skills()->sync($validated['skills'] ?? []);
        });

        return redirect()->route('admin-mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    // menghapus data mahasiswa (berserta user untuk login)
    public function destroyMahasiswa(Request $request, $id)
    {
        $user = User::query()
            ->where('id', $id)
            ->where('role', 'mahasiswa') // pastikan cuma bisa hapus user ber-role mahasiswa lewat route ini
            ->firstOrFail();

        // Mencegah admin menghapus akunnya sendiri secara tidak sengaja -
        // tidak relevan untuk role mahasiswa, tapi pola ini penting kalau
        // nanti Anda buat destroy() serupa untuk admin/ASN.
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete('id');

        return redirect()->route('admin-mahasiswa')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
