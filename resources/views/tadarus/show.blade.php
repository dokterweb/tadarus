@extends('layouts.app')
@section('content_title','Detail Siswa')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                <h3 class="card-title">Detail Siswa - {{ $siswa->nama_siswa }}</h3>
                </div>
                
                <div class="card-body">    
                    <div class="form-group">
                        <label>Nama Siswa</label>
                        <input type="text" class="form-control" value="{{ $siswa->nama_siswa }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Kelas</label>
                        <input type="text" class="form-control" value="{{ $siswa->kelasnya->nama_kelas }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Kelompok</label>
                        <input type="text" class="form-control" value="{{ $siswa->kelompok->nama_kelompok }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                <h3 class="card-title">Detail Absensi</h3>
                </div>
                
                <div class="card-body">    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensiCounts as $absensi)
                                <tr>
                                    <td>{{ ucfirst($absensi->status) }}</td>
                                    <td>{{ $absensi->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                <h3 class="card-title">List Tadarus</h3>
                </div>
                <div class="card-body">
                    <table id="paketTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Surat</th>
                                <th>Dari Ayat</th>
                                <th>Sampai Ayat</th>
                                <th>Tanggal Tadarus</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tadarusHistories as $history)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $history->surat->sura_name ?? '-' }}</td>
                                    <td>{{ $history->dariayat }}</td>
                                    <td>{{ $history->sampaiayat }}</td>
                                    <td>{{ \Carbon\Carbon::parse($history->tgl_tadarusnya)->format('d-m-Y') }}</td>
                                    <td>{{ $history->keterangan ?? '-' }}</td>
                                    <td>
                                        <a href="{{route('tadaruses.edit',$history->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                                            <form method="POST" action="{{ route('tadaruses.destroy', $history->id) }}" style="display: inline;" id="delete-form-{{ $history->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation({{ $history->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $tadarusHistories->links() }}
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