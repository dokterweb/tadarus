@extends('layouts.app')
@section('content_title','Hari Libur')

@section('content')
    
    <div class="card card-primary">
        <div class="card-header">
        <h3 class="card-title">Edit Data</h3>
        </div>
        <form method="POST" action="{{route('hariliburs.update',$harilibur->id)}}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label >Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $harilibur->tanggal_mulai)->format('Y-m-d') }}">
                            @error('tanggal_mulai')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label >Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $harilibur->tanggal_selesai)->format('Y-m-d') }}">
                            @error('tanggal_selesai')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label >Nama Libur</label>
                            <input type="text" name="nama_libur" class="form-control" value="{{ old('nama_libur', $harilibur->nama_libur) }}">
                            @error('nama_libur')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipe</label>
                            <select name="tipe" class="form-control" style="width:100%">
                                <option value="nasional" {{ old('tipe', $harilibur->tipe) == 'nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="sekolah" {{ old('tipe', $harilibur->tipe) == 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                                <option value="mingguan" {{ old('tipe', $harilibur->tipe) == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
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
                                <option value="semua" {{ old('berlaku_untuk', $harilibur->berlaku_untuk) == 'semua' ? 'selected' : '' }}>Semua</option>
                                <option value="siswa" {{ old('berlaku_untuk', $harilibur->berlaku_untuk) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="ustadz" {{ old('berlaku_untuk', $harilibur->berlaku_untuk) == 'ustadz' ? 'selected' : '' }}>Ustadz</option>
                            </select>
                            @error('berlaku_untuk')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label >Keterangan</label>
                            <input type="text" name="keterangan" class="form-control"  value="{{ old('keterangan', $harilibur->keterangan) }}">
                            @error('keterangan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('hariliburs') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update</button>
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