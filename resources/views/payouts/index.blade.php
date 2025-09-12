@extends('layouts.app')
@section('content_title','Pembayaran')

@section('content')

<div class="card card-danger">
    <div class="card-header">
      <h3 class="card-title">Seleksi Siswa</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('payouts.filter_bulanans') }}">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <select class="form-control" name="periode_id">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($periodes as $p)
                        <option value="{{$p->id}}">{{$p->periode_start.' / '.$p->periode_end}}</option>
                        @endforeach
                    </select>
                    <label class="mt-2">Tahun Ajaran</label>
                </div>
                <div class="col-md-5">
                    <select class="form-control" name="siswa_id">
                        <option value="">Pilih Siswa</option>
                        @foreach ($siswas as $p)
                        <option value="{{$p->id}}">{{$p->user->name}}</option>
                        @endforeach
                    </select>
                    <label class="mt-2">Pilih Siswa</label>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

@if(isset($bulanans) && $bulanans->isNotEmpty())

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Data Siswa</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td width='40%'>Nama Siswa</td>
                            <td width='2%'>:</td>
                            <td>{{ $siswanya->user->name }}</td>
                        </tr>
                        <tr>
                            <td width='40%'>Kelas</td>
                            <td width='2%'>:</td>
                            <td>{{ $siswanya->kelasnya->nama_kelas }}</td>
                        </tr>
                        <tr>
                            <td width='40%'>Tempat / Tanggal Lahir</td>
                            <td width='2%'>:</td>
                            <td>{{ $siswanya->tempat_lahir.' / '.$siswanya->tgl_lahir }}</td>
                        </tr>
                        <tr>
                            <td width='40%'>No HP</td>
                            <td width='2%'>:</td>
                            <td>{{ $siswanya->no_hp}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                {{-- <img src="{{Storage::url($siswanya->user->avatar)}}" alt="Girl in a jacket" width="200"> --}}
            </div>
        </div>
    </div>
</div>

<div class="card card-success">
    <div class="card-header">
        <h3 class="card-title">Data Pembyaran Bulanan</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tahun Ajaran</th>
                    <th>Jenis Bayar</th>
                    <th>Total Tagihan</th>
                    <th>Telah Dibayar</th>
                    <th>Sisa Tagihan</th>
                    <th>Act</th>
                </tr>
            </thead>
            <tbody>
                @if(count($bulanans) > 0)
                    <tr>
                        <td>{{ $bulanans[0]->periode_end.'/'.$bulanans[0]->periode_start }}</td>
                        <td>{{ $bulanans[0]->pos_name }}</td>
                        <td>Rp {{ number_format($totBill, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($telahDibayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($totBill - $telahDibayar, 0, ',', '.') }}</td>
                        <td><a href="{{route('payouts.bayar_bulan',['payment' => $bulanans[0]->payment_id, 'siswa' => $bulanans[0]->siswa_id])}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i> Bayar</a></td>
                    </tr>
                @else
                    <tr>
                        <td colspan="6">Data tidak ditemukan</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card">
    <div class="card-body">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-info"></i> Info</h5>
            Data yang anda cari tidak ada, 
          </div>
    </div>
</div>

@endif

@endsection