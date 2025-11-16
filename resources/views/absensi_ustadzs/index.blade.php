@extends('layouts.app')
@section('content_title','Absensi Ustadz / Ustadzah')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                <h3 class="card-title">Tambah Data</h3>
                </div>
                <form method="POST" action="{{route('absensiustadz.store')}}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label >Tanggal Absen</label>
                            <input type="date" name="tgl_absen" class="form-control"required>
                        </div>
                        <div class="form-group">
                            <label for="ustadz_id">Nama Ustadz</label>
                            <select name="ustadz_id" class="form-control" required>
                                @foreach($ustadzs as $ustadz)
                                    <option value="{{ $ustadz->id }}">{{ $ustadz->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Absensi Siswa</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <option value="hadir">Hadir</option>
                                <option value="ghoib">Ghoib</option>
                                <option value="izin">Izin</option>
                                <option value="tugas">Tugas</option>
                                <option value="sakit">Sakit</option>
                                <option value="pulang">Pulang</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan opsional">
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
                <h3 class="card-title">Data Absensi</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Absensi</th>
                                <th>Nama Ustadz</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensiUstadzs as $absensi)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($absensi->tgl_absen)->format('d-m-Y') }}</td>
                                    <td>{{ $absensi->ustadz->user->name }}</td>
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
                    {{ $absensiUstadzs->links() }}
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