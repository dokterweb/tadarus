@extends('layouts.app')
@section('content_title','Absensi Ustadz / Ustadzah')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                <h3 class="card-title">Edit Data</h3>
                </div>
                <form method="POST" action="{{route('absensiustadz.update',$absensi->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label >Tgl Absen</label>
                            <input type="date" name="tgl_absen" class="form-control" value="{{$absensi->tgl_absen}}">
                        </div>
                        <div class="form-group">
                            <label >Nama Ustadz</label>
                            <select class="form-control" name="ustadz_id" id="ustadz_id">
                                @foreach ($ustadzs as $p)
                                <option value="{{ $p->id }}" {{ $absensi->ustadz_id == $p->id ? 'selected' : '' }}>{{ $p->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" style="width:100%">
                                <option value="hadir" {{ $absensi->status == 'hadir' ? 'selected' : '' }}>hadir</option>
                                <option value="ghoib" {{ $absensi->status == 'ghoib' ? 'selected' : '' }}>ghoib</option>
                                <option value="izin" {{ $absensi->status == 'izin' ? 'selected' : '' }}>izin</option>
                                <option value="tugas" {{ $absensi->status == 'tugas' ? 'selected' : '' }}>tugas</option>
                                <option value="sakit" {{ $absensi->status == 'sakit' ? 'selected' : '' }}>sakit</option>
                                <option value="pulang" {{ $absensi->status == 'pulang' ? 'selected' : '' }}>pulang</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label >Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" value="{{$absensi->keterangan}}">
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
                            @foreach($absensiUstadzs as $a)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($a->tgl_absen)->format('d-m-Y') }}</td>
                                    <td>{{ $a->ustadz->user->name }}</td>
                                    <td>{{ ucfirst($a->status) }}</td>
                                    <td>{{ $a->keterangan ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('absensiustadz.edit', $a->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('absensiustadz.destroy', $a->id) }}" method="POST" style="display:inline;">
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
@endsection