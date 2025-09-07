<?php

namespace App\Http\Controllers;
use App\Models\Siswa;
use App\Models\Ustadz;
use App\Models\Kelasnya;
use Illuminate\Http\Request;
use App\Models\Absensi_siswa;

class AbsensiSiswaController extends Controller
{
     // Fungsi untuk melihat absensi semua siswa (admin)
     public function index()
     {
        if (auth()->user()->hasRole('admin')) {
            // Admin dapat melihat semua absensi
            $absensi = Absensi_siswa::all(); 
        } else {
            $absensi = []; // Jika tidak ada role yang cocok
        }
 
         return view('absensi.index', compact('absensi'));
     }
 
     public function create()
    {
        // Ambil semua kelas yang tersedia
        $kelas = Kelasnya::all();

        return view('absensi.create', compact('kelas'));
    }

    public function getSiswaByKelas($kelas_id)
    {
        // Ambil semua siswa berdasarkan kelas_id dan termasuk user (untuk mendapatkan nama)
        $siswa = Siswa::with('user')  // Mengambil relasi user untuk nama siswa
                      ->where('kelas_id', $kelas_id)
                      ->get();
    
        // Kirimkan data siswa dalam bentuk JSON
        return response()->json(['siswa' => $siswa]);
    }
    

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kelas_id' => 'required|exists:kelasnyas,id',  // Pastikan kelas_id ada
            'tgl_absen' => 'required|date',
            'status' => 'required|array',
        ]);

        // Cek apakah absensi sudah ada di tanggal yang sama untuk kelas yang dipilih
        $existingAbsensi = Absensi_siswa::whereHas('siswa', function ($query) use ($request) {
            $query->where('kelas_id', $request->kelas_id);  // Cek kelas siswa
        })
        ->where('tgl_absen', $request->tgl_absen)
        ->exists();

        // Jika absensi sudah ada untuk tanggal tersebut, tampilkan notifikasi error
        if ($existingAbsensi) {
            return response()->json(['error' => 'Absensi sudah ada di tanggal tersebut. Semua siswa sudah absen.'], 400);
        }

        // Simpan absensi untuk setiap siswa yang dipilih
        foreach ($request->status as $siswa_id => $status) {
            Absensi_siswa::create([
                'siswa_id' => $siswa_id,
                'tgl_absen' => $request->tgl_absen,
                'status' => $status,  // Status absensi per siswa
            ]);
        }

        return response()->json(['success' => 'Absensi berhasil disimpan.'], 200);
    }
    

    public function checkAbsensi(Request $request)
    {
        // Cek apakah absensi sudah ada di tanggal yang sama untuk kelas yang dipilih
        $existingAbsensi = Absensi_siswa::whereHas('siswa', function ($query) use ($request) {
            $query->where('kelas_id', $request->kelas_id);  // Cek kelas siswa
        })
        ->where('tgl_absen', $request->tgl_absen)
        ->exists();

        // Kembalikan status dalam bentuk JSON
        return response()->json(['exists' => $existingAbsensi]);
    }

    public function destroy($id)
    {
        // Cari data absensi berdasarkan ID
        $absensi = Absensi_siswa::findOrFail($id);
    
        // Hapus absensi
        $absensi->delete();
    
        // Kembali ke halaman absensi dengan pesan sukses
        return redirect()->route('absensis')->with('success', 'Absensi berhasil dihapus');
    }
    
    public function ustadzIndex()
    {
        // Ambil data ustadz yang sedang login
        $ustadz = Ustadz::where('user_id', auth()->id())->first();

        if (!$ustadz) {
            return redirect()->back()->with('error', 'Ustadz tidak ditemukan.');
        }

        // Ambil absensi semua siswa yang diampu oleh ustadz ini
        $absensi = Absensi_siswa::whereHas('siswa', function ($query) use ($ustadz) {
            $query->where('ustadz_id', $ustadz->id);
        })->with(['siswa.user'])->orderBy('tgl_absen', 'desc')->get();

        return view('absensi.ustadz_index', compact('absensi'));
    }

    public function ustadzCreate()
    {
        // Ambil data ustadz yang sedang login
        $ustadz = Ustadz::where('user_id', auth()->id())->first();

        if (!$ustadz) {
            return redirect()->back()->with('error', 'Ustadz tidak ditemukan.');
        }

        // Ambil siswa yang diampu oleh ustadz
        $siswas = Siswa::where('ustadz_id', $ustadz->id)->get();

        return view('absensi.ustadzcreate', compact('siswas'));
    }

    public function ustadzStore(Request $request)
    {
        // Validasi input
        $request->validate([
            'tgl_absen' => 'required|date',
            'status' => 'required|array',  // Pastikan setiap siswa memiliki status
        ]);
    
        // Periksa apakah absensi sudah ada untuk siswa pada tanggal yang sama
        foreach ($request->status as $siswa_id => $status) {
            $existingAbsensi = Absensi_siswa::where('siswa_id', $siswa_id)
                                            ->where('tgl_absen', $request->tgl_absen)
                                            ->exists();
    
            if ($existingAbsensi) {
                return back()->with('error', 'Absensi untuk siswa dengan tanggal yang sama sudah ada.');
            }
        }
    
        // Simpan absensi untuk setiap siswa yang dipilih
        foreach ($request->status as $siswa_id => $status) {
            Absensi_siswa::create([
                'siswa_id' => $siswa_id,
                'tgl_absen' => $request->tgl_absen,
                'status' => $status,  // Status absensi per siswa
            ]);
        }
    
        // Redirect ke halaman absensi dengan pesan sukses
        return redirect()->route('absensis.ustadzIndex')->with('success', 'Absensi berhasil disimpan.');
    }
    

}
