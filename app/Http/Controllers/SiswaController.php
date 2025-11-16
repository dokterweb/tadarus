<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sabaq;
use App\Models\Sabqi;
use App\Models\Siswa;
use App\Models\Manzil;
use App\Models\Ustadz;
use App\Models\Kelasnya;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswas = Siswa::all();
        $kelompoks = Kelompok::all();
        $kelasnya = Kelasnya::all();
        return view('siswas.index',compact('siswas','kelompoks','kelasnya'));
    }

  
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_siswa'    => 'required|string|max:255',
            'kelompok_id'   => 'required','integer',
            'kelas_id'      => 'required','integer',
            'kelamin'       => 'required|in:laki-laki,perempuan',
        ]);

        try {
            // Simpan data ke database
            Siswa::create([
                'nama_siswa'    => $request->nama_siswa,
                'kelompok_id'   => $request->kelompok_id,
                'kelas_id'      => $request->kelas_id,
                'kelamin'       => $request->kelamin,
            ]);

           // Notifikasi sukses
            return redirect()->route('siswas')
                             ->with('success', 'Data siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('siswas')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Siswa $siswa)
    {
        $siswaview = Siswa::all();
        $kelompoks = Kelompok::all();
        $kelasnya = Kelasnya::all();
        return view('siswas.edit',compact('kelasnya','kelompoks','siswa','siswaview'));
    }

    
    public function update(Request $request, Siswa $siswa)
    {
        // 1️⃣ Validasi input
        $validated = $request->validate([
            'nama_siswa'   => 'required|string|max:255',
            'kelompok_id'  => 'required|integer',
            'kelas_id'     => 'required|integer',
            'kelamin'      => 'required|in:laki-laki,perempuan',
        ]);

        try {
            // 2️⃣ Update data siswa di database
            $siswa->update([
                'nama_siswa'   => $validated['nama_siswa'],
                'kelompok_id'  => $validated['kelompok_id'],
                'kelas_id'     => $validated['kelas_id'],
                'kelamin'      => $validated['kelamin'],
            ]);

            // 3️⃣ Notifikasi sukses
            return redirect()->route('siswas')
                            ->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            // 4️⃣ Notifikasi error
            return redirect()->route('siswas')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Siswa $siswa)
    {
        try {
            // Hapus data siswa
            $siswa->delete();
    
            // Notifikasi sukses
            return redirect()
                ->route('siswas')
                ->with('success', 'Data siswa berhasil dihapus.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()
                ->route('siswas')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    public function getUstadzByKelas($kelas_id)
    {
        // Mengambil ustadz berdasarkan kelas_id
        $ustadz = Ustadz::where('kelas_id', $kelas_id)->with('user')->get();
        
        // Mengembalikan data sebagai JSON
        return response()->json($ustadz);
    }

}
