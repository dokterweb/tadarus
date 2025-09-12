@extends('layouts.app')
@section('content_title','Bayar Bulanan')

@section('content')

<div class="card card-danger">
    <div class="card-header">
      <h3 class="card-title">List dan Pemabayaran</h3>
    </div>
    <div class="card-body">
        

        @if(count($bulanans) > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bulan</th>
                        <th>Tagihan</th>
                        <th>Status</th>
                        <th>Bill Amount</th>
                        <th>Date</th>
                        <th>Bukti</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bulanans as $b)
                        <tr>
                            @if ($b->bulan_status == '1')
                                <td>{{ $b->id }}</td>
                                <td>{{ $b->bulan->nama_bulan }}</td>
                                <td>{{ $b->bulan_bill }}</td>
                                <td><span class="badge badge-success">Lunas</span></td>
                                <td>{{ $b->bulan_number_pay }}</td>
                                <td>{{ $b->bulan_date_pay }}</td>
                                <td>
                                @if ($b->bukti_bulan)
                                    <a href="{{ asset('storage/' . $b->bukti_bulan) }}" target="_blank">Bukti</a> 
                                @endif
                                </td>
                                <td>
                                    <a href="#" target="_blank">Bukti</a>

                                    {{-- untuk cetak pdf ,mengirimkan bulan_id dan siswa_id--}}
                                </td>
                            @else
                                <form action="{{ route('payoutsiswas.bayarbulanan') }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" value="{{ $b->id }}">
                                    <td>{{ $b->id }}</td>
                                    <td>{{ $b->bulan->nama_bulan }}</td>
                                    <td>{{ $b->bulan_bill }}</td>
                                    <td><span class="badge badge-danger">Belum Bayar</span></td>
                                    <td>{{ $b->bulan_number_pay }}</td>
                                    <td>{{ $b->bulan_date_pay }}</td>
                                    <td></td>
                                    <td><button type="submit" class="btn btn-primary btn-sm">Bayar</button></td>
                                </form>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        @else
            <p>Data tidak ditemukan.</p>
        @endif
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

@endsection