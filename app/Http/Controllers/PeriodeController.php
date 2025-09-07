<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = Periode::all();
        return view('periodes.index',compact('periodes'));
    }

    public function store(Request $request)
    {

        // Validasi data yang diterima dari form
        $validated = $request->validate([
            'periode_start'     => 'required|digits:4',  // Validasi tahun awal harus 4 digit
            'periode_end'       => 'required|digits:4',    // Validasi tahun akhir harus 4 digit
            'periode_status'    => 'required|in:0,1',   // Validasi status hanya bisa 0 atau 1
        ]);

        // Simpan data ke database
        Periode::create([
            'periode_start'     => $request->periode_start,
            'periode_end'       => $request->periode_end,
            'periode_status'    => $request->periode_status,
        ]);

        // Redirect atau memberikan pesan sukses
        return redirect()->route('periodes')->with('success', 'Data periode berhasil disimpan!');
    }

    public function edit(Periode $periode)
    {
        $periodenya = Periode::all();
        return view('periodes.edit',compact('periodenya', 'periode'));
    }

    public function update(Request $request, Periode $periode)
    {
        // Validasi data input
        $request->validate([
            'periode_start' => 'required|integer|digits:4',  // Validasi tahun harus berupa angka 4 digit
            'periode_end' => 'required|integer|digits:4|gte:periode_start', // Periode akhir harus lebih besar atau sama dengan periode awal
            'periode_status' => 'required|in:0,1',  // Status hanya bisa 0 atau 1
        ]);

        // Update data periode yang ditemukan berdasarkan ID
        $periode->update([
            'periode_start' => $request->periode_start,
            'periode_end' => $request->periode_end,
            'periode_status' => $request->periode_status,
        ]);

        // Redirect ke halaman daftar periode dengan pesan sukses
        return redirect()->route('periodes')->with('success', 'Periode berhasil diperbarui!');
    }

    public function destroy(Periode $periode)
    {
        // Menghapus data periode
        $periode->delete();

        // Redirect ke halaman daftar periode dengan pesan sukses
        return redirect()->route('periodes')->with('success', 'Periode berhasil dihapus!');
    }

}
