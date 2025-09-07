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
                    @foreach ($siswa->sabaqs as $index => $sabaq)
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
                                    <button class="btn btn-warning btn-edit" data-id="{{ $history->id }}">
                                        Edit
                                    </button>
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
                <h4 class="modal-title" id="tambahHafalanModalLabel">Tambah Sabaq</h4>
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

<!-- Modal Edit -->
@if(isset($history))
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="sabaq_history_id" id="sabaq_history_id" value={{$history->id}}>
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Sabaq</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_tgl_sabaq">Tanggal Muroja'ah</label>
                                <input type="date" name="tgl_sabaq" id="edit_tgl_sabaq" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="edit_sura_no">Nama Surat</label>
                                <select name="surat_no" class="form-control" id="edit_sura_no" required>
                                    <option value="">Pilih Surat</option>
                                    <!-- Surat akan diisi oleh jQuery -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. Surat</label>
                                <input type="text" class="form-control" id="edit_no_surat" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Juz</label>
                                <input type="text" class="form-control" id="edit_jozz" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mulai Hal</label>
                                <input type="text" class="form-control" id="edit_start_page" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Akhir Hal</label>
                                <input type="text" class="form-control" id="edit_end_page" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dari Ayat</label>
                                <input type="number" name="dariayat" id="edit_dariayat" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sampai Ayat</label>
                                <input type="number" name="sampaiayat" id="edit_sampaiayat" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nilai</label>
                                <input type="number" name="nilai" id="edit_nilai" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" id="edit_keterangan" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
    <p>Data tidak ditemukan.</p>
@endif

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

    $(document).ready(function () {
    // Ketika tombol edit diklik, buka modal dan muat data
        $(document).on('click', '.btn-edit', function () {
            var historyId = $(this).data('id');  // Ambil ID dari tombol edit

            // Lakukan AJAX untuk mengambil data berdasarkan historyId
            $.ajax({
                url: '/sabaq/history/' + historyId + '/edit',  // Arahkan ke route edit
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var history = response.data;
                        var suratList = response.suratList;
                        var surat = response.surat;

                        // Isi modal dengan data yang diambil
                        $('#editForm').attr('action', '/sabaq/history/' + historyId + '/update'); // Set action ke URL dengan ID
                        $('#sabaq_history_id').val(history.id);  // Set hidden field dengan ID dari history
                        $('#edit_tgl_sabaq').val(history.tgl_sabaq);
                        $('#edit_dariayat').val(history.dariayat);
                        $('#edit_sampaiayat').val(history.sampaiayat);
                        $('#edit_nilai').val(history.nilai);
                        $('#edit_keterangan').val(history.keterangan);

                        // Mengisi dropdown surat dengan data suratList
                        $('#edit_sura_no').html('<option value="">Pilih Surat</option>'); // Kosongkan dropdown sebelumnya
                        suratList.forEach(function(surat) {
                            $('#edit_sura_no').append('<option value="' + surat.sura_no + '">' + surat.sura_name + '</option>');
                        });

                        // Pilih surat yang sesuai dengan history
                        $('#edit_sura_no').val(surat.sura_no);
                      /*   $('#edit_jozz').val(surat.jozz);
                        $('#edit_start_page').val(surat.start_page);
                        $('#edit_end_page').val(surat.end_page) */;
                        
                        // Tampilkan modal
                        $('#editModal').modal('show');

                        // Update fields (No. Surat, Juz, dll) setelah memilih surat
                        $('#edit_sura_no').change(function () {
                            var selectedSuraNo = $(this).val();
                            if (selectedSuraNo) {
                                $.ajax({
                                    url: '/get-surat-details/' + selectedSuraNo,
                                    type: 'GET',
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            $('#edit_no_surat').val(response.data.no_surat);
                                            $('#edit_jozz').val(response.data.jozz);
                                            $('#edit_start_page').val(response.data.start_page);
                                            $('#edit_end_page').val(response.data.end_page);
                                        }
                                    },
                                    error: function() {
                                        alert('Terjadi kesalahan saat mengambil data surat.');
                                    }
                                });
                            }
                        });

                    } else {
                        alert('Gagal memuat data.');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat memuat data.');
                }
            });
        });
    });

$(document).ready(function () {
    // Mengirim data ke server saat submit form edit
    $('#editForm').submit(function(event) {
        event.preventDefault();  // Mencegah form submit default
        console.log($(this).attr('action'));
        var formData = $(this).serialize();  // Ambil data form

        // Kirim data menggunakan AJAX
        $.ajax({
            url: $(this).attr('action'),  // URL form (sabaq.history.update)
            type: 'PUT',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil diperbarui.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = response.redirect_url;  // Redirect setelah update
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                    showConfirmButton: true
                });
            }
        });
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