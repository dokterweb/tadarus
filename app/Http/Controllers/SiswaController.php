<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sabaq;
use App\Models\Sabqi;
use App\Models\Siswa;
use App\Models\Manzil;
use App\Models\Ustadz;
use App\Models\Kelasnya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswas = Siswa::all();
        return view('siswas.index',compact('siswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelasnya::all();
        $ustadz = Ustadz::all();
        return view('siswas.create',compact('kelas','ustadz'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiswaRequest $request)
    {
        $validated = $request->validated();

        // Simpan avatar jika ada
        if($request->hasFile('avatar')){
            $avatarPath = $request->file('avatar')->store('siswa','public');
        }

        // Buat user baru
        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'avatar'    => $avatarPath ?? null, // Jika tidak ada avatar, nilainya null
            'password'  => Hash::make($validated['password']),
        ]);

        // Assign role 'ustadz' ke user
        $user->assignRole('siswa'); // Pastikan role 'ustadz' sudah ada di database

        // Simpan data ke tabel ustadzs
        $siswa = Siswa::create([
            'user_id'       => $user->id,
            'kelas_id'      => $validated['kelas_id'],
            'ustadz_id'     => $validated['ustadz_id'],
            'kelamin'       => $validated['kelamin'],
            'tempat_lahir'  => $validated['tempat_lahir'],
            'tgl_lahir'     => $validated['tgl_lahir'],
            'alamat'        => $validated['alamat'],
            'nama_ayah'     => $validated['nama_ayah'],
            'nama_ibu'      => $validated['nama_ibu'],
            'no_hp'         => $validated['no_hp'],
        ]);
        
         // Insert ke tabel sabaqs
        Sabaq::create([
            'siswa_id'   => $siswa->id, // Ambil ID siswa yang baru dibuat
            'ustadz_id'  => $validated['ustadz_id'], // Ustadz yang dipilih
        ]);

         // Insert ke tabel Sabqi
        Sabqi::create([
            'siswa_id'   => $siswa->id, // Ambil ID siswa yang baru dibuat
            'ustadz_id'  => $validated['ustadz_id'], // Ustadz yang dipilih
        ]);

         // Insert ke tabel Manzil
        Manzil::create([
            'siswa_id'   => $siswa->id, // Ambil ID siswa yang baru dibuat
            'ustadz_id'  => $validated['ustadz_id'], // Ustadz yang dipilih
        ]);
        
        return redirect()->route('siswas.index')->with('success', 'Data Siswa berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelasnya::all();
        $ustadz = Ustadz::all();
        return view('siswas.edit',compact('kelas','ustadz','siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        // Data sudah divalidasi di UpdateustadzRequest
        $validated = $request->validated();

        // Update data User terkait
        $userData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        // Cek apakah password diisi, jika iya, update password
        if ($request->filled('password')) {
            $userData['password'] = bcrypt($validated['password']);
        }

        // Cek apakah ada file avatar yang diunggah
        if ($request->hasFile('avatar')) {
            // Simpan avatar ke storage dan update path-nya
            $avatarPath = $request->file('avatar')->store('siswa', 'public');
            $userData['avatar'] = $avatarPath;
        }

        // Update data user yang terkait dengan ustadz
        $siswa->user->update($userData);

        // Simpan data ke tabel siswas
        $siswa->update([
            'kelas_id'      => $validated['kelas_id'],
            'ustadz_id'     => $validated['ustadz_id'],
            'kelamin'       => $validated['kelamin'],
            'tempat_lahir'  => $validated['tempat_lahir'],
            'tgl_lahir'     => $validated['tgl_lahir'],
            'alamat'        => $validated['alamat'],
            'nama_ayah'     => $validated['nama_ayah'],
            'nama_ibu'      => $validated['nama_ibu'],
            'no_hp'         => $validated['no_hp'],
        ]);

        return redirect()->route('siswas.index')->with('success', 'Data ustadz berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        //
    }

    public function getUstadzByKelas($kelas_id)
    {
        // Mengambil ustadz berdasarkan kelas_id
        $ustadz = Ustadz::where('kelas_id', $kelas_id)->with('user')->get();
        
        // Mengembalikan data sebagai JSON
        return response()->json($ustadz);
    }

}
