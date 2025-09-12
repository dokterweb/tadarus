@extends('layouts.app')
@section('content_title','Pembayaran')

@section('content')

<div class="card card-danger">
    <div class="card-header">
      <h3 class="card-title">Seleksi Siswa</h3>
    </div>
    <div class="card-body">
        <div class="container">
            <h4>Pembayaran Bulanan</h4>
            <p>Tagihan: Rp {{ number_format($bulanan->bulan_bill, 0, ',', '.') }}</p>
        
            <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>
        </div>
        
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                alert("Pembayaran sukses!");
                console.log(result);

                // Kirim ke backend untuk update status
                fetch("{{ route('payoutsiswas.update_status') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: "{{ $bulanan->id }}",
                        status: "paid"
                    })
                }).then(res => res.json())
                  .then(data => {
                      if (data.success) {
                          window.location.href = "{{ route('payoutsiswas.index') }}";
                      }
                  });
            },
            onPending: function(result){
                alert("Menunggu pembayaran!");
                console.log(result);
            },
            onError: function(result){
                alert("Pembayaran gagal!");
                console.log(result);
            },
            onClose: function(){
                alert('Kamu menutup popup tanpa menyelesaikan pembayaran');
            }
        });
    });
</script>
@endsection