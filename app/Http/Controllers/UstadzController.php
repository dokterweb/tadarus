<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ustadz;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UstadzController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ustadzs = Ustadz::all();
        $kelompoks = Kelompok::all();
        return view('ustadzs.index',compact('ustadzs','kelompoks'));
    }

    public function store(Request $request)
    {
         // 1. Validasi input
         $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6', 
            'kelompok_id'  => 'required','integer',
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
    
               
                $user->assignRole('ustadz');
    
                // 4. Simpan ke tabel Ustadz
                Ustadz::create([
                    'user_id'       => $user->id,
                    'kelompok_id'      => $validated['kelompok_id'],
                ]);
            });
    
            // 5. Redirect dengan flash message (dipakai SweetAlert2 di view)
            return redirect()
                ->route('ustadzs')
                ->with('success', 'Data Ustadz berhasil disimpan!');
    
        } catch (\Exception $e) {
            return redirect()
                ->route('ustadzs')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

    }

    public function edit(Ustadz $ustadz)
    {
        $ustadzview = Ustadz::all();
        $kelompoks = Kelompok::all();
        return  view('ustadzs.edit',compact('ustadzview','ustadz','kelompoks'));
    }

    public function update(Request $request, Ustadz $ustadz)
    {
        // 1️⃣ Validasi input
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $ustadz->user_id,
            'password'     => 'nullable|string|min:6', // optional, hanya diupdate kalau diisi
            'kelompok_id'  => 'required|integer',
        ]);
    
        try {
            DB::transaction(function () use ($validated, $ustadz) {
    
                // 2️⃣ Ambil data user terkait
                $user = User::findOrFail($ustadz->user_id);
    
                // 3️⃣ Update data user
                $userData = [
                    'name'  => $validated['name'],
                    'email' => $validated['email'],
                ];
    
                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }
    
                $user->update($userData);
    
                // 4️⃣ Pastikan role tetap "ustadz"
                $user->syncRoles(['ustadz']);
    
                // 5️⃣ Update tabel Ustadz
                $ustadz->update([
                    'kelompok_id' => $validated['kelompok_id'],
                ]);
            });
    
            // 6️⃣ Jika berhasil
            return redirect()
                ->route('ustadzs')
                ->with('success', 'Data Ustadz berhasil diperbarui!');
    
        } catch (\Exception $e) {
            // 7️⃣ Jika gagal
            return redirect()
                ->route('ustadzs')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
     
    public function destroy(Ustadz $ustadz)
    {
        try {
            DB::transaction(function () use ($ustadz) {
    
                // Ambil user terkait
                $user = User::find($ustadz->user_id);
    
                if ($user) {
                    // Lepas semua role user ini
                    $user->syncRoles([]);
    
                    // Hapus user (soft delete kalau model User pakai SoftDeletes)
                    $user->delete();
                }
    
                // Hapus data ustadz
                $ustadz->delete();
            });
    
            return redirect()
                ->route('ustadzs')
                ->with('success', 'Data Ustadz berhasil dihapus!');
    
        } catch (\Exception $e) {
            return redirect()
                ->route('ustadzs')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
