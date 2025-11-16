@extends('layouts.app')
@section('content_title','Siswa')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                <h3 class="card-title">Edit Data</h3>
                </div>
                <form method="POST" action="{{route('siswas.update',$siswa->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <input type="text" name="nama_siswa" class="form-control" value="{{$siswa->nama_siswa}}">
                            @error('nama_siswa')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Kelompok</label>
                            <select class="form-control" name="kelompok_id">
                                <option value="">Pilih kelompok</option>
                                @foreach ($kelompoks as $p)
                                <option value="{{ $p->id }}" {{ $siswa->kelompok_id == $p->id ? 'selected' : '' }}>{{ $p->nama_kelompok }}</option>
                                @endforeach
                            </select>
                            @error('kelompok_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Kelas</label>
                            <select class="form-control" name="kelas_id">
                                <option value="">Pilih kelas</option>
                                @foreach ($kelasnya as $p)
                                <option value="{{ $p->id }}" {{ $siswa->kelas_id == $p->id ? 'selected' : '' }}>{{ $p->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Kelamin</label>
                            <select name="kelamin" class="form-control" style="width:100%">
                                <option value="laki-laki" {{ $siswa->kelamin == 'laki-laki' ? 'selected' : '' }}>laki-laki</option>
                                <option value="perempuan" {{ $siswa->kelamin == 'perempuan' ? 'selected' : '' }}>perempuan</option>
                            </select>
                            @error('kelamin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-success">
                <div class="card-header">
                <h3 class="card-title">List Siswa</h3>
                </div>
                <div class="card-body">
                    <table id="paketTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>Kelompok</th>
                                <th>Kelas</th>
                                <th>Kelamin</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($siswaview as $p)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$p->nama_siswa}} </td>
                                <td>{{$p->kelompok->nama_kelompok}} </td>
                                <td>{{$p->kelasnya->nama_kelas}} </td>
                                <td>{{$p->kelamin}} </td>
                                <td class="d-flex align-items-center" style="gap: 5px;">
                                    <a href="{{route('siswas.edit',$p->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                                    <form method="POST" action="{{ route('siswas.destroy', $p->id) }}" style="display: inline;" id="delete-form-{{ $p->id }}">
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
                            <td colspan="5">No Data</td>
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