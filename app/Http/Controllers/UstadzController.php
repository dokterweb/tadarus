<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ustadz;
use App\Models\Kelasnya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUstadzRequest;
use App\Http\Requests\UpdateUstadzRequest;

class UstadzController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ustadzs = Ustadz::all();
        return view('ustadzs.index',compact('ustadzs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelasnya::all();
        return view('ustadzs.create',compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUstadzRequest $request)
    {
        $validated = $request->validated();

        // Simpan avatar jika ada
        if($request->hasFile('avatar')){
            $avatarPath = $request->file('avatar')->store('avatars','public');
        }

        // Buat user baru
        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'avatar'    => $avatarPath ?? null, // Jika tidak ada avatar, nilainya null
            'password'  => Hash::make($validated['password']),
        ]);

        // Assign role 'ustadz' ke user
        $user->assignRole('ustadz'); // Pastikan role 'ustadz' sudah ada di database

        // Simpan data ke tabel ustadzs
        Ustadz::create([
            'user_id'       => $user->id,
            'kelas_id'      => $validated['kelas_id'],
            'kelamin'       => $validated['kelamin'],
            'tempat_lahir'  => $validated['tempat_lahir'],
            'tgl_lahir'     => $validated['tgl_lahir'],
            'no_hp'         => $validated['no_hp'],
        ]);

        return redirect()->route('ustadzs.index')->with('success', 'Data Ustadz berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ustadz $ustadz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ustadz $ustadz)
    {
        $kelas = Kelasnya::all();
        return  view('ustadzs.edit',compact('ustadz','kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUstadzRequest $request, Ustadz $ustadz)
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
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $avatarPath;
        }

        // Update data user yang terkait dengan ustadz
        $ustadz->user->update($userData);

        // Simpan data ke tabel ustadzs
        $ustadz->update([
            'kelas_id'      => $validated['kelas_id'],
            'kelamin'       => $validated['kelamin'],
            'tempat_lahir'  => $validated['tempat_lahir'],
            'tgl_lahir'     => $validated['tgl_lahir'],
            'no_hp'         => $validated['no_hp'],
        ]);

        return redirect()->route('ustadzs.index')->with('success', 'Data ustadz berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ustadz $ustadz)
    {
        //
    }
}
