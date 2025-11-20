<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use App\Models\Absensi_siswa;
use App\Models\Absensi_ustadz;
use App\Models\TadarusHistory;
use Illuminate\Support\Facades\DB;

class TadarusController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        // base query
        $siswasQuery = Siswa::with('kelompok')
        ->whereHas('kelompok', function ($q) {
            $q->where('pelajaran', 'tilawah');
        });
        $kelompoksQuery = Kelompok::query()->where('pelajaran', 'tilawah')->orderBy('nama_kelompok');
    
        // ================= 1. ADMIN =================
        if ($user->hasRole('admin')) {
    
            // tidak ada filter ekstra:
            // - semua siswa
            // - semua kelompok (tilawah & btq)
    
        // ================= 2. ADMIN PUTRA =================
        } elseif ($user->hasRole('adminputra')) {
    
            // hanya siswa laki-laki
            $siswasQuery->where('kelamin', 'laki-laki');
    
        // ================= 3. ADMIN PUTRI =================
        } elseif ($user->hasRole('adminputri')) {
    
            // hanya siswa perempuan
            $siswasQuery->where('kelamin', 'perempuan');
    
        // ================= 4. USTADZ =================
        } elseif ($user->hasRole('ustadz')) {
    
            $ustadz = $user->ustadz;   // relasi di model User: hasOne(Ustadz::class)
            if (!$ustadz) {
                abort(403, 'Data ustadz tidak ditemukan.');
            }
    
            $kelompok = $ustadz->kelompok; // relasi di model Ustadz: belongsTo(Kelompok::class)
            if (!$kelompok) {
                abort(403, 'Kelompok ustadz tidak ditemukan.');
            }
    
            // cek pelajaran: harus sama dengan kolom "mengajari" di ustadz
            // kalau tidak sama, ya secara logika dia tidak mengajar tadarus di sini
            if ($kelompok->pelajaran !== $ustadz->mengajari) {
                abort(403, 'Kelompok ini tidak sesuai dengan pelajaran yang diajar ustadz.');
            }
    
            // gender siswa ditentukan oleh jenis kelompok
            $gender = $kelompok->jenis === 'putra'
                ? 'laki-laki'
                : 'perempuan';
    
            // untuk ustadz, kelompok yang ditampilkan cukup kelompok miliknya saja
            $kelompoksQuery->where('id', $kelompok->id);
    
            // siswa:
            $siswasQuery
                // ->where('kelompok_id', $kelompok->id)
                ->where('kelamin', $gender)
                ->whereHas('kelompok', function ($q) use ($ustadz) {
                    $q->where('pelajaran', $ustadz->mengajari);
                });
    
        } else {
            // role tidak dikenali
            abort(403, 'Anda tidak memiliki akses.');
        }
    
        $siswas    = $siswasQuery->orderBy('nama_siswa')->get();
        $kelompoks = $kelompoksQuery->get();
    
        return view('tadarus.index', compact('siswas', 'kelompoks'));
    }
    

    public function create()
    {
        $user = auth()->user();

    // base query kelompok tilawah
    $kelompoksQuery = Kelompok::select('id','nama_kelompok','jenis')
        ->where('pelajaran', 'tilawah');  // hanya kelompok tilawah

    // ========== ROLE ADMIN ==========
    if ($user->hasRole('admin')) {
        // admin melihat seluruh kelompok → tidak ada filter jenis
        // jadi tidak perlu tambah where

    // ========== ROLE USTADZ ==========
    } elseif ($user->hasRole('ustadz')) {

        $ustadz = $user->ustadz;

        if ($ustadz) {

            // ustadz laki-laki → kelompok putra
            // ustadz perempuan → kelompok putri
            $jenis = $ustadz->kelamin === 'laki-laki' ? 'putra' : 'putri';

            $kelompoksQuery->where('jenis', $jenis);
        }

    // ========== ROLE LAIN (adminputra, adminputri) ==========
    } else {
        // role selain ustadz atau admin → tampilkan semua kelompok tilawah
        // tanpa filter jenis
    }

    $kelompoks = $kelompoksQuery
        ->orderBy('nama_kelompok')
        ->get();

        // dropdown surat (nama & nomor) — cukup yang dibutuhkan
        $surat = DB::table('madina')
            ->select('sura_no','sura_name', DB::raw('MIN(id) as id'))
            ->groupBy('sura_no','sura_name')
            ->orderBy('sura_no')
            ->get();

        return view('tadarus.create', compact('kelompoks','surat'));
    }

    // AJAX: ambil siswa pada kelompok tertentu
    public function getSiswasByKelompok($kelompokId)
    {
        $siswas = Siswa::where('kelompok_id', $kelompokId)
            ->select('id','nama_siswa')
            ->orderBy('nama_siswa')
            ->get();

        return response()->json($siswas);
    }

    // AJAX (opsional): detail surat untuk isi No/Juz/Halaman otomatis
    public function getSuratDetails($sura_no)
    {
        $head = DB::table('madina')->where('sura_no',$sura_no)->orderBy('page')->first();
        $pages = DB::table('madina')
            ->where('sura_no',$sura_no)
            ->selectRaw('MIN(page) as start_page, MAX(page) as end_page')
            ->first();

        if(!$head || !$pages){
            return response()->json(['status'=>'error','message'=>'Surat tidak ditemukan'],404);
        }

        return response()->json([
            'status'=>'success',
            'data'=>[
                'no_surat'   => $head->sura_no,
                'sura_name'  => $head->sura_name,
                'jozz'       => $head->jozz ?? ($head->juz ?? null),
                'start_page' => $pages->start_page,
                'end_page'   => $pages->end_page,
            ]
        ]);
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

    public function store(Request $request)
    {
        // validasi umum
        $base = $request->validate([
            'tgl'               => 'required|date',
            'kelompok_id'       => 'required|integer|exists:kelompoks,id',
            'siswa_id'          => 'required|integer|exists:siswas,id',
            'status'            => 'required|in:hadir,ghoib,izin,tugas,sakit,pulang',
            'keteranganabsen'   => 'nullable|string',
        ]);

        // jika BUKAN hadir → cukup simpan absensi
        if ($base['status'] !== 'hadir') {
            Absensi_siswa::create([
                'tgl_absen' => $base['tgl'],
                'status'    => $base['status'],
                'siswa_id'  => $base['siswa_id'],
                'keterangan'=> $base['keteranganabsen'] ?? null,// opsional
            ]);

            return back()->with('success','Absensi tersimpan (bukan hadir).');
        }

        // jika HADIR → validasi tambahan untuk tadarus
        $more = $request->validate([
            'surat_no'   => 'required|integer|exists:madina,sura_no',
            'dariayat'   => 'required|integer|min:1',
            'sampaiayat' => 'required|integer|min:1|gte:dariayat',
            'keterangantadarus' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($base, $more, $request) {

                // ambil surat
                $surat = DB::table('madina')
                    ->where('sura_no',$more['surat_no'])
                    ->orderBy('id')->first();

                // simpan tadarus_history
                TadarusHistory::create([
                    'siswa_id'       => $base['siswa_id'],
                    'ustadz_id'      => auth()->id(), // kalau pakai Auth
                    'surat_id'       => $surat->id,
                    'surat_no'       => $more['surat_no'],
                    'dariayat'       => $more['dariayat'],
                    'sampaiayat'     => $more['sampaiayat'],
                    'tgl_tadarusnya' => $base['tgl'],
                    'keterangan'     => $more['keterangantadarus'] ?? null,
                ]);

                // opsional: sekaligus catat absensi hadir di absensi_siswas
                Absensi_siswa::updateOrCreate(
                    ['tgl_absen'=>$base['tgl'], 'siswa_id'=>$base['siswa_id']],
                    ['status'=>'hadir', 'keterangan'=>$base['keteranganabsen'] ?? null]
                );

                 // Catat absensi hadir untuk ustadz
                Absensi_ustadz::updateOrCreate(
                    ['tgl_absen' => $base['tgl'], 'ustadz_id' => auth()->id()],
                    ['status' => 'hadir', 'keterangan' => 'Tadarus input oleh ustadz']
                );
            });

            // return back()->with('success','Tadarus & absensi hadir tersimpan.');
            return redirect()
            ->route('tadarus.show', $base['siswa_id'])
            ->with('success','Tadarus & absensi hadir tersimpan.');
        } catch (\Exception $e) {
            return back()->with('error','Gagal menyimpan: '.$e->getMessage());
        }
    }

    public function getTadarusBySiswa($siswa_id)
    {
        $histories = DB::table('tadarus_histories')
            ->join('madina', 'tadarus_histories.surat_id', '=', 'madina.id')
            ->where('tadarus_histories.siswa_id', $siswa_id)
            ->select(
                'tadarus_histories.id',
                'tadarus_histories.tgl_tadarusnya',
                'madina.sura_name',
                'tadarus_histories.dariayat',
                'tadarus_histories.sampaiayat',
                'tadarus_histories.keterangan'
            )
            ->orderByDesc('tadarus_histories.tgl_tadarusnya')
            ->limit(10) // tampilkan 10 terakhir
            ->get();

        return response()->json($histories);
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
  
    public function edit($id)
    {
        // Ambil data tadarus berdasarkan ID
        $tadarus = TadarusHistory::findOrFail($id);
        // dd($tadarus);
        $kelompoks = Kelompok::all();  // Ambil semua kelompok untuk dropdown
        
        $surat = DB::table('madina')
        ->select('sura_no','sura_name', DB::raw('MIN(id) as id'))
        ->groupBy('sura_no','sura_name')
        ->orderBy('sura_no')
        ->get();
    
          // ambil absensi yang cocok dengan siswa & tanggal tadarus
        $absensi = Absensi_siswa::where('siswa_id', $tadarus->siswa_id)
        ->whereDate('tgl_absen', $tadarus->tgl_tadarusnya)
        ->first();
        
        // Kirim data ke view edit
        return view('tadarus.edit', compact('tadarus', 'kelompoks', 'surat','absensi'));
    }
    

    public function update(Request $request, $id)
    {
        // Ambil record tadarus dulu
        $tadarus = TadarusHistory::findOrFail($id);

        // 1. Validasi dasar (absensi)
        $base = $request->validate([
            'tgl'             => 'required|date',
            'status'          => 'required|in:hadir,ghoib,izin,tugas,sakit,pulang',
            'keteranganabsen' => 'nullable|string',
        ]);

        // ======================
        // CASE 1: BUKAN HADIR
        // ======================
        if ($base['status'] !== 'hadir') {

            DB::transaction(function () use ($tadarus, $base) {

                // Hapus tadarus_history
                $tadarus->delete();

                // Update / buat absensi saja
                Absensi_siswa::updateOrCreate(
                    [
                        'tgl_absen' => $base['tgl'],
                        'siswa_id'  => $tadarus->siswa_id,
                    ],
                    [
                        'status'     => $base['status'],
                        'keterangan' => $base['keteranganabsen'] ?? null,
                    ]
                );
            });

            return redirect()
                ->route('tadaruses.create') // atau route yang kamu mau
                ->with('success', 'Absensi diperbarui. Data tadarus dihapus karena status bukan HADIR.');
        }

        // ======================
        // CASE 2: HADIR
        // ======================

        // Validasi tambahan untuk tadarus
        $more = $request->validate([
            'surat_no'          => 'required|integer|exists:madina,sura_no',
            'dariayat'          => 'required|integer|min:1',
            'sampaiayat'        => 'required|integer|min:1|gte:dariayat',
            'keterangantadarus' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($tadarus, $base, $more) {

                // Ambil data surat
                $surat = DB::table('madina')
                    ->where('sura_no', $more['surat_no'])
                    ->orderBy('id')
                    ->first();

                if (!$surat) {
                    throw new \Exception('Surat tidak ditemukan.');
                }

                // 1) Update tadarus_histories
                $tadarus->update([
                    'siswa_id'       => $tadarus->siswa_id,
                    'ustadz_id'      => auth()->id(),
                    'surat_id'       => $surat->id,
                    'surat_no'       => $more['surat_no'],
                    'dariayat'       => $more['dariayat'],
                    'sampaiayat'     => $more['sampaiayat'],
                    'tgl_tadarusnya' => $base['tgl'],
                    'keterangan'     => $more['keterangantadarus'] ?? null,
                ]);

                // 2) Update / buat absensi dengan status HADIR
                Absensi_siswa::updateOrCreate(
                    [
                        'tgl_absen' => $base['tgl'],
                        'siswa_id'  => $tadarus->siswa_id,
                    ],
                    [
                        'status'     => 'hadir',
                        'keterangan' => $base['keteranganabsen'] ?? null,
                    ]
                );
            });

            return redirect()
                ->route('tadaruses.create') // atau kembali ke halaman lain
                ->with('success', 'Data tadarus dan absensi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Ambil detail siswa berdasarkan ID
        $siswa = Siswa::with('kelompok', 'kelasnya') // Relasi dengan kelompok dan kelas
            ->findOrFail($id);  // Mengambil siswa berdasarkan ID yang diberikan

        // Ambil histori tadarus berdasarkan siswa_id, urutkan berdasarkan tanggal
        $tadarusHistories = TadarusHistory::where('siswa_id', $id)
            ->orderBy('tgl_tadarusnya', 'desc') // Urutkan berdasarkan tanggal tadarus
            ->paginate(10);

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
    
        // Hitung jumlah absensi berdasarkan status untuk bulan ini
        $absensiCounts = Absensi_siswa::where('siswa_id', $id)
            ->whereMonth('tgl_absen', $currentMonth) // Filter berdasarkan bulan
            ->whereYear('tgl_absen', $currentYear)  // Filter berdasarkan tahun
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // Kembalikan data ke view 'tadarus.show'
        return view('tadarus.show', compact('siswa', 'tadarusHistories','absensiCounts'));
    }
    
    public function destroy($id)
    {
        try {
            $tadarus = TadarusHistory::findOrFail($id);
            $tadarus->delete();

            // Jika absensi terkait dengan tadarus dihapus, hapus juga absensinya
            AbsensiSiswa::where('siswa_id', $tadarus->siswa_id)
                ->where('tgl_absen', $tadarus->tgl_tadarusnya)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


}
