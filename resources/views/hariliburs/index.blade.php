@extends('layouts.app')
@section('content_title','Hari Libur')

@section('content')
    
    <div class="card">
        <div class="card-header">
            <a href="{{route('hariliburs.create')}}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Tambah
            </a>
            <a href="{{route('hariliburs.monthly')}}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Tambah Perbulan
            </a>
        </div>
        <div class="card-body">
            <table id="paketTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Libur</th>
                        <th>Tipe</th>
                        <th>Berlaku Untuk</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hariliburs as $libur)
                        <tr>
                            <td>
                                {{ $libur->tanggal_mulai->format('d-m-Y') }}
                                @if($libur->tanggal_selesai->ne($libur->tanggal_mulai))
                                    s/d {{ $libur->tanggal_selesai->format('d-m-Y') }}
                                @endif
                            </td>
                            <td>{{ $libur->nama_libur }}</td>
                            <td>{{ ucfirst($libur->tipe) }}</td>
                            <td>{{ ucfirst($libur->berlaku_untuk) }}</td>
                            <td>{{ $libur->keterangan ?? '-' }}</td>
                            <td>
                                <a href="{{ route('hariliburs.edit', $libur->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('hariliburs.destroy', $libur->id) }}" method="POST"
                                    style="display:inline-block;"
                                    onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada data hari libur.</td></tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
@endsection
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
 <script>
     
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