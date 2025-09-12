@extends('layouts.app')
@section('content_title','Jenis Pembayaran')

@section('content')
    
    <div class="card">
        <div class="card-header">
            <a href="{{route('payments.create')}}" class="btn btn-info"><i class="fas fa-plus-circle"></i> Tambah</a>
        </div>
        <div class="card-body">
            <table id="paketTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pembayaran</th>
                        <th>Jenis Pembayaran</th>
                        <th>Tipe</th>
                        <th>Tahun</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($payments as $p)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$p->posnya->pos_name}} </td>
                        <td>{{$p->posnya->pos_name.'-'.$p->periode->periode_start}} </td>
                        <td>{{$p->payment_type}} </td>
                        <td>{{$p->periode->periode_start.'-'.$p->periode->periode_end}} </td>
                        <td>
                            <a href="{{route('payments.edit',$p->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                            <a href="{{route('payments.view_bulan', ['payment' => $p->id])}}" class="btn btn-sm btn-success">Bulanan</a>    
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


<!-- SweetAlert2 Script -->
@section('scripts')

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