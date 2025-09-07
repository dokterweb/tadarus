<?php

namespace App\Http\Controllers;

use Log;
use PDF;
use Carbon\Carbon;
use App\Models\Sabqi;
use App\Models\Siswa;
use App\Exports\SabqiExport;
use Illuminate\Http\Request;
use App\Models\Sabqi_history;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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
/* 
    public function getSuratDetails($sura_no)
    {
        // Menjalankan query dengan Query Builder untuk mengambil data
        $surat = DB::table('madina')
        ->selectRaw('
            id AS suratId,
            sura_no AS no_surat, 
            jozz, 
            sura_name,
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
    }  */
    
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
                'suratId' => $suratData->id, // ID dari surat
                'no_surat' => $suratData->sura_no, // Nomor surat
                'jozz' => $suratData->jozz, // Juz
                'sura_name' => $suratData->sura_name, // Nama surat
                'start_page' => $pages->start_page, // Halaman pertama
                'end_page' => $pages->end_page, // Halaman terakhir
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

    public function edit($id)
    {
        // Ambil data histori berdasarkan id
        $history  = Sabqi_history::findOrFail($id);
        // dd($history);
        // Ambil data surat terkait menggunakan query khusus
        $surat = DB::table('madina')
        ->selectRaw('
            sura_no AS no_surat,
            jozz,
            sura_name,
            COUNT(*) AS qty_sura,
            MIN(page) AS start_page,
            MAX(page) AS end_page
        ')
        ->where('sura_no', $history->surat_no)  // Menggunakan surat_no yang ada di history
        ->groupBy('sura_no', 'sura_name', 'jozz')  // Kelompokkan berdasarkan sura_no, sura_name, dan jozz
        ->orderBy('sura_no')
        ->first();  // Mengambil satu hasil karena hanya satu surat yang dicari
        // dd($surat);
        
        // Ambil data surat terkait
        $suratList = DB::table('madina')
                    ->select('sura_no', 'sura_name')
                    ->groupBy('sura_no', 'sura_name')
                    ->orderBy('sura_no')
                    ->get();  // Ambil data surat terkait
        // dd($suratList);
        return view('sabqis.edithistory', compact('history', 'suratList','surat'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'tgl_sabqi'     => 'required|date',
            'surat_no'      => 'required|integer|exists:madina,sura_no',
            'dariayat'      => 'required|integer',
            'sampaiayat'    => 'required|integer',
            'nilai'         => 'required|integer',
            'keterangan'    => 'nullable|string',
        ]);

        // Ambil data history berdasarkan ID
        $history = Sabqi_history::findOrFail($id);

        // Ambil data surat berdasarkan surat_no
        $surat = DB::table('madina')->where('sura_no', $request->surat_no)->first();

        // Cek apakah surat ditemukan
        if (!$surat) {
            return redirect()->back()->withErrors(['surat_id' => 'Surat tidak ditemukan.']);
        }

        
        // Ambil data sabaq terkait history
        $sabqi = Sabqi::findOrFail($history->sabqi_id); // Mengambil sabqi berdasarkan sabqi_id yang ada pada history

        // Ambil siswa_id dari sabqi
        $siswa_id = $sabqi->siswa_id;

        // Update data history
        $history->surat_id      = $surat->id;
        $history->tgl_sabqi     = $request->tgl_sabqi;
        $history->dariayat      = $request->dariayat;
        $history->sampaiayat    = $request->sampaiayat;
        $history->nilai         = $request->nilai;
        $history->keterangan    = $request->keterangan;

        // Simpan perubahan
        $history->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('sabqi-history.show', ['siswa_id' => $siswa_id])->with('success', 'Data berhasil diperbarui.');
    }

    public function laporan(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        if (!$start_date && !$end_date) {
            $start_date = Carbon::now()->startOfMonth()->toDateString();
            $end_date = Carbon::now()->toDateString();
        }

        $start_date = Carbon::parse($start_date)->startOfDay();
        $end_date = Carbon::parse($end_date)->endOfDay();


        // Ambil data berdasarkan rentang tanggal yang dipilih
        $sabqis = Sabqi_history::whereBetween('tgl_sabqi',  [$start_date, $end_date])
            ->with(['surat', 'sabqi.siswa']) // Menyertakan data surat dan siswa
            ->get();

             // Jika user ingin mendownload laporan sebagai PDF
        if ($request->has('pdf')) {
            // Pastikan $start_date dan $end_date juga diteruskan ke view
            $pdf = PDF::loadView('sabqis.laporan_pdf', compact('sabqis', 'start_date', 'end_date'))
                      ->setPaper('a4', 'landscape'); // Menetapkan kertas PDF dan orientasi
            return $pdf->download('laporan_sabqi_' . $start_date . '_to_' . $end_date . '.pdf');
        }
        
        return view('sabqis.laporan', compact('sabqis'));
    }

    public function exportToExcel(Request $request)
    {
        // dd($request->all());
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
    
        // Validasi tanggal
        if (!$start_date || !$end_date) {
            return redirect()->route('sabqis.laporan')->with('error', 'Tanggal harus dipilih.');
        }
    
        // Export to Excel
        return Excel::download(new SabqiExport($start_date, $end_date), 'laporan_sabqi_' . $start_date . '_to_' . $end_date . '.xlsx');
    }
}
