@extends('layouts.app')
@section('content_title','Edit POS')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                <h3 class="card-title">Edit Data</h3>
                </div>
                <form method="POST" action="{{route('posnyas.update',$posnya->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label >Nama POS</label>
                            <input type="text" name="pos_name" class="form-control" value="{{$posnya->pos_name}}">
                        </div>
                        <div class="form-group">
                            <label >Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" value="{{$posnya->keterangan}}">
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
                <h3 class="card-title">List POS</h3>
                </div>
                <div class="card-body">
                    <table id="paketTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama POS</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($posnyas as $p)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$p->pos_name}} </td>
                                <td>{{$p->keterangan}} </td>
                                <td class="d-flex align-items-center" style="gap: 5px;">
                                    <a href="{{route('posnyas.edit',$p->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="3">No Data</td>
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
@endsection