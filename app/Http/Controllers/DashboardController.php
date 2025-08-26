<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Ustadz;
use App\Models\Kelasnya;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalUstadz = Ustadz::count();
        $totalKelas = Kelasnya::count();
        return view('dashboard.index',compact('totalSiswa','totalUstadz','totalKelas'));
    }

}
