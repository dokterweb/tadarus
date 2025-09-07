@extends('layouts.app')
@section('content_title','Kelas')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                <h3 class="card-title">Tambah Data</h3>
                </div>
                <form method="POST"action="{{ route('periodes.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label >Tahun Awal</label>
                            <input type="text" name="periode_start" class="form-control years" onchange="getYear(this.value)">
                        </div>
                        
                        <div class="form-group">
                            <label >Tahun Akhir</label>
                            <input type="text" class="form-control" name="periode_end" id="YearEnd">
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="periode_status" class="form-control" style="width:100%">
                                <option value="">-Pilih Status-</option>
                                <option value="1">Aktif</option>
                                <option value="0">Non Aktif</option>
                            </select>
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
                <h3 class="card-title">List Kelas</h3>
                </div>
                <div class="card-body">
                    <table id="paketTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Thn Pelajaran</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($periodes as $p)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$p->periode_start . '/' . $p->periode_end}} </td>
                                    <td>{{($p->periode_status == 1) ? 'Aktif' : 'Tidak Aktif'}}</td>
                                    <td class="d-flex align-items-center" style="gap: 5px;">
                                        <a href="{{route('periodes.edit',$p->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                                        <form action="{{ route('periodes.destroy', $p->id) }}" method="POST" id="delete-form-{{ $p->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $p->id }})">
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

@endsection

@section('css')
    <link href="{{asset('adminlte')}}/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
@stop

@section('scripts')
    <script src="{{asset('adminlte')}}/dist/js/bootstrap-datepicker.min.js"></script>
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
        $(".years").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true,
            todayHighlight: true,
            orientation: 'bottom'
        });
        

        function getYear(value) {
            var yearsend = parseInt(value) + 1;
            $("#YearEnd").val(yearsend);
        }
    
        function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengonfirmasi, submit form untuk menghapus data
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
        
    </script>
@stop