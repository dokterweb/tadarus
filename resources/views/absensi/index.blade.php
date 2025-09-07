@extends('layouts.app')
@section('content_title','Absensi')

@section('content')

    <div class="card">
        <div class="card-header">
            <a href="{{route('absensis.create')}}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Tambah
            </a>
            </div>
        <div class="card-body">
            <table id="paketTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Ustadz</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensi as $absen)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $absen->siswa->user->name }}</td>
                        <td>{{ $absen->siswa->kelasnya->nama_kelas }}</td>
                        <td>{{ $absen->siswa->ustadz->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse( $absen->tgl_absen)->format('d F Y') }}</td>
                        <td>{{ $absen->status }}</td>
                        <td>
                            <!-- Tombol Hapus -->
                            <form action="{{ route('absensis.destroy', $absen->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE') <!-- Method spoofing untuk DELETE -->
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus absensi ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
@endsection


<!-- SweetAlert2 Script -->
@section('scripts')
<script>
     $(document).ready(function () {
          $('#paketTable').DataTable();
        });
</script>
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