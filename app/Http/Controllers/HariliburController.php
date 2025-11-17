<?php

namespace App\Http\Controllers;

use App\Models\Harilibur;
use Illuminate\Http\Request;

class HariliburController extends Controller
{
    public function index()
    {
        $hariliburs = Harilibur::orderBy('tanggal_mulai', 'desc')->get();

        return view('hariliburs.index', compact('hariliburs'));
    }

    public function create()
    {
        return view('hariliburs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nama_libur'      => 'required|string|max:255',
            'tipe'            => 'required|in:nasional,sekolah,mingguan',
            'berlaku_untuk'   => 'required|in:semua,siswa,ustadz',
            'keterangan'      => 'nullable|string|max:255',
        ]);
        
        Harilibur::create($data);

        return redirect()
            ->route('hariliburs')
            ->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $harilibur = Harilibur::findOrFail($id);
        return view('hariliburs.edit', compact('harilibur'));
    }

    public function update(Request $request, $id)
    {
        $harilibur = Harilibur::findOrFail($id);

        $data = $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nama_libur'      => 'required|string|max:255',
            'tipe'            => 'required|in:nasional,sekolah,mingguan',
            'berlaku_untuk'   => 'required|in:semua,siswa,ustadz',
            'keterangan'      => 'nullable|string|max:255',
        ]);

        $harilibur->update($data);

        return redirect()
            ->route('hariliburs')
            ->with('success', 'Hari libur berhasil diperbarui.');
    }

    public function createMonthly()
    {
        // default: bulan ini
        $currentMonth = now()->format('Y-m');

        return view('hariliburs.monthly', compact('currentMonth'));
    }

    public function storeMonthly(Request $request)
    {
        $rows = $request->input('rows', []);

        // optional: validasi super simple
        if (empty($rows)) {
            return back()->with('error','Tidak ada data yang dikirim.');
        }

        $created = 0;

        foreach ($rows as $row) {
            // hanya proses kalau dicentang
            if (empty($row['is_libur'])) {
                continue;
            }

            // safety: pastikan tanggal ada
            if (empty($row['tanggal'])) {
                continue;
            }

            Harilibur::create([
                'tanggal_mulai'   => $row['tanggal'],
                'tanggal_selesai' => $row['tanggal'],
                'nama_libur'      => $row['nama_libur'] ?? 'Libur',
                'tipe'            => $row['tipe'] ?? 'sekolah',
                'berlaku_untuk'   => $row['berlaku_untuk'] ?? 'semua',
                'keterangan'      => $row['keterangan'] ?? null,
            ]);

            $created++;
        }

        return redirect()
            ->route('hariliburs')
            ->with('success', "Berhasil menyimpan $created hari libur.");
    }

    public function destroy($id)
    {
        $harilibur = Harilibur::findOrFail($id);
        $harilibur->delete();

        return redirect()
            ->route('hariliburs')
            ->with('success', 'Hari libur berhasil dihapus.');
    }

    public function cekHariLibur($tanggal)
    {
        $libur = Harilibur::whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->first();

        if ($libur) {
            return response()->json([
                'status' => 'libur',
                'nama_libur' => $libur->nama_libur,
                'tipe' => $libur->tipe,
            ]);
        }

        return response()->json(['status' => 'bukan-libur']);
    }

}
