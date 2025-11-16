<?php

namespace App\Http\Controllers;

use App\Models\Jenisiqro;
use Illuminate\Http\Request;

class JenisiqroController extends Controller
{
    public function index()
    {
        $jenisiqros = Jenisiqro::all();
        return view('jenisiqros.index',compact('jenisiqros'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_iqro' => 'required|string|max:255',
        ]);

        try {
            // Simpan data ke database
            Jenisiqro::create([
                'nama_iqro' => $request->nama_iqro,
            ]);

            // Notifikasi sukses
            return redirect()->route('jenisiqros')
                             ->with('success', 'Data Iqra berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('jenisiqros')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Jenisiqro $jenisiqro)
    {
        $jenisiqros = Jenisiqro::all();
        // dd($kelasnya);
        return view('jenisiqros.edit',compact('jenisiqro','jenisiqros'));
    }

    public function update(Request $request, Jenisiqro $jenisiqro)
    {
        // Validasi input
        $request->validate([
            'nama_iqro' => 'required|string|max:255',
        ]);

        try {
            // Update data KelasSub
            $jenisiqro->update([
                'nama_iqro' => $request->nama_iqro,
            ]);

            // Notifikasi sukses
            return redirect()->route('jenisiqros')
                            ->with('success', 'Data Iqro berhasil diperbarui.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('jenisiqros')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan kelas berdasarkan ID
            $jenisiqro = Jenisiqro::findOrFail($id);
            
            // Hapus data
            $jenisiqro->delete();

            // Redirect dengan notifikasi sukses
            return redirect()->route('jenisiqros')
                            ->with('success', 'Data kelas berhasil dihapus.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan
            return redirect()->route('jenisiqros')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
