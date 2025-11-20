@extends('layouts.app')
@section('content_title','Absensi Siswa')

@section('content')    
    <div class="card card-success">
        <div class="card-header">
        <h3 class="card-title">Data Absensi</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('absensiswas.index') }}" method="GET">
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $tanggalMulai->toDateString()) }}">
                </div>
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $tanggalSelesai->toDateString()) }}">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
            <hr>
            <table id="paketTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal Absensi</th>
                        <th>Nama Siswa</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensiSiswas as $absensi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($absensi->tgl_absen)->format('d-m-Y') }}</td>
                            <td>{{ $absensi->siswa->nama_siswa }}</td>
                            <td>{{ ucfirst($absensi->status) }}</td>
                            <td>{{ $absensi->keterangan ?? '-' }}</td>
                            <td>
                                <a href="{{ route('absensiustadz.edit', $absensi->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('absensiustadz.destroy', $absensi->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination -->
            
            
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