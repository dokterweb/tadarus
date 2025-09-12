@extends('layouts.app')
@section('content_title','Kelas')

@section('content')
    
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Data</h3>
        </div>
        <form method="POST" action="{{route('payments.store')}}">
            @csrf
            <div class="col-6">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Pembayaran</label>
                        <select class="form-control" name="posnya_id">
                            <option value="">Pilih Pembayaran</option>
                            @foreach ($posnyas as $p)
                            <option value="{{$p->id}}">{{$p->pos_name}}</option>
                            @endforeach
                        </select>
                        @error('posnya_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Tahun Ajaran</label>
                        <select class="form-control" name="periode_id">
                            <option value="">Pilih Pembayaran</option>
                            @foreach ($periodes as $p)
                            <option value="{{$p->id}}">{{$p->periode_start.' - '.$p->periode_end}}</option>
                            @endforeach
                        </select>
                        @error('periode_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Tipe</label>
                        <select name="payment_type" class="form-control" style="width:100%">
                            <option value="bulan" {{ old('payment_type') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                            <option value="bebas" {{ old('payment_type') == 'bebas' ? 'selected' : '' }}>Bebas</option>
                            <option value="buku" {{ old('payment_type') == 'buku' ? 'selected' : '' }}>Buku</option>
                        </select>
                        @error('payment_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>

@endsection