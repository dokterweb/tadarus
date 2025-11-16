<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Adminsiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminsiswaController extends Controller
{
    public function index()
    {
        $adminnya = Adminsiswa::all();
        return view('adminsiswas.index',compact('adminnya'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6', // kalau pakai password_confirmation
            'jenis'     => 'required|in:putra,putri',          // hanya putra / putri
        ]);
    
        try {
            DB::transaction(function () use ($validated) {
    
                // 2. Buat user baru
                $user = User::create([
                    'name'      => $validated['name'],
                    'email'     => $validated['email'],
                    'avatar'    => 'images/default-avatar.png', // atau null, sesuai kebutuhanmu
                    'password'  => Hash::make($validated['password']),
                ]);
    
                // 3. Tentukan role berdasarkan jenis
                $roleName = $validated['jenis'] === 'putra'
                    ? 'adminputra'
                    : 'adminputri';
    
                $user->assignRole($roleName);
    
                // 4. Simpan ke tabel adminsiswas
                Adminsiswa::create([
                    'user_id' => $user->id,
                    'jenis'   => $validated['jenis'],
                ]);
            });
    
            // 5. Redirect dengan flash message (dipakai SweetAlert2 di view)
            return redirect()
                ->route('adminsiswas')
                ->with('success', 'Data Admin Siswa berhasil disimpan!');
    
        } catch (\Exception $e) {
            return redirect()
                ->route('adminsiswas')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Adminsiswa $adminsiswa)
    {
        $adminview = Adminsiswa::all();
        // dd($Adminsiswa);
        return view('adminsiswas.edit',compact('adminsiswa','adminview'));
    }
    
    public function update(Request $request, Adminsiswa $adminsiswa)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $adminsiswa->user_id,
            'password'  => 'nullable|string|min:6',
            'jenis'     => 'required|in:putra,putri',
        ]);

        try {
            DB::transaction(function () use ($validated, $adminsiswa) {

                // 2. Ambil user terkait
                $user = User::findOrFail($adminsiswa->user_id);

                // 3. Siapkan data user yang di-update
                $dataUser = [
                    'name'  => $validated['name'],
                    'email' => $validated['email'],
                ];

                // Kalau password diisi, baru di-update
                if (!empty($validated['password'])) {
                    $dataUser['password'] = Hash::make($validated['password']);
                }

                $user->update($dataUser);

                // 4. Tentukan role berdasarkan jenis
                $roleName = $validated['jenis'] === 'putra'
                    ? 'adminputra'
                    : 'adminputri';

                // Ganti role lama dengan role baru
                $user->syncRoles([$roleName]);

                // 5. Update data adminsiswa
                $adminsiswa->update([
                    'jenis' => $validated['jenis'],
                ]);
            });

            // 6. Redirect + flash message (dipakai SweetAlert2 di view)
            return redirect()
                ->route('adminsiswas')
                ->with('success', 'Data Admin Siswa berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()
                ->route('adminsiswas')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Adminsiswa $adminsiswa)
    {
        try {
            DB::transaction(function () use ($adminsiswa) {

                // Ambil user terkait
                $user = User::find($adminsiswa->user_id);

                if ($user) {
                    // Hapus semua role user ini
                    $user->syncRoles([]);

                    // Hapus user (soft delete jika model User pakai SoftDeletes)
                    $user->delete();
                }

                // Hapus data adminsiswa (soft delete juga kalau modelnya pakai SoftDeletes)
                $adminsiswa->delete();
            });

            return redirect()
                ->route('adminsiswas.index')
                ->with('success', 'Data Admin Siswa berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()
                ->route('adminsiswas.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
