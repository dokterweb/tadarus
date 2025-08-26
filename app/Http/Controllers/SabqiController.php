<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Sabqi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Sabqi_history;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SabqiController extends Controller
{
    public function index()
    {
         // Cek jika yang login adalah ustadz
         if (auth()->user()->hasRole('ustadz')) {
            // Ambil ustadz_id dari ustadz yang sedang login
            $ustadz_id = auth()->user()->ustadz->id;
    
            // Ambil data Sabaq yang memiliki siswa dengan ustadz_id yang sama dengan ustadz.id
            $sabaqs = Sabaq::whereHas('siswa', function($query) use ($ustadz_id) {
                $query->where('ustadz_id', $ustadz_id);
            })->get();
        } else {
            // Jika bukan ustadz, ambil semua data Sabaq
            $sabqis = Sabqi::all();
        }
        
        return view('sabqis.index',compact('sabqis'));
    }

    public function showSabqiHistory($siswa_id)
    {
       // Ambil data siswa, sabqi dan histori terkait siswa tertentu
        $siswa = Siswa::with(['user', 'kelasnya', 'sabqis.sabqiHistories.surat'])->findOrFail($siswa_id);

        // Mengambil semua sabqi terkait siswa
        $sabqis = $siswa->sabqis;
        $sabqi_id = $sabqis->first()->id;
          // Ambil data surat unik dari tabel madina
        $surat = DB::table('madina')
        ->select('sura_no', 'sura_name', DB::raw('MIN(id) as id'), DB::raw('COUNT(sura_no) as qty_sura'))
        ->groupBy('sura_no', 'sura_name')
        ->orderBy('sura_no')
        ->get();
        
        // Kirim data siswa dan sabqi ke view
        return view('sabqis.history', compact('siswa','siswa_id', 'sabqis','surat','sabqi_id'));
    }

    public function getSuratDetails($sura_no)
    {
        // Menjalankan query dengan Query Builder untuk mengambil data
        $surat = DB::table('madina')
        ->selectRaw('
            id AS suratId,
            sura_no AS no_surat, 
            jozz, 
            sura_name,
            COUNT(*) AS qty_sura,
            MIN(page) AS start_page,
            MAX(page) AS end_page
        ')
        ->where('sura_no', $sura_no)
        ->groupBy('sura_no', 'sura_name', 'jozz','id')  // Menambahkan 'jozz' ke dalam GROUP BY
        ->orderBy('sura_no')
        ->first();
    
        // Cek apakah data ditemukan
        if ($surat) {
            return response()->json(['status' => 'success', 'data' => $surat]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Surat tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'tgl_sabqi'     => 'required|date',
            'surat_no'      => 'required|integer|exists:madina,sura_no', // pastikan surat_no ada di tabel madina
            'dariayat'      => 'required|integer',
            'sampaiayat'    => 'required|integer',
            'nilai'         => 'required|integer',
            'keterangan'    => 'nullable|string',
            'sabqi_id'      => 'required|integer|exists:sabqis,id', // pastikan sabqi_id ada di tabel sabqis
        ]);

        // Ambil data surat berdasarkan surat_no (sura_no)
        $surat = DB::table('madina')->where('sura_no', $request->input('surat_no'))->first();

        // Cek apakah surat ditemukan
        if (!$surat) {
            return redirect()->back()->withErrors(['surat_id' => 'Surat tidak ditemukan.']);
        }

        $siswa_id = $request->input('siswa_id'); // Asumsikan siswa_id dikirim dari form

        // Menyimpan data ke tabel sabqi_histories
        $sabqiHistory = new sabqi_history();
        $sabqiHistory->sabqi_id = $request->input('sabqi_id'); // Menambahkan sabqi_id
        $sabqiHistory->surat_id = $surat->id; // Menambahkan surat_id
        $sabqiHistory->surat_no = $surat->sura_no;
        $sabqiHistory->dariayat = $request->input('dariayat');
        $sabqiHistory->sampaiayat = $request->input('sampaiayat');
        $sabqiHistory->nilai = $request->input('nilai');
        $sabqiHistory->keterangan = $request->input('keterangan');
        $sabqiHistory->tgl_sabqi = $request->input('tgl_sabqi');

        // Simpan ke database
        $sabqiHistory->save();

        // Redirect ke halaman sebelumnya atau halaman yang sesuai
        return redirect()->route('sabqi-history.show', ['siswa_id' => $siswa_id])->with('success', 'Data berhasil disimpan');
    }

    public function destroy($siswa_id, $id)
    {
        // Temukan history berdasarkan id
        $sabqiHistory = Sabqi_history::findOrFail($id);

        // Hapus data
        $sabqiHistory->delete();

        // Kembalikan response sukses
        return response()->json(['status' => 'success']);
    }

    public function showSiswaHistory()
    {
        // Ambil data siswa yang sedang login
        $siswa = Auth::user()->siswa; // Asumsikan ada relasi antara User dan Siswa
        
        // Ambil semua history sabqi yang terkait dengan siswa
        $sabqiHistories = $siswa->sabqiHistories()->with('surat')->get();

        return view('sabqis.siswa_history', compact('sabqiHistories'));
    }

}
