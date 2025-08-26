<?php

namespace App\Http\Controllers;

use App\Models\Iqro;
use App\Models\Siswa;
use App\Models\Iqro_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IqroController extends Controller
{
    public function index()
    {
        $iqros = Iqro::all();
        return view('iqros.index',compact('iqros'));
    }

    public function showIqroHistory($siswa_id)
    {
        // Ambil data siswa beserta iqro dan iqro histories
        $siswa = Siswa::with(['user', 'kelasnya', 'iqros.iqroHistories'])->findOrFail($siswa_id);
    
        // Mengambil semua iqro terkait siswa
        $iqros = $siswa->iqros;
        $iqro_id = $iqros->first()->id;
        
        // Mengambil iqro_histories terkait siswa
        $iqroHistories = $siswa->iqros->flatMap(function($iqro) {
            return $iqro->iqroHistories; // Mengambil semua iqro histories terkait iqro
        });
    
        // Kirim data siswa, iqro, iqroHistories ke view
        return view('iqros.history', compact('siswa', 'siswa_id', 'iqros', 'iqro_id', 'iqroHistories'));
    }
    
    public function store(Request $request, $siswa_id)
    {
        // Validasi input dari form
        $request->validate([
            'tgl_iqro'      => 'required|date',
            'iqro_jilid'    => 'required|string',
            'halaman'       => 'required|integer',
            'nilai'         => 'required|integer',
            'keterangan'    => 'nullable|string',
            'iqro_id'      => 'required|integer|exists:iqros,id', 
        ]);
        $siswa_id = $request->input('siswa_id'); // Asumsikan siswa_id dikirim dari form
        // Menyimpan data ke tabel iqro_histories
        $iqroHistory = new Iqro_history();
        $iqroHistory->iqro_id = $request->iqro_id; 
        $iqroHistory->iqro_jilid = $request->iqro_jilid;
        $iqroHistory->halaman = $request->halaman;
        $iqroHistory->nilai = $request->nilai;
        $iqroHistory->keterangan = $request->keterangan;
        $iqroHistory->tgl_iqro = $request->tgl_iqro;
        
        // Simpan data
        $iqroHistory->save();
    
        // Redirect kembali ke halaman history iqro siswa
        return redirect()->route('iqro-history.show', ['siswa_id' => $siswa_id])
                         ->with('success', 'Data iqro berhasil disimpan.');
    }
    
    public function edit($siswa_id, $id)
    {
        // Ambil data iqro_history berdasarkan id
        $iqroHistory = Iqro_history::findOrFail($id);

        // Kirim data iqroHistory ke AJAX
        return response()->json([
            'iqroHistory' => $iqroHistory,
        ]);
    }


    public function update(Request $request, $siswa_id, $id)
    {
        // Validasi input
        $request->validate([
            'tgl_iqro'   => 'required|date',
            'iqro_jilid' => 'required|string',
            'halaman'    => 'required|integer',
            'nilai'      => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);
    
        // Temukan iqro_history berdasarkan id
        $iqroHistory = Iqro_history::findOrFail($id);
    
        // Update data iqro_history
        $iqroHistory->iqro_jilid = $request->iqro_jilid;
        $iqroHistory->halaman = $request->halaman;
        $iqroHistory->nilai = $request->nilai;
        $iqroHistory->keterangan = $request->keterangan;
        $iqroHistory->tgl_iqro = $request->tgl_iqro;
    
        // Simpan perubahan
        $iqroHistory->save();
    
        // Redirect kembali ke halaman history iqro siswa
        return redirect()->route('iqro-history.show', ['siswa_id' => $siswa_id])
                         ->with('success', 'Data iqro berhasil diperbarui.');
    }
    
    public function showSiswaHistory()
    {
        // Ambil data siswa yang sedang login
        $siswa = Auth::user()->siswa; // Asumsikan ada relasi antara User dan Siswa
        
        // Ambil semua history sabaq yang terkait dengan siswa
        $iqroHistories = $siswa->iqroHistories()->get();

        return view('iqros.siswa_history', compact('iqroHistories'));
    }
    
}
