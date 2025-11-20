@extends('layouts.app')
@section('content_title','Ustadz / Ustadzah')

@section('content')

    <div class="card card-primary">
        <div class="card-header">
        <h3 class="card-title">Tambah Data</h3>
        </div>
        <form method="POST" action="{{route('ustadzs.store')}}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nama Ustadz / Ustadzah</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kelompok</label>
                            <select class="form-control" name="kelompok_id">
                                <option value="">Pilih kelompok</option>
                                @foreach ($kelompoks as $p)
                                <option value="{{ $p->id }}" {{ old('kelompok_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_kelompok }}</option>
                                @endforeach
                            </select>
                            @error('kelompok_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kelamin</label>
                            <select name="kelamin" class="form-control" style="width:100%">
                                <option value="laki-laki" {{ old('kelamin') == 'laki-laki' ? 'selected' : '' }}>laki-laki</option>
                                <option value="perempuan" {{ old('kelamin') == 'perempuan' ? 'selected' : '' }}>perempuan</option>
                            </select>
                            @error('kelamin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jenis Pelajaran</label>
                            <select name="mengajari" class="form-control" style="width:100%">
                                <option value="tilawah" {{ old('mengajari') == 'tilawah' ? 'selected' : '' }}>Tilawah</option>
                                <option value="btq" {{ old('mengajari') == 'btq' ? 'selected' : '' }}>BTQ</option>
                            </select>
                            @error('mengajari')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label >Email</label>
                            <input type="text" name="email" class="form-control" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label >Password</label>
                            <input type="password" name="password" class="form-control">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
    
    <div class="card card-success">
        <div class="card-header">
        <h3 class="card-title">List Admin Siswa</h3>
        </div>
        <div class="card-body">
            <table id="paketTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Admin</th>
                        <th>Kelompok</th>
                        <th>Kelamin</th>
                        <th>Pelajaran</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($ustadzs as $p)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$p->user->name}} </td>
                        <td>{{$p->kelompok->nama_kelompok}} </td>
                        <td>{{$p->kelamin}} </td>
                        <td>{{$p->mengajari}} </td>
                        <td>{{$p->user->email}} </td>
                        <td class="d-flex align-items-center" style="gap: 5px;">
                                <a href="{{route('ustadzs.edit',$p->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                            <form method="POST" action="{{ route('adminsiswas.destroy', $p->id) }}" style="display: inline;" id="delete-form-{{ $p->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation({{ $p->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="5">No Data</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
@endsection

@section('scripts')
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
<script>
    $(document).ready(function () {
      $('#paketTable').DataTable();
    });
    function deleteConfirmation(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus dan tidak dapat dipulihkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika tombol "Ya, Hapus!" ditekan, kirim form untuk menghapus data
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection