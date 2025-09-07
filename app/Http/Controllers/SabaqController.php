<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sabaq;
use App\Models\Siswa;
use App\Models\Madina;
use Illuminate\Http\Request;
use App\Models\Sabaq_history;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Exports\SabaqExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

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

    /* public function getSuratDetails($sura_no)
    {
        // Menjalankan query dengan Query Builder untuk mengambil data
        $surat = DB::table('madina')
            ->select('id AS suratId', 'sura_no AS no_surat', 'jozz', 'sura_name', 
                    DB::raw('MIN(page) AS start_page'), DB::raw('MAX(page) AS end_page'))
            ->where('sura_no', $sura_no)
            ->groupBy('sura_no', 'sura_name', 'jozz', 'id')
            ->first();

        // Cek apakah data ditemukan
        if ($surat) {
            return response()->json(['status' => 'success', 'data' => $surat]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Surat tidak ditemukan'], 404);
        }
    } */

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

    public function getHistory($id)
    {
        // Ambil data history berdasarkan ID
        $history = Sabaq_history::find($id);
    
        if (!$history) {
            return response()->json(['status' => 'error', 'message' => 'History tidak ditemukan'], 404);
        }
    
        // Ambil data surat berdasarkan surat_id yang ditemukan
        $surat = DB::table('madina')
                    ->where('id', $history->surat_id)  // Gunakan surat_id dari history untuk mencari surat
                    ->first();
    
        // Ambil semua surat dari madina, urutkan berdasarkan sura_no
        $suratList = DB::table('madina')
                       ->select('sura_no', 'sura_name', DB::raw('MIN(id) as id'), DB::raw('COUNT(sura_no) as qty_sura'))
                       ->groupBy('sura_no', 'sura_name')
                       ->orderBy('sura_no')  // Urutkan berdasarkan sura_no
                       ->get();  // Ambil semua surat dari tabel madina
    
        // Cek jika data ada
        if ($history && $surat) {
            // Mengembalikan data dalam format JSON
            return response()->json([
                'status' => 'success',
                'data' => $history,
                'surat' => $surat,  // Data surat yang terkait dengan history
                'suratList' => $suratList  // Mengirimkan surat list yang terurut
            ]);
        }
    
        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak ditemukan'
        ]);
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

    

    public function update(Request $request, $id)
    {
          // Log input untuk debugging
    Log::info('Data yang diterima:', $request->all());  // Ini akan menulis seluruh input yang diterima di log

    // Cek apakah nilai surat_no ada
    Log::info('Surat No diterima: ' . $request->surat_no);  // Menulis log ke file log Laravel
    Log::info('ID diterima: ' . $id);  // Menulis log ke file log Laravel

        // Validasi data input
        $request->validate([
            'tgl_sabaq'     => 'required|date',
            'surat_no'      => 'required|integer|exists:madina,sura_no', // Surat harus ada di tabel madina
            'dariayat'      => 'required|integer',
            'sampaiayat'    => 'required|integer',
            'nilai'         => 'required|integer',
            'keterangan'    => 'nullable|string',
        ]);
    
        // Ambil data history berdasarkan ID
        $history = Sabaq_history::findOrFail($id);
    
        // Ambil data surat berdasarkan surat_no (sura_no)
        $surat = DB::table('madina')->where('sura_no', $request->surat_no)->first();
    
        // Cek apakah surat ditemukan
        if (!$surat) {
            Log::error('Surat tidak ditemukan untuk surat_no: ' . $request->surat_no); // Log error jika surat tidak ditemukan
            return redirect()->back()->withErrors(['surat_id' => 'Surat tidak ditemukan.']);
        }
    
        // Ambil data sabaq terkait history
        $sabaq = Sabaq::findOrFail($history->sabaq_id); // Mengambil sabaq berdasarkan sabaq_id yang ada pada history

        // Ambil siswa_id dari sabaq
        $siswa_id = $sabaq->siswa_id;
        
        $history->surat_id = $surat->id;
        // $history->sura_no = $surat->sura_no;
        $history->dariayat = $request->dariayat;
        $history->sampaiayat = $request->sampaiayat;
        $history->nilai = $request->nilai;
        $history->keterangan = $request->keterangan;
        $history->tgl_sabaq = $request->tgl_sabaq;
    
        // Simpan perubahan
        $history->save();
    
    
        return redirect()->route('sabaq-history.show', ['siswa_id' => $siswa_id])->with('success', 'Data berhasil diperbarui.');
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
        $sabaqs = Sabaq_history::whereBetween('tgl_sabaq',  [$start_date, $end_date])
            ->with(['surat', 'sabaq.siswa']) // Menyertakan data surat dan siswa
            ->get();

        // Jika user ingin mendownload laporan sebagai PDF
        if ($request->has('pdf')) {
            // Pastikan $start_date dan $end_date juga diteruskan ke view
            $pdf = PDF::loadView('sabaqs.laporan_pdf', compact('sabaqs', 'start_date', 'end_date'))
                      ->setPaper('a4', 'landscape'); // Menetapkan kertas PDF dan orientasi
            return $pdf->download('laporan_sabaq_' . $start_date . '_to_' . $end_date . '.pdf');
        }
        
        return view('sabaqs.laporan', compact('sabaqs'));
    }

    public function exportToExcel(Request $request)
    {
        // dd($request->all());
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
    
        // Validasi tanggal
        if (!$start_date || !$end_date) {
            return redirect()->route('sabaqs.laporan')->with('error', 'Tanggal harus dipilih.');
        }
    
        // Export to Excel
        return Excel::download(new SabaqExport($start_date, $end_date), 'laporan_sabaq_' . $start_date . '_to_' . $end_date . '.xlsx');
    }
    
}
