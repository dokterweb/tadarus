@extends('layouts.app')
@section('content_title','Siswa')

@section('content')
    
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <h4>Input Siswa</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama Murid</th>
                            <td>{{ $siswa->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Tgl Lahir</th>
                            <td>{{ \Carbon\Carbon::parse($siswa->tgl_lahir)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Nama Ortu</th>
                            <td>{{ $siswa->nama_ayah }} / {{ $siswa->nama_ibu }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $siswa->kelasnya->nama_kelas }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    @if ($siswa->user->avatar)
                        <img src="{{Storage::url($siswa->user->avatar)}}" width="200">
                    @endif
                </div>
            </div>
            <hr>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahHafalanModal">
                Tambah Sabaq
            </button>
            <table id="paketTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tgl Sabaq</th>
                        <th>Nama Surat</th>
                        <th>Dari dan ke Ayat</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sabaqs as $index => $sabaq)
                        @foreach ($sabaq->sabaqHistories as $history)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($history->tgl_sabaq)->format('d F Y') }}</td>
                                <td>
                                    @if ($history->surat)
                                        {{ $history->surat->sura_name }}
                                    @else
                                        Surat Tidak Ditemukan
                                    @endif
                                </td>
                                <td>{{ $history->dariayat }} - {{ $history->sampaiayat }}</td>
                                <td>{{ $history->nilai }}</td>
                                <td>{{ $history->keterangan }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning edit-button" 
                                    data-idedit="{{ $history->id }}" 
                                    data-siswa-idedit="{{ $siswa_id }}" 
                                    data-url="{{ route('sabaq-history.edit', ['siswa_id' => $siswa_id, 'id' => $history->id]) }}" 
                                    data-toggle="modal" data-target="#editHafalanModal">Edit</button>
                                    <button type="button" class="btn btn-danger delete-button" 
                                            data-id="{{ $history->id }}"
                                            data-url="{{ route('sabaq-history.destroy', ['siswa_id' => $siswa_id, 'id' => $history->id]) }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
 <!-- Modal create -->
 <div class="modal fade" id="tambahHafalanModal" tabindex="-1" role="dialog" aria-labelledby="tambahHafalanModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="tambahHafalanModalLabel">Tambah Hafalan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formTambahHafalan" method="POST" action="{{ route('sabaq.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Muroja'ah</label>
                                <input type="date" name="tgl_sabaq" class="form-control" required>
                                <input type="hidden" name="sabaq_id" value="{{ $sabaq_id }}">
                                <input type="hidden" name="siswa_id" value="{{ $siswa->id }}"> <!-- Mengirim siswa_id ke controller -->
                            </div>
                        </div>
                        <div class="col-md-8">   
                            <div class="form-group">
                                <label>Nama Surat</label>
                                <select name="surat_no" id="selectSurat" class="form-control" required>
                                    <option value="">Pilih Surat</option>
                                    @foreach($surat as $s)
                                        <option value="{{ $s->sura_no }}">{{ $s->sura_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. Surat</label>
                                {{-- <input type="text" id="suratId" class="form-control" readonly> --}}
                                <input type="text" id="noSurat" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Juz</label>
                                <input type="text" id="juz" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mulai Hal</label>
                                <input type="text" id="mulaiHal" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Akhir Hal</label>
                                <input type="text" id="akhirHal" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dari Ayat</label>
                                <input type="number" name="dariayat" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sampai Ayat</label>
                                <input type="number" name="sampaiayat" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nilai</label>
                                <input type="number" name="nilai" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

 <!-- Modal edit -->
{{--  
 <div class="modal fade" id="editHafalanModal" tabindex="-1" role="dialog" aria-labelledby="editHafalanModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editHafalanModalLabel">Edit Hafalan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('sabaq-history.update', ['siswa_id' => $siswa_id, 'id' => $history->id]) }}">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Muroja'ah</label>
                                <input type="date" name="tgl_sabaq" id="tgl_sabaqedit" class="form-control" value="{{ old('tgl_sabaq', $history->tgl_sabaq) }}" required>
                            </div>
                        </div>
                        <div class="col-md-8">   
                            <div class="form-group">
                                <label>Nama Surat</label>
                                <select name="surat_id" id="surat_id" class="form-control" required>
                                    <option value="">Pilih Surat</option>
                                    @foreach($surat as $s)
                                        <option value="{{ $s->sura_no }}" {{ $s->sura_no == old('surat_id', $history->surat_id) ? 'selected' : '' }}>
                                            {{ $s->sura_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. Surat</label>
                                <input type="text" id="noSuratedit" class="form-control"  readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Juz</label>
                                <input type="text" id="juzedit" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mulai Hal</label>
                                <input type="text" id="mulaiHaledit" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Akhir Hal</label>
                                <input type="text" id="akhirHaledit" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dari Ayat</label>
                                <input type="number" name="dariayat" class="form-control" value="{{ old('dariayat', $history->dariayat) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sampai Ayat</label>
                                <input type="number" name="sampaiayat" class="form-control" value="{{ old('sampaiayat', $history->sampaiayat) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nilai</label>
                                <input type="number" name="nilai" class="form-control"  value="{{ old('nilai', $history->nilai) }}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control">{{ old('keterangan', $history->keterangan) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
 --}}
@endsection


<!-- SweetAlert2 Script -->
@section('scripts')
<script>
    $(document).ready(function() {
        // Ketika pilihan Surat berubah
        $('#selectSurat').change(function() {
            var sura_no = $(this).val(); // Mendapatkan value sura_no yang dipilih

            if (sura_no) {
                // Mengirim request AJAX untuk mengambil data surat
                $.ajax({
                    url: '/get-surat-details/' + sura_no, // URL untuk AJAX
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            // Mengisi input readonly dengan data yang diterima
                            // $('#suratId').val(data.data.suratId);
                            $('#noSurat').val(data.data.no_surat);        // No. Surat
                            $('#juz').val(data.data.jozz);               // Juz
                            $('#mulaiHal').val(data.data.start_page);    // Mulai Hal
                            $('#akhirHal').val(data.data.end_page);      // Akhir Hal
                        } else {
                            alert(data.message); // Menampilkan error jika surat tidak ditemukan
                        }
                    },
                    error: function() {
                        alert("Terjadi kesalahan saat memuat data surat.");
                    }
                });
            }
        });
    });


    $(document).on('click', '.edit-button', function() {
    var url = $(this).data('url');  // URL untuk edit
    var siswa_id = $(this).data('siswa-idedit');  // Ambil siswa_id

    // Kirim AJAX untuk mendapatkan data
    $.get(url, function(data) {
         if (data.status === 'success') {
        // Isi data modal berdasarkan response dari server
        $('#tgl_sabaqedit').val(data.tgl_sabaq);
        
        // Pilih surat_id yang sesuai dengan data yang didapat
        $('#surat_id').val(data.surat_id);

        // Isi data lainnya
        $('#noSuratedit').val(data.data.no_surat);
        $('#juzedit').val(data.data.jozz);
        $('#mulaiHaledit').val(data.data.start_page);
        $('#akhirHaledit').val(data.data.end_page);
        $('#dariayat').val(data.data.dariayat);
        $('#sampaiayat').val(data.data.sampaiayat);
        $('#nilai').val(data.data.nilai);
        $('#keterangan').val(data.data.keterangan);

        // Update form action untuk update data
        $('#editForm').attr('action', '/sabaq-history/' + siswa_id + '/update/' + data.id);
        // Tampilkan modal setelah data diisi
            $('#editHafalanModal').modal('show');
         }
    });
});

$(document).on('click', '.delete-button', function() {
    var url = $(this).data('url');  // URL untuk edit
    
    // Tampilkan SweetAlert2 untuk konfirmasi hapus
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengkonfirmasi hapus, kirim permintaan DELETE
            $.ajax({
                url: url, // Ambil URL dari data-url
                type: 'DELETE',  // Pastikan menggunakan metode DELETE
                data: {
                    _token: '{{ csrf_token() }}'  // Kirim CSRF token untuk permintaan DELETE
                },
                success: function(response) {
                    // Jika berhasil, tampilkan pesan sukses dan hapus baris di tabel
                    Swal.fire(
                        'Dihapus!',
                        'Data berhasil dihapus.',
                        'success'
                    ).then(function() {
                        location.reload(); // Reload halaman untuk memperbarui tampilan
                    });
                },
                error: function() {
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat menghapus data.',
                        'error'
                    );
                }
            });
        }
    });
});


</script>

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