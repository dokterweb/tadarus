@extends('layouts.app')
@section('content_title','Jenis Pembayaran')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
            <h3 class="card-title">Input Payment</h3>
            </div>
            <form method="POST"  action="{{ route('payments.storeBulanans', ['payment' => $payment->id]) }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label >Tahun Ajaran</label>
                        <input type="text" class="form-control" value="{{ $payment->posnya->pos_name.' / '.$payment->periode->periode_end}}" readonly>
                    </div>
                    <div class="form-group">
                        <label >Tahun Ajaran</label>
                        <input type="text" class="form-control" value="{{ $payment->periode->periode_start.' / '.$payment->periode->periode_end}}" readonly>
                    </div>
                    <div class="form-group">
                        <label >Tahun Ajaran</label>
                        <input type="text" class="form-control" value="{{ $payment->payment_type}}" readonly>
                    </div>
                    <div class="form-group">
                        <label >Kelas</label>
                        <select class="form-control" name="kelas_id">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas as $p)
                            <option value="{{$p->id}}">{{$p->nama_kelas}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Tipe Pembayaran</label>
                        <div class="col-sm-8">
                            <input type="number" placeholder="Masukkan Nilai dan Tekan Enter" id="allTarif" name="allTarif" class="form-control">
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">List Bulan</h3></div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        @foreach ($bulan as $b)
                        <tr>
                            <input type="hidden" name="bulan_id[]" value="{{$b->id}}">
                            <td><strong>{{$b->nama_bulan}}</strong></td>
                            <td><input type="text" id="n{{$b->id}}" name="bulan_bill[]" class="form-control" required="">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection


<!-- SweetAlert2 Script -->
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $("#allTarif").keypress(function(event) {
            // Periksa jika tombol Enter ditekan
            if (event.keyCode == 13) {
                event.preventDefault(); // Mencegah default behavior dari Enter (submit form)
                
                // Ambil nilai dari input setelah Enter ditekan
                var allTarif = $("#allTarif").val();

                // Loop melalui elemen-elemen dan set nilai
                @foreach ($bulan as $b)
                    $("#n{{$b->id}}").val(allTarif);
                @endforeach
            }
        });
    });
</script>
@endsection