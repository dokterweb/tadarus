<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Ustadz;
use App\Models\Kelasnya;
use App\Models\Iqrohistory;
use Illuminate\Http\Request;
use App\Models\TadarusHistory;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalUstadz = Ustadz::count();
        $totalKelas = Kelasnya::count();

         // --- 5 Tadarus terbaru ---
         $latestTadarus = TadarusHistory::with(['siswa:id,nama_siswa', 'ustadz'])
         ->orderBy('tgl_tadarusnya', 'desc')
         ->limit(5)
         ->get();

        // --- 5 Iqro terbaru ---
        $latestIqro = Iqrohistory::with(['siswa:id,nama_siswa', 'jenisiqro'])
            ->orderBy('tgl_iqro', 'desc')
            ->limit(5)
            ->get();
            
        return view('dashboard.index',compact('totalSiswa','totalUstadz','totalKelas','latestTadarus', 'latestIqro'));
    }

}
