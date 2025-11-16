<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TadarusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TadarusController extends Controller
{
    public function index()
    {
        $siswas = Siswa::all();
        return view('tadarus.index',compact('siswas'));
    }

    public function showTadarusHistory($siswa_id)
    {
        // Ambil data siswa beserta relasi-relasinya
        // GANTI isi array with() sesuai nama relasi di model Siswa
        // misal: 'kelompok', 'kelas', 'tadarusHistories'
        $siswa = Siswa::with([
            'kelompok',
            'kelasnya',
            'tadarusHistories.surat',   // contoh relasi riwayat tadarus
        ])->findOrFail($siswa_id);
        // dd($siswa);
        // Ambil data surat dari tabel madina
        $surat = DB::table('madina')
            ->select(
                'sura_no',
                'sura_name',
                DB::raw('MIN(id) as id'),
                DB::raw('COUNT(sura_no) as qty_sura')
            )
            ->groupBy('sura_no', 'sura_name')
            ->orderBy('sura_no')
            ->get();

        // Kirim data ke view
        return view('tadarus.history', compact('siswa', 'siswa_id', 'surat'));
    }

    public function getSuratDetails($sura_no)
    {
        // Mengambil data surat berdasarkan sura_no
        $suratData = DB::table('madina')
            ->where('sura_no', $sura_no)
            ->orderBy('page', 'asc')  // Mengurutkan berdasarkan halaman pertama (ascending)
            ->first();  // Ambil baris pertama dari hasil query
    
        // Mengambil halaman pertama (start_page) dan halaman terakhir (end_page)
        $pages = DB::table('madina')
            ->where('sura_no', $sura_no)
            ->selectRaw('MIN(page) AS start_page, MAX(page) AS end_page')  // Mengambil halaman pertama dan terakhir
            ->first();
    
        // Cek apakah data surat ditemukan
        if ($suratData && $pages) {
            // Membuat objek hasil dengan semua data yang diperlukan
            $result = (object)[
                'suratId'       => $suratData->id, // ID dari surat
                'no_surat'      => $suratData->sura_no, // Nomor surat
                'jozz'          => $suratData->jozz, // Juz
                'sura_name'     => $suratData->sura_name, // Nama surat
                'start_page'    => $pages->start_page, // Halaman pertama
                'end_page'      => $pages->end_page, // Halaman terakhir
            ];
    
            return response()->json(['status' => 'success', 'data' => $result]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Surat tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'tgl_tadarusnya'=> 'required|date',
            'surat_no'      => 'required|integer|exists:madina,sura_no', // pastikan surat_no ada di tabel madina
            'dariayat'      => 'required|integer',
            'sampaiayat'    => 'required|integer',
            'keterangan'    => 'required|string',
            'siswa_id'      => 'required|integer|exists:siswas,id',
        ]);

        // Ambil data surat berdasarkan surat_no (sura_no)
        $surat = DB::table('madina')->where('sura_no', $request->input('surat_no'))->first();

        // Cek apakah surat ditemukan
        if (!$surat) {
            return redirect()->back()->withErrors(['surat_id' => 'Surat tidak ditemukan.']);
        }

        $siswa_id = $request->input('siswa_id'); // Asumsikan siswa_id dikirim dari form

        // Menyimpan data ke tabel sabaq_histories
        $tadarusHistory = new TadarusHistory();
        $tadarusHistory->surat_id       = $surat->id;
        $tadarusHistory->surat_no       = $surat->sura_no;
        $tadarusHistory->siswa_id       = $request->siswa_id;
        $tadarusHistory->dariayat       = $request->input('dariayat');
        $tadarusHistory->sampaiayat     = $request->input('sampaiayat');
        $tadarusHistory->keterangan     = $request->input('keterangan');
        $tadarusHistory->tgl_tadarusnya = $request->input('tgl_tadarusnya');

        // Simpan ke database
        $tadarusHistory->save();

        // Redirect ke halaman sebelumnya atau halaman yang sesuai
        return redirect()->route('tadarus-history.show', ['siswa_id' => $siswa_id])->with('success', 'Data berhasil disimpan');
    }

   public function getHistory($id)
    {
        $history = TadarusHistory::find($id);

        if (!$history) {
            return response()->json([
                'status'  => 'error',
                'message' => 'History tidak ditemukan'
            ], 404);
        }

        // Surat yang sedang dipakai di history ini
        $surat = DB::table('madina')
            ->where('id', $history->surat_id)
            ->first();

        // List semua surat (untuk isi dropdown)
        $suratList = DB::table('madina')
            ->select('sura_no', 'sura_name', DB::raw('MIN(id) as id'), DB::raw('COUNT(sura_no) as qty_sura'))
            ->groupBy('sura_no', 'sura_name')
            ->orderBy('sura_no')
            ->get();

        return response()->json([
            'status'    => 'success',
            'data'      => $history,
            'surat'     => $surat,
            'suratList' => $suratList,
        ]);
    }
    
    public function updateHistory(Request $request, $id)
    {
        $validated = $request->validate([
            'tgl_tadarusnya' => 'required|date',
            'surat_no'       => 'required|integer|exists:madina,sura_no',
            'dariayat'       => 'required|integer|min:1',
            'sampaiayat'     => 'required|integer|min:1|gte:dariayat',
            'keterangan'     => 'required|string',
            'siswa_id'       => 'required|integer|exists:siswas,id',
        ]);

        try {
            $history = TadarusHistory::findOrFail($id);

            $surat = DB::table('madina')
                ->where('sura_no', $validated['surat_no'])
                ->first();

            if (!$surat) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Surat tidak ditemukan'
                ], 404);
            }

            $history->update([
                'siswa_id'       => $validated['siswa_id'],
                'surat_id'       => $surat->id,
                'surat_no'       => $surat->sura_no,
                'dariayat'       => $validated['dariayat'],
                'sampaiayat'     => $validated['sampaiayat'],
                'keterangan'     => $validated['keterangan'],
                'tgl_tadarusnya' => $validated['tgl_tadarusnya'],
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
{
    try {
        $tadarusHistory = TadarusHistory::findOrFail($id);

        // Hapus data tadarus history
        $tadarusHistory->delete();

        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

}
