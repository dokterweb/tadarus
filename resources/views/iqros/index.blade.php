@extends('layouts.app')
@section('content_title','Siswa')

@section('content')
    <div class="row">    
        <div class="col-md-12">
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
                        @forelse ($siswas as $p)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$p->nama_siswa}} </td>
                                <td>{{$p->kelompok->nama_kelompok}} </td>
                                <td>{{$p->kelasnya->nama_kelas}} </td>
                                <td>{{$p->kelamin}} </td>
                                <td class="d-flex align-items-center" style="gap: 5px;">
                                    <a href="{{route('iqros.show',$p->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
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