<?php

namespace App\Http\Controllers;

use App\Models\Kelasnya;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelasnya = Kelasnya::all();
        return view('kelasv.index',compact('kelasnya'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_kelas' => 'required|string|max:255'
        ]);

        try {
            // Simpan data ke database
            Kelasnya::create([
                'nama_kelas' => $request->nama_kelas, // Sesuaikan dengan nama kolom di database
            ]);

            // Notifikasi sukses
            return redirect()->route('kelasnyas')
                             ->with('success', 'Data kelasnya berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('kelasnyas')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Kelasnya $kelasnya)
    {
        $kelasview = Kelasnya::all();
        // dd($kelasnya);
        return view('kelasv.edit',compact('kelasnya','kelasview'));
    }

    public function update(Request $request, Kelasnya $kelasnya)
    {
        // Validasi input
        $request->validate([
            'nama_kelas' => 'required|string|max:255'
        ]);

        try {
            // Update data KelasSub
            $kelasnya->update([
                'nama_kelas' => $request->nama_kelas, // Sesuaikan dengan nama kolom di database
            ]);

            // Notifikasi sukses
            return redirect()->route('kelasnyas')
                            ->with('success', 'Data KelasSub berhasil diperbarui.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('kelasnyas')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
{
    try {
        // Temukan kelas berdasarkan ID
        $kelasnya = Kelasnya::findOrFail($id);
        
        // Hapus data
        $kelasnya->delete();

        // Redirect dengan notifikasi sukses
        return redirect()->route('kelasnyas')
                         ->with('success', 'Data kelas berhasil dihapus.');
    } catch (\Exception $e) {
        // Jika terjadi kesalahan
        return redirect()->route('kelasnyas')
                         ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

}
