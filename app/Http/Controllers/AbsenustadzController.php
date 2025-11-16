<?php

namespace App\Http\Controllers;

use App\Models\Ustadz;
use App\Models\Absensi_ustadz;
use Illuminate\Http\Request;

class AbsenustadzController extends Controller
{
    public function index()
    {
        $ustadzs = Ustadz::all();
        // Ambil data absensi ustadz dengan pagination
        $absensiUstadzs = Absensi_ustadz::with('ustadz')  // Ambil relasi ustadz
            ->paginate(10);  // Menampilkan 10 data per halaman

        return view('absensi_ustadzs.index', compact('absensiUstadzs','ustadzs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_absen'     => 'required|date',
            'status'        => 'required|in:hadir,ghoib,izin,tugas,sakit,pulang',
            'ustadz_id'     => 'required|exists:ustadzs,id',
            'keterangan'    => 'nullable|string',
        ]);

       
        try {
            Absensi_ustadz::create([
                'tgl_absen'     => $request->tgl_absen,
                'status'        => $request->status,
                'ustadz_id'     => $request->ustadz_id,
                'keterangan'    => $request->keterangan,
            ]);

            // Notifikasi sukses
            return redirect()->route('absensiustadz.index')
                             ->with('success', 'Data Absensi berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('absensiustadz.index')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        
        return redirect()->route('absensiustadz.index')->with('success', 'Absensi berhasil disimpan');
    }

     
    public function edit($id)
    {
        // Ambil absensi berdasarkan id
        $absensi = Absensi_ustadz::findOrFail($id);

        // Ambil semua ustadz untuk dropdown
        $ustadzs = Ustadz::all();  

        // Ambil absensi ustadz berdasarkan ustadz_id (dengan pagination)
        $absensiUstadzs = Absensi_ustadz::with('ustadz')  // Ambil relasi ustadz
        ->paginate(10);  // Menampilkan 10 data per halaman
        
        return view('absensi_ustadzs.edit', compact('absensi', 'ustadzs', 'absensiUstadzs'));
    }

    public function update(Request $request, Absensi_ustadz $absensi_ustadz)
    {
        // Validasi input
        $request->validate([
            'tgl_absen'     => 'required|date',
            'status'        => 'required|in:hadir,ghoib,izin,tugas,sakit,pulang',
            'ustadz_id'     => 'required|exists:ustadzs,id',
            'keterangan'    => 'nullable|string',
        ]);

        try {
            // Update data KelasSub
            $absensi_ustadz->update([
                'tgl_absen'     => $request->tgl_absen,
                'status'        => $request->status,
                'ustadz_id'     => $request->ustadz_id,
                'keterangan'    => $request->keterangan,
            ]);

            // Notifikasi sukses
            return redirect()->route('absensiustadz.index')
                            ->with('success', 'Data Absensi Ustadz berhasil diperbarui.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('absensiustadz.index')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $absensi = Absensi_ustadz::findOrFail($id);
            $absensi->delete();

            return redirect()->route('absensiustadz.index')->with('success', 'Absensi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('absensiustadz.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
