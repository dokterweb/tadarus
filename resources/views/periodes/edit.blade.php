@extends('layouts.app')
@section('content_title','Kelas')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                <h3 class="card-title">Tambah Data</h3>
                </div>
                <form method="POST" action="{{ route('periodes.update', $periode->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label >Tahun Awal</label>
                            <input type="number" name="periode_start" class="form-control years" onchange="getYear(this.value)" value="{{$periode->periode_start}}">
                        </div>
                        
                        <div class="form-group">
                            <label >Tahun Akhir</label>
                            <input type="number" class="form-control" name="periode_end" id="YearEnd" value="{{$periode->periode_end}}">
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="periode_status" class="form-control" style="width:100%">
                                <option value="1" {{ $periode->periode_status == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $periode->periode_status == '0' ? 'selected' : '' }}>Non Aktif</option>
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
                <h3 class="card-title">List Periode</h3>
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
                            @forelse ($periodenya as $p)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$p->periode_start . '/' . $p->periode_end}} </td>
                                    <td>{{($p->periode_status == 1) ? 'Aktif' : 'Tidak Aktif'}}</td>
                                    <td class="d-flex align-items-center" style="gap: 5px;">
                                        <a href="#" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                                        <form action="#" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"> </i>
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
                
        
    </script>
@stop