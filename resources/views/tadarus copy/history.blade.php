@extends('layouts.app')
@section('content_title','Siswa')

@section('content')
    <div class="card card-success">
        <div class="card-header">
        <h3 class="card-title">List Siswa</h3>
        </div>
        
        <div class="card-body">
            
            <div class="col-md-6">
                <h4>Input Siswa</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Nama Murid</th>
                        <td>{{ $siswa->nama_siswa }}</td>
                    </tr>
                    <tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $siswa->kelasnya->nama_kelas }}</td>
                    </tr>
                    <tr>
                        <th>Kelompok</th>
                        <td>{{ $siswa->kelompok->nama_kelompok }}</td>
                    </tr>
                </table>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahTadarusModal">
                    Tambah Tadarus
                </button>
            </div>
            
            <hr>
            <div class="col-md-12 table-responsive p-3">
                <table id="paketTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal Tadarus</th>
                            <th>Nama Surat</th>
                            <th>Dari Ayat</th>
                            <th>Sampai Ayat</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa->tadarusHistories as $history)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{ \Carbon\Carbon::parse($history->tgl_tadarusnya)->format('d-m-Y') }}</td>
                            <td>{{ $history->surat->sura_name ?? '-' }}</td>
                            <td>{{ $history->dariayat }}</td>
                            <td>{{ $history->sampaiayat }}</td>
                            <td>{{ $history->keterangan }}</td>
                            <td>
                                <button type="button"
                                    class="btn btn-sm btn-warning btn-edit-tadarus"
                                    data-id="{{ $history->id }}">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-tadarus" data-id="{{ $history->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    Belum ada riwayat tadarus untuk siswa ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        
 <!-- Modal create -->
 <div class="modal fade" id="tambahTadarusModal" tabindex="-1" role="dialog" aria-labelledby="tambahTadarusModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="tambahTadarusModalLabel">Tambah Tadarus</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formTambahHafalan" method="POST" action="{{ route('tadarus.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Muroja'ah</label>
                                <input type="date" name="tgl_tadarusnya" class="form-control" required>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Surat</label>
                                <input type="text" id="noSurat" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Juz</label>
                                <input type="text" id="juz" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mulai Hal</label>
                                <input type="text" id="mulaiHal" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Akhir Hal</label>
                                <input type="text" id="akhirHal" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dari Ayat</label>
                                <input type="number" name="dariayat" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sampai Ayat</label>
                                <input type="number" name="sampaiayat" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <select name="keterangan" class="form-control" style="width:100%">
                                    <option value="Jayyid Jayyid" {{ old('keterangan') == 'Jayyid Jayyid' ? 'selected' : '' }}>Jayyid Jayyid</option>
                                    <option value="Jayyid" {{ old('keterangan') == 'Jayyid' ? 'selected' : '' }}>Jayyid</option>
                                    <option value="Maqbul" {{ old('keterangan') == 'Maqbul' ? 'selected' : '' }}>Maqbul</option>
                                </select>
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
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="editForm">
          @csrf
          @method('PUT')
          <input type="hidden" name="tadarus_history_id" id="tadarus_history_id">
          <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
  
          <div class="modal-header">
            <h5 class="modal-title">Edit Tadarus</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
  
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Tanggal Muroja'ah</label>
                  <input type="date" name="tgl_tadarusnya" id="edit_tgl_tadarusnya" class="form-control" required>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label>Nama Surat</label>
                  <select name="surat_no" id="edit_surat_no" class="form-control" required>
                    <option value="">Pilih Surat</option>
                    {{-- akan diisi via JS (suratList) --}}
                  </select>
                </div>
              </div>
  
              <div class="col-md-6">
                <div class="form-group">
                  <label>No. Surat</label>
                  <input type="text" id="edit_no_surat" class="form-control" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Juz</label>
                  <input type="text" id="edit_jozz" class="form-control" readonly>
                </div>
              </div>
              {{-- <div class="col-md-6">
                <div class="form-group">
                  <label>Mulai Hal</label>
                  <input type="text" id="edit_start_page" class="form-control" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Akhir Hal</label>
                  <input type="text" id="edit_end_page" class="form-control" readonly>
                </div>
              </div> --}}
  
              <div class="col-md-6">
                <div class="form-group">
                  <label>Dari Ayat</label>
                  <input type="text" name="dariayat" id="edit_dariayat" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Sampai Ayat</label>
                  <input type="text" name="sampaiayat" id="edit_sampaiayat" class="form-control" required>
                </div>
              </div>
  
              <div class="col-md-12">
                <div class="form-group">
                  <label>Keterangan</label>
                  <select name="keterangan" id="edit_keterangan" class="form-control">
                    <option value="Jayyid Jayyid">Jayyid Jayyid</option>
                    <option value="Jayyid">Jayyid</option>
                    <option value="Maqbul">Maqbul</option>
                  </select>
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
  
  
@endsection

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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $(document).ready(function () {

        // 🔹 Buka modal & load data
        $(document).on('click', '.btn-edit-tadarus', function () {
            var historyId = $(this).data('id');

            $.ajax({
                url: '/tadaruses/history/' + historyId + '/edit',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        var history   = response.data;
                        console.log(history);

                        var surat     = response.surat;
                        var suratList = response.suratList;

                        $('#tadarus_history_id').val(history.id);
                        $('#edit_tgl_tadarusnya').val(history.tgl_tadarusnya);
                        $('#edit_dariayat').val(history.dariayat ?? history.dari_ayat);
                        $('#edit_sampaiayat').val(history.sampaiayat ?? history.sampai_ayat);
                        $('#edit_start_page').val(history.start_page ?? '');
                        $('#edit_end_page').val(history.end_page ?? '');
                        $('#edit_keterangan').val(history.keterangan);

                        // isi dropdown surat
                        $('#edit_surat_no').html('<option value="">Pilih Surat</option>');
                        suratList.forEach(function (s) {
                            $('#edit_surat_no').append(
                                '<option value="' + s.sura_no + '">' + s.sura_name + '</option>'
                            );
                        });

                        // pilih surat yang sedang dipakai
                        if (surat) {
                            $('#edit_surat_no').val(surat.sura_no);
                            $('#edit_no_surat').val(surat.sura_no);
                            $('#edit_jozz').val(surat.jozz ?? '');
                            $('#edit_start_page').val(surat.start_page ?? '');
                            $('#edit_end_page').val(surat.end_page ?? '');
                        }

                        $('#editModal').modal('show');
                    } else {
                        alert(response.message || 'Gagal memuat data.');
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan saat memuat data.');
                }
            });
        });

        // 🔹 Update detail surat ketika ganti surat di dropdown
        $('#edit_surat_no').on('change', function () {
            var suraNo = $(this).val();
            if (!suraNo) return;

            $.ajax({
                url: '/get-surat-details/' + suraNo,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#edit_no_surat').val(response.data.no_surat);
                        $('#edit_jozz').val(response.data.jozz);
                        $('#edit_start_page').val(response.data.start_page);
                        $('#edit_end_page').val(response.data.end_page);
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan saat mengambil data surat.');
                }
            });
        });

        // 🔹 Submit form edit via AJAX
        $('#editForm').on('submit', function (e) {
            e.preventDefault();

            var id       = $('#tadarus_history_id').val();
            var formData = $(this).serialize();

            $.ajax({
                url: '/tadaruses/history/' + id,
                type: 'POST',
                data: formData + '&_method=PUT',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#editModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data tadarus berhasil diperbarui',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire('Gagal', response.message || 'Terjadi kesalahan.', 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                }
            });
        });
    });

    $(document).ready(function (){
        // 🔹 Klik tombol hapus
        $(document).on('click', '.btn-delete-tadarus', function () {
            var historyId = $(this).data('id');  // Ambil ID dari tombol hapus

            // Tampilkan konfirmasi SweetAlert sebelum hapus
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lakukan AJAX untuk menghapus data
                    $.ajax({
                        url: '/tadaruses/history/' + historyId,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                // Hapus row di tabel
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Data berhasil dihapus',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                // Menghapus row dari tabel
                                $('button[data-id="' + historyId + '"]').closest('tr').remove();
                            } else {
                                Swal.fire('Gagal', response.message || 'Terjadi kesalahan.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                        }
                    });
                }
            });
        });

    });

</script>

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
<script>
   $(document).ready(function () {
     $('#paketTable').DataTable();
   });
   function deleteConfirmation(id) {
       Swal.fire({
           title: 'Apakah Anda yakin?',
           text: "Data ini akan dihapus dan tidak dapat dipulihkan!",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Ya, Hapus!',
           cancelButtonText: 'Batal'
       }).then((result) => {
           if (result.isConfirmed) {
               // Jika tombol "Ya, Hapus!" ditekan, kirim form untuk menghapus data
               document.getElementById('delete-form-' + id).submit();
           }
       });
   }
</script>
@endsection