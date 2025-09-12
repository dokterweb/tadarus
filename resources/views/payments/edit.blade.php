@extends('layouts.app')
@section('content_title','Edit Jenis Pembayaran')

@section('content')
    
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Data</h3>
        </div>
        <form method="POST" action="{{ route('payments.update', $payment->id) }}">
            @csrf
            @method('PUT') <!-- Menambahkan method PUT untuk mengupdate data -->
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Pembayaran</label>
                    <select class="form-control" name="posnya_id">
                        <option value="">Pilih Pembayaran</option>
                        @foreach ($posnyas as $p)
                            <option value="{{ $p->id }}" {{ $payment->posnya_id == $p->id ? 'selected' : '' }}>
                                {{ $p->pos_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('posnya_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
        
                <div class="form-group">
                    <label>Tahun Ajaran</label>
                    <select class="form-control" name="periode_id">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" {{ $payment->periode_id == $p->id ? 'selected' : '' }}>
                                {{ $p->periode_start . ' - ' . $p->periode_end }}
                            </option>
                        @endforeach
                    </select>
                    @error('periode_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
        
                <div class="form-group">
                    <label>Jenis Pembayaran</label>
                    <select name="payment_type" class="form-control">
                        <option value="bulan" {{ $payment->payment_type == 'bulan' ? 'selected' : '' }}>Bulan</option>
                        <option value="bebas" {{ $payment->payment_type == 'bebas' ? 'selected' : '' }}>Bebas</option>
                        <option value="buku" {{ $payment->payment_type == 'buku' ? 'selected' : '' }}>Buku</option>
                    </select>
                    @error('payment_type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
        
    </div>

@endsection