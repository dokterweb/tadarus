<?php

namespace App\Http\Controllers;

use App\Models\Bulan;
use App\Models\Siswa;
use App\Models\Posnya;
use App\Models\Bulanan;
use App\Models\Payment;
use App\Models\Periode;
use App\Models\Kelasnya;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return view('payments.index',compact('payments'));
    }

    public function create()
    {
        $periodes = Periode::where('periode_status', '1')->get();
        $posnyas = Posnya::all();
        return  view('payments.create',compact('periodes','posnyas'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'posnya_id' => 'required|exists:posnyas,id', // Pastikan posnya_id ada di tabel posnyas
            'periode_id' => 'required|exists:periodes,id', // Pastikan periode_id ada di tabel periodes
            'payment_type' => 'required|in:bulan,bebas,buku', // Validasi jenis pembayaran
        ]);

        // Menyimpan data pembayaran ke dalam database
        $payment = new Payment();
        $payment->posnya_id = $request->posnya_id;
        $payment->periode_id = $request->periode_id;
        $payment->payment_type = $request->payment_type;
        $payment->save(); // Simpan data pembayaran

        // Redirect dengan pesan sukses
        return redirect()->route('payments')->with('success', 'Pembayaran berhasil ditambahkan!');
    }

    public function edit(Payment $payment)
    {
        // Ambil data periode aktif dan posnya
        $periodes = Periode::where('periode_status', '1')->get();
        $posnyas = Posnya::all();

        // Kembalikan view dengan data payment yang akan diedit
        return view('payments.edit', compact('payment', 'periodes', 'posnyas'));
    }

    public function update(Request $request, Payment $payment)
    {
        // Validasi input
        $request->validate([
            'posnya_id' => 'required|exists:posnyas,id',
            'periode_id' => 'required|exists:periodes,id',
            'payment_type' => 'required|in:bulan,bebas,buku',
        ]);

        // Update data pembayaran
        $payment->posnya_id = $request->posnya_id;
        $payment->periode_id = $request->periode_id;
        $payment->payment_type = $request->payment_type;
        $payment->save(); // Simpan perubahan

        // Redirect ke halaman pembayaran dengan pesan sukses
        return redirect()->route('payments')->with('success', 'Pembayaran berhasil diperbarui!');
    }

    public function view_bulan(Payment $payment)
    {
        $kelasnyas = Kelasnya::all();
        return  view('payments.view_bulan',compact('payment','kelasnyas'));
    }

    public function filterBulanans(Request $request, Payment $payment)
    {
        $kelasnyas = Kelasnya::all();

        // Ambil input kelas_id dari request
        $kelas_id = $request->input('kelas_id');

        // Query dasar bulanans berdasarkan periode (payment_id)
        $query = Bulanan::with(['siswa.user', 'siswa.kelasnya'])
            ->where('payment_id', $payment->id);

        // Jika kelas dipilih, filter berdasarkan kelas_id
        if (!empty($kelas_id)) {
            $query->whereHas('siswa', function ($q) use ($kelas_id) {
                $q->where('kelas_id', $kelas_id);
            });
        }

        $bulanans = $query->get();

        return view('payments.view_bulan', compact('payment', 'kelasnyas', 'bulanans'));
    }

    public function add_payment_bulan(Payment $payment)
    {
        $kelas = Kelasnya::all();
        $bulan = Bulan::all();
        return  view('payments.payment_add_bulan',compact('payment','kelas','bulan'));
    }

    public function storeBulanans(Request $request, $paymentId)
    {
        // Validasi input
        $request->validate([
            'kelas_id'   => 'required|exists:kelasnyas,id',  
            'bulan_id'   => 'required|array',           
            'bulan_bill' => 'required|array',         
        ]);
    
        // Ambil semua siswa berdasarkan kelas_id
        $siswaList = Siswa::where('kelas_id', $request->kelas_id)->get();
    
        // Loop setiap siswa dan bulan untuk insert ke tabel bulanans
        foreach ($siswaList as $siswa) {
            foreach ($request->bulan_id as $index => $bulanId) {
                Bulanan::create([
                    'payment_id' => $paymentId,                
                    'siswa_id'   => $siswa->id,               
                    'bulan_id'   => $bulanId,                 
                    'bulan_bill' => $request->bulan_bill[$index], 
                ]);
            }
        }
    
        return redirect()->route('payments')
                         ->with('success', 'Data pembayaran bulanan berhasil ditambahkan.');
    }
    
}
