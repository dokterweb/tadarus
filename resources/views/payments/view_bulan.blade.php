@extends('layouts.app')
@section('content_title','Jenis Pembayaran')

@section('content')
    
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('payments.filter_bulanans', $payment->id) }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label >Tahun Ajaran</label>
                                <input type="text" name="periode_id" class="form-control" value="{{ $payment->periode->periode_start.' / '.$payment->periode->periode_end}}" readonly>
                                @error('periode_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label >Kelas</label>
                                <select class="form-control" name="kelas_id">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelasnyas as $p)
                                    <option value="{{$p->id}}">{{$p->nama_kelas}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                <!-- /.card-body -->
            </form>
            @if(isset($bulanans))
                <div class="card-body">
                    <table id="paketTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Ustadz</th>
                                <th>Biaya SPP</th>
                                <th>Action</th>
                                <!-- Tambahkan kolom lainnya sesuai kebutuhan -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bulanans as $bulanan)
                                <tr>
                                    <td>{{ $bulanan->siswa->user->name }}</td>
                                    <td>{{ $bulanan->siswa->kelasnya->nama_kelas }}</td>
                                    <td>{{ $bulanan->siswa->ustadz->user->name }}</td>
                                    <td>{{ $bulanan->bulan_bill }}</td>
                                    <td><a href="#" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="card-footer">
                <a href="{{route('payments.add_payment_bulan', ['payment' => $payment->id])}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
            </div>
        </div>
    </div>
    
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#paketTable').DataTable();
    });
</script>
@endsection