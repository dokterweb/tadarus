@extends('layouts.app')
@section('content_title','Absensi Siswa')

@section('content')
   
    <div class="card">
        <div class="card-body">
            <form action="{{ route('absensis.ustadzStore') }}" method="POST">
                @csrf
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tgl_absen">Tanggal Absensi</label>
                        <input type="date" name="tgl_absen" id="tgl_absen" class="form-control" required>
                    </div>
            
                    <div class="form-group">
                        <label for="siswa">Data Siswa</label>
                        <div id="siswa_container">
                            @foreach ($siswas as $siswa)
                                <div class="form-group">
                                    <label for="status_{{ $siswa->id }}">{{ $siswa->user->name }}</label>
                                    <select class="form-control" name="status[{{ $siswa->id }}]" id="status_{{ $siswa->id }}">
                                        <option value="hadir">Hadir</option>
                                        <option value="absen">Absen</option>
                                        <option value="izin">Izin</option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Absensi</button>
            </form>
            
        </div>
    </div>
    
@endsection


@section('scripts')
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    @elseif (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    @endif
@endsection