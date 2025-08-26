<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Manzil;
use App\Models\Manzil_history;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManzilController extends Controller
{
    public function index()
    {
        // Cek jika yang login adalah ustadz
        if (auth()->user()->hasRole('ustadz')) {
            // Ambil ustadz_id dari ustadz yang sedang login
            $ustadz_id = auth()->user()->ustadz->id;
    
            // Ambil data manzil yang memiliki siswa dengan ustadz_id yang sama dengan ustadz.id
            $manzils = manzil::whereHas('siswa', function($query) use ($ustadz_id) {
                $query->where('ustadz_id', $ustadz_id);
            })->get();
        } else {
            // Jika bukan ustadz, ambil semua data manzil
            $manzils = Manzil::all();
        }

        return view('manzils.index',compact('manzils'));
    }

    public function showManzilHistory($siswa_id)
    {
       // Ambil data siswa, manzil dan histori terkait siswa tertentu
        $siswa = Siswa::with(['user', 'kelasnya', 'manzils.manzilHistories.surat'])->findOrFail($siswa_id);

        // Mengambil semua manzil terkait siswa
        $manzils = $siswa->manzils;
        $manzil_id = $manzils->first()->id;
          // Ambil data surat unik dari tabel madina
        $surat = DB::table('madina')
        ->select('sura_no', 'sura_name', DB::raw('MIN(id) as id'), DB::raw('COUNT(sura_no) as qty_sura'))
        ->groupBy('sura_no', 'sura_name')
        ->orderBy('sura_no')
        ->get();
        
        // Kirim data siswa dan manzil ke view
        return view('manzils.history', compact('siswa','siswa_id', 'manzils','surat','manzil_id'));
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
            'tgl_manzil'     => 'required|date',
            'surat_no'      => 'required|integer|exists:madina,sura_no', // pastikan surat_no ada di tabel madina
            'dariayat'      => 'required|integer',
            'sampaiayat'    => 'required|integer',
            'nilai'         => 'required|integer',
            'keterangan'    => 'nullable|string',
            'manzil_id'      => 'required|integer|exists:manzils,id', // pastikan manzil_id ada di tabel manzils
        ]);

        // Ambil data surat berdasarkan surat_no (sura_no)
        $surat = DB::table('madina')->where('sura_no', $request->input('surat_no'))->first();

        // Cek apakah surat ditemukan
        if (!$surat) {
            return redirect()->back()->withErrors(['surat_id' => 'Surat tidak ditemukan.']);
        }

        $siswa_id = $request->input('siswa_id'); // Asumsikan siswa_id dikirim dari form

        // Menyimpan data ke tabel manzil_histories
        $manzilHistory = new manzil_history();
        $manzilHistory->manzil_id = $request->input('manzil_id'); // Menambahkan manzil_id
        $manzilHistory->surat_id = $surat->id; // Menambahkan surat_id
        $manzilHistory->surat_no = $surat->sura_no;
        $manzilHistory->dariayat = $request->input('dariayat');
        $manzilHistory->sampaiayat = $request->input('sampaiayat');
        $manzilHistory->nilai = $request->input('nilai');
        $manzilHistory->keterangan = $request->input('keterangan');
        $manzilHistory->tgl_manzil = $request->input('tgl_manzil');

        // Simpan ke database
        $manzilHistory->save();

        // Redirect ke halaman sebelumnya atau halaman yang sesuai
        return redirect()->route('manzil-history.show', ['siswa_id' => $siswa_id])->with('success', 'Data berhasil disimpan');
    }

    public function destroy($siswa_id, $id)
    {
        // Temukan history berdasarkan id
        $manzilHistory = Manzil_history::findOrFail($id);

        // Hapus data
        $manzilHistory->delete();

        // Kembalikan response sukses
        return response()->json(['status' => 'success']);
    }

    public function showSiswaHistory()
    {
        // Ambil data siswa yang sedang login
        $siswa = Auth::user()->siswa; // Asumsikan ada relasi antara User dan Siswa
        
        // Ambil semua history sabaq yang terkait dengan siswa
        $manzilHistories = $siswa->manzilHistories()->with('surat')->get();

        return view('manzils.siswa_history', compact('manzilHistories'));
    }

}
