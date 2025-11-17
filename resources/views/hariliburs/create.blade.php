@extends('layouts.app')
@section('content_title','Hari Libur')

@section('content')
    
    <div class="card card-primary">
        <div class="card-header">
        <h3 class="card-title">Tambah Data</h3>
        </div>
        <form method="POST" action="{{route('hariliburs.store')}}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label >Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label >Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label >Nama Libur</label>
                            <input type="text" name="nama_libur" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipe</label>
                            <select name="tipe" class="form-control" style="width:100%">
                                <option value="nasional" {{ old('tipe') == 'nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="sekolah" {{ old('tipe') == 'sekolah' ? 'selected' : '' }}>sekolah</option>
                                <option value="mingguan" {{ old('tipe') == 'mingguan' ? 'selected' : '' }}>mingguan</option>
                            </select>
                            @error('tipe')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Berlaku Untuk</label>
                            <select name="berlaku_untuk" class="form-control" style="width:100%">
                                <option value="semua" {{ old('berlaku_untuk') == 'semua' ? 'selected' : '' }}>semua</option>
                                <option value="siswa" {{ old('berlaku_untuk') == 'siswa' ? 'selected' : '' }}>siswa</option>
                                <option value="ustadz" {{ old('berlaku_untuk') == 'ustadz' ? 'selected' : '' }}>ustadz</option>
                            </select>
                            @error('berlaku_untuk')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label >Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" >
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    
     <!-- SweetAlert2 Script -->
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