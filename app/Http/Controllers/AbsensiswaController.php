<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Absensi_siswa;

class AbsensiswaController extends Controller
{
    public function index(Request $request)
    {
        // Menentukan tanggal mulai dan tanggal selesai
        $tanggalMulai = $request->has('tanggal_mulai') ? Carbon::parse($request->tanggal_mulai) : Carbon::now()->startOfMonth();
        $tanggalSelesai = $request->has('tanggal_selesai') ? Carbon::parse($request->tanggal_selesai) : Carbon::now();
    
        // Filter absensi berdasarkan tanggal
        $absensiSiswas = Absensi_siswa::with('siswa')
            ->whereBetween('tgl_absen', [$tanggalMulai, $tanggalSelesai])->get();
    
        return view('absensi_siswas.index', compact('absensiSiswas', 'tanggalMulai', 'tanggalSelesai'));
    }

}
