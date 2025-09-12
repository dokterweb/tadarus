<?php

namespace App\Http\Controllers;

use App\Models\Posnya;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        $posnyas = Posnya::all();
        return view('posnyas.index',compact('posnyas'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'pos_name' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255'
        ]);

        try {
            // Simpan data ke database
            Posnya::create([
                'pos_name'  => $request->pos_name,
                'keterangan' => $request->keterangan,
            ]);

            // Notifikasi sukses
            return redirect()->route('posnyas')
                             ->with('success', 'Data kelasnya berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('posnyas')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Posnya $posnya)
    {
        // dd($posnya);
        $posnyas = Posnya::all();
        return view('posnyas.edit',compact('posnyas','posnya'));
    }

    public function update(Request $request, Posnya $posnya)
    {
        // Validasi data input
        $request->validate([
            'pos_name' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255'
        ]);

        // Update data periode yang ditemukan berdasarkan ID
        $posnya->update([
            'pos_name'      => $request->pos_name,
            'keterangan'    => $request->keterangan,
        ]);

        // Redirect ke halaman daftar periode dengan pesan sukses
        return redirect()->route('posnyas')->with('success', 'POS berhasil diperbarui!');
    }

    public function destroy(Posnya $posnya)
    {
        // Menghapus data periode
        $posnya->delete();

        // Redirect ke halaman daftar periode dengan pesan sukses
        return redirect()->route('posnyas')->with('success', 'POS berhasil dihapus!');
    }

}
