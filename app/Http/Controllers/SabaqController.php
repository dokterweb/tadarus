<?php

namespace App\Http\Controllers;

use App\Models\Sabaq;
use App\Models\Siswa;
use App\Models\Madina;
use Illuminate\Http\Request;
use App\Models\Sabaq_history;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SabaqController extends Controller
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
            $sabaqs = Sabaq::all();
        }

        return view('sabaqs.index',compact('sabaqs'));
    }

    public function showSabaqHistory($siswa_id)
    {
       // Ambil data siswa, sabaq dan histori terkait siswa tertentu
       $siswa = Siswa::with(['user', 'kelasnya', 'sabaqs.sabaqHistories.surat'])->findOrFail($siswa_id);
    //    dd($siswa);
        // Mengambil semua sabaq terkait siswa
        $sabaqs = $siswa->sabaqs;
        $sabaq_id = $sabaqs->first()->id;
          // Ambil data surat unik dari tabel madina
        $surat = DB::table('madina')
        ->select('sura_no', 'sura_name', DB::raw('MIN(id) as id'), DB::raw('COUNT(sura_no) as qty_sura'))
        ->groupBy('sura_no', 'sura_name')
        ->orderBy('sura_no')
        ->get();
        
        // Kirim data siswa dan sabaq ke view
        return view('sabaqs.history', compact('siswa','siswa_id', 'sabaqs','surat','sabaq_id'));
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
            'tgl_sabaq'     => 'required|date',
            'surat_no'      => 'required|integer|exists:madina,sura_no', // pastikan surat_no ada di tabel madina
            'dariayat'      => 'required|integer',
            'sampaiayat'    => 'required|integer',
            'nilai'         => 'required|integer',
            'keterangan'    => 'nullable|string',
            'sabaq_id'      => 'required|integer|exists:sabaqs,id', // pastikan sabaq_id ada di tabel sabaqs
        ]);

        // Ambil data surat berdasarkan surat_no (sura_no)
        $surat = DB::table('madina')->where('sura_no', $request->input('surat_no'))->first();

        // Cek apakah surat ditemukan
        if (!$surat) {
            return redirect()->back()->withErrors(['surat_id' => 'Surat tidak ditemukan.']);
        }

        $siswa_id = $request->input('siswa_id'); // Asumsikan siswa_id dikirim dari form

        // Menyimpan data ke tabel sabaq_histories
        $sabaqHistory = new Sabaq_history();
        $sabaqHistory->sabaq_id = $request->input('sabaq_id'); // Menambahkan sabaq_id
        $sabaqHistory->surat_id = $surat->id; // Menambahkan surat_id
        $sabaqHistory->surat_no = $surat->sura_no;
        $sabaqHistory->dariayat = $request->input('dariayat');
        $sabaqHistory->sampaiayat = $request->input('sampaiayat');
        $sabaqHistory->nilai = $request->input('nilai');
        $sabaqHistory->keterangan = $request->input('keterangan');
        $sabaqHistory->tgl_sabaq = $request->input('tgl_sabaq');

        // Simpan ke database
        $sabaqHistory->save();

        // Redirect ke halaman sebelumnya atau halaman yang sesuai
        return redirect()->route('sabaq-history.show', ['siswa_id' => $siswa_id])->with('success', 'Data berhasil disimpan');
    }

    public function edit($siswa_id, $id)
    {
        // Ambil data histori berdasarkan id
        $sabaqHistory = Sabaq_history::findOrFail($id);

        // Ambil data surat terkait
        $surat = DB::table('madina')
                    ->select('sura_no', 'sura_name')
                    ->where('sura_no', $sabaqHistory->surat_id)
                    ->first();  // Ambil data surat terkait

        // Kirim data ke AJAX dalam format JSON
        return response()->json([
            'id'            => $sabaqHistory->id,
            'tgl_sabaq'     => $sabaqHistory->tgl_sabaq,
            'sura_name' => $surat->sura_name,
            'surat_id' => $sabaqHistory->surat_id,
            'jozz' => $sabaqHistory->juz,
            'start_page' => $sabaqHistory->start_page,
            'end_page' => $sabaqHistory->end_page,
            'dariayat' => $sabaqHistory->dariayat,
            'sampaiayat' => $sabaqHistory->sampaiayat,
            'nilai' => $sabaqHistory->nilai,
            'keterangan' => $sabaqHistory->keterangan,
        ]);
    }

    

    public function update(Request $request, $siswa_id, $id)
    {
        // Validasi input dari form
        $request->validate([
            'tgl_sabaq' => 'required|date',
            'surat_id' => 'required|integer|exists:madina,sura_no',
            'dariayat' => 'required|integer',
            'sampaiayat' => 'required|integer',
            'nilai' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);

        // Ambil histori yang akan diupdate
        $sabaqHistory = Sabaq_history::findOrFail($id);

        // Update data
        $sabaqHistory->tgl_sabaq = $request->input('tgl_sabaq');
        $sabaqHistory->surat_id = $request->input('surat_id');
        $sabaqHistory->dariayat = $request->input('dariayat');
        $sabaqHistory->sampaiayat = $request->input('sampaiayat');
        $sabaqHistory->nilai = $request->input('nilai');
        $sabaqHistory->keterangan = $request->input('keterangan');

        // Simpan perubahan
        $sabaqHistory->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('sabaq-history.show', ['siswa_id' => $siswa_id])->with('success', 'Data berhasil diperbarui');
    }


    public function destroy($siswa_id, $id)
    {
        // Temukan history berdasarkan id
        $sabaqHistory = Sabaq_history::findOrFail($id);

        // Hapus data
        $sabaqHistory->delete();

        // Kembalikan response sukses
        return response()->json(['status' => 'success']);
    }

    public function showSiswaHistory()
    {
        // Ambil data siswa yang sedang login
        $siswa = Auth::user()->siswa; // Asumsikan ada relasi antara User dan Siswa
        
        // Ambil semua history sabaq yang terkait dengan siswa
        $sabaqHistories = $siswa->sabaqHistories()->with('surat')->get();

        return view('sabaqs.siswa_history', compact('sabaqHistories'));
    }


}
