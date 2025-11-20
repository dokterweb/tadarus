<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Kelompok;
use App\Models\Jenisiqro;
use App\Models\Iqrohistory;
use Illuminate\Http\Request;
use App\Models\Absensi_siswa;
use App\Models\Absensi_ustadz;
use Illuminate\Support\Facades\DB;

class IqroController extends Controller
{
   /*  public function index()
    {
        $siswas = Siswa::whereHas('kelompok', function($q) {
            $q->where('pelajaran', 'btq');
        })->get();
        return view('iqros.index',compact('siswas'));
    } */

    public function index()
    {
        $user = auth()->user();
    
        // base query
        $siswasQuery = Siswa::with('kelompok')
        ->whereHas('kelompok', function ($q) {
            $q->where('pelajaran', 'btq');
        });
        $kelompoksQuery = Kelompok::query()->orderBy('nama_kelompok');
    
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
    
        return view('iqros.index', compact('siswas', 'kelompoks'));
    }

    public function create()
    {
        // $kelompoks = Kelompok::select('id','nama_kelompok')->orderBy('nama_kelompok')->get();
        $kelompoks = Kelompok::select('id','nama_kelompok')
        ->where('pelajaran', 'btq')   // ← filter kelompok
        ->orderBy('nama_kelompok')
        ->get();

        $jenisiqros = Jenisiqro::all();

        return view('iqros.create', compact('kelompoks','jenisiqros'));
    }

    public function getSiswaByKelompok($kelompokId)
    {
        $siswas = Siswa::where('kelompok_id', $kelompokId)->get();
        
        return response()->json($siswas);
    }

    public function store(Request $request)
    {
        // Validasi umum
        $base = $request->validate([
            'tgl'               => 'required|date',
            'kelompok_id'       => 'required|integer|exists:kelompoks,id',
            'siswa_id'          => 'required|integer|exists:siswas,id',
            'status'            => 'required|in:hadir,ghoib,izin,tugas,sakit,pulang',
            'keterangan'        => 'nullable|string',
        ]);
    
        // Jika BUKAN hadir → cukup simpan absensi
        if ($base['status'] !== 'hadir') {
            Absensi_siswa::create([
                'tgl_absen' => $base['tgl'],
                'status'    => $base['status'],
                'siswa_id'  => $base['siswa_id'],
                'keterangan'=> $base['keterangan'] ?? null, // opsional
            ]);
    
            return back()->with('success','Absensi tersimpan (bukan hadir).');
        }
    
        // Jika HADIR → validasi tambahan untuk tadarus
        $more = $request->validate([
            'jenisiqro_id'    => 'required|integer',
            'nilaibacaan'  => 'required|string|max:255',
            'hal_awal'      => 'required|integer|min:1',
            'hal_akhir'     => 'required|integer|min:1|gte:hal_awal',
        ]);
    
        try {
            DB::transaction(function () use ($base, $more, $request) {
    
                // Simpan tadarus_history
                $iqrohistory = Iqrohistory::create([
                    'siswa_id'       => $base['siswa_id'],
                    'ustadz_id'      => auth()->id(), // menggunakan Auth
                    'jenisiqro_id'  => $more['jenisiqro_id'],
                    'nilaibacaan'   => $more['nilaibacaan'],
                    'hal_awal'       => $more['hal_awal'],
                    'hal_akhir'      => $more['hal_akhir'],
                    'tgl_iqro'      => $base['tgl'],
                ]);
                
                // Simpan absensi hadir untuk siswa
                $absensi_siswa = Absensi_siswa::updateOrCreate(
                    ['tgl_absen' => $base['tgl'], 'siswa_id' => $base['siswa_id']],
                    ['status' => 'hadir', 'keterangan' => $base['keterangan'] ?? null]
                );
                
                // Simpan absensi hadir untuk ustadz
                $absensi_ustadz = Absensi_ustadz::updateOrCreate(
                    ['tgl_absen' => $base['tgl'], 'ustadz_id' => auth()->id()],
                    ['status' => 'hadir', 'keterangan' => 'Iqro input oleh ustadz']
                );
                
            });
    
            // return back()->with('success','Tadarus & absensi hadir tersimpan.');
            return redirect()
            ->route('iqros.show', $base['siswa_id'])
            ->with('success','Iqra & absensi hadir tersimpan.');
        } catch (\Exception $e) {
            \Log::error('Error during transaction: ' . $e->getMessage());
            return back()->with('error','Gagal menyimpan: ' . $e->getMessage());
        }
    }
    
    // IqroController.php
    public function getHistories($siswaId)
    {
        $histories = Iqrohistory::with('jenisiqro')
            ->where('siswa_id', $siswaId)
            ->orderBy('tgl_iqro', 'desc')
            ->get()
            ->map(function ($row) {
                return [
                    'id'           => $row->id,
                    'tgl_iqro'     => $row->tgl_iqro,
                    'nama_iqro'    => optional($row->jenisiqro)->nama_iqro,
                    'hal_awal'     => $row->hal_awal,
                    'hal_akhir'    => $row->hal_akhir,
                    'nilaibacaan' => $row->nilaibacaan,
                ];
            });

        return response()->json($histories);
    }

       public function edit($id)
    {
        // Ambil data iqrohistory berdasarkan ID
        $iqro = Iqrohistory::findOrFail($id);

        // Ambil data kelompok dan jenis iqro untuk dropdown
        $kelompoks = Kelompok::select('id', 'nama_kelompok')->orderBy('nama_kelompok')->get();
        $jenisiqros = Jenisiqro::all();
        $absensi = Absensi_siswa::where('siswa_id', $iqro->siswa_id)
        ->whereDate('tgl_absen', $iqro->tgl_iqro)
        ->first();

        // Kirim data ke view
        return view('iqros.edit', compact('iqro', 'kelompoks', 'jenisiqros','absensi'));
    }

    public function update(Request $request, $id)
    {
        // Validasi umum
        $base = $request->validate([
            'tgl'               => 'required|date',
            'kelompok_id'       => 'required|integer|exists:kelompoks,id',
            'siswa_id'          => 'required|integer|exists:siswas,id',
            'status'            => 'required|in:hadir,ghoib,izin,tugas,sakit,pulang',
            'keterangan'        => 'nullable|string',
        ]);

        // Validasi tambahan untuk iqro jika hadir
        $more = $request->validate([
            'jenisiqro_id'  => 'required|integer',
            'nilaibacaan'  => 'required|string|max:255',
            'hal_awal'      => 'required|integer|min:1',
            'hal_akhir'     => 'required|integer|min:1|gte:hal_awal',
        ]);

        try {
            DB::transaction(function () use ($base, $more, $request, $id) {

                // Cek apakah status absen adalah "hadir"
                if ($base['status'] === 'hadir') {

                    // Jika hadir, lakukan update pada iqrohistory
                    $iqro = Iqrohistory::findOrFail($id);

                    $iqro->update([
                        'siswa_id'      => $base['siswa_id'],
                        'ustadz_id'     => auth()->id(),
                        'jenisiqro_id'  => $more['jenisiqro_id'],
                        'nilaibacaan'  => $more['nilaibacaan'],
                        'hal_awal'      => $more['hal_awal'],
                        'hal_akhir'     => $more['hal_akhir'],
                        'tgl_iqro'      => $base['tgl'],
                    ]);
                } else {
                    // Jika status absen selain "hadir", hapus iqrohistory yang ada
                    $iqro = Iqrohistory::findOrFail($id);
                    $iqro->delete();
                }

                // Simpan absensi siswa
                Absensi_siswa::updateOrCreate(
                    ['tgl_absen' => $base['tgl'], 'siswa_id' => $base['siswa_id']],
                    ['status' => $base['status'], 'keterangan' => $base['keterangan'] ?? null]
                );

                // Simpan absensi ustadz
                Absensi_ustadz::updateOrCreate(
                    ['tgl_absen' => $base['tgl'], 'ustadz_id' => auth()->id()],
                    ['status' => $base['status'], 'keterangan' => 'Iqro input oleh ustadz']
                );
            });

            return back()->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Ambil detail siswa berdasarkan ID
        $siswa = Siswa::with('kelompok', 'kelasnya') // Relasi dengan kelompok dan kelas
            ->findOrFail($id);  // Mengambil siswa berdasarkan ID yang diberikan

        // Ambil histori tadarus berdasarkan siswa_id, urutkan berdasarkan tanggal
        $iqroHistories = Iqrohistory::where('siswa_id', $id)
            ->orderBy('tgl_iqro', 'desc') // Urutkan berdasarkan tanggal tadarus
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
        return view('iqros.show', compact('siswa', 'iqroHistories','absensiCounts'));
    }

}
