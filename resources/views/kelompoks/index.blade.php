@extends('layouts.app')
@section('content_title','Kelompok')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                <h3 class="card-title">Tambah Data</h3>
                </div>
                <form method="POST" action="{{route('kelompoks.store')}}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label >Nama kelompok</label>
                            <input type="text" name="nama_kelompok" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Jenis Pelajaran</label>
                            <select name="pelajaran" class="form-control" style="width:100%">
                                <option value="tilawah" {{ old('pelajaran') == 'tilawah' ? 'selected' : '' }}>Tilawah</option>
                                <option value="btq" {{ old('pelajaran') == 'btq' ? 'selected' : '' }}>BTQ</option>
                            </select>
                            @error('pelajaran')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Jenis</label>
                            <select name="jenis" class="form-control" style="width:100%">
                                <option value="putra" {{ old('jenis') == 'putra' ? 'selected' : '' }}>Putra</option>
                                <option value="putri" {{ old('jenis') == 'putri' ? 'selected' : '' }}>Putri</option>
                            </select>
                            @error('jenis')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-success">
                <div class="card-header">
                <h3 class="card-title">List kelompok</h3>
                </div>
                <div class="card-body">
                    <table id="paketTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama kelompok</th>
                                <th>Jenis Pelajaran</th>
                                <th>Jenis</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($kelompoks as $p)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$p->nama_kelompok}} </td>
                                <td>{{$p->pelajaran}} </td>
                                <td>{{$p->jenis}} </td>
                                <td class="d-flex align-items-center" style="gap: 5px;">
                                    <a href="{{route('kelompoks.edit',$p->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                                    <form method="POST" action="{{ route('kelompoks.destroy', $p->id) }}" style="display: inline;" id="delete-form-{{ $p->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation({{ $p->id }})">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                    
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="4">No Data</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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