@extends('layouts.app')
@section('content_title','Siswa')

@section('content')
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Data Siswa</h4>
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
            </div>
            <hr>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahIqroModal">
                Tambah Iqro
            </button>
            <div class="row-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Iqro Jilid</th>
                            <th>Halaman</th>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($iqroHistories as $index => $history)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $history->iqro_jilid }}</td>
                                <td>{{ $history->halaman }}</td>
                                <td>{{ $history->nilai }}</td>
                                <td>{{ $history->keterangan }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning edit-button" 
                                        data-id="{{ $history->id }}" 
                                        data-siswa-id="{{ $siswa_id }}" 
                                        data-toggle="modal" 
                                        data-target="#editIqroModal">Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal Tambah Iqro History -->
<div class="modal fade" id="tambahIqroModal" tabindex="-1" role="dialog" aria-labelledby="tambahIqroModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="tambahIqroModalLabel">Tambah Hafalan Iqro</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('iqro-history.store', ['siswa_id' => $siswa_id]) }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal Iqro</label>
                        <input type="date" name="tgl_iqro" class="form-control" required>
                        <input type="hidden" name="iqro_id" value="{{ $iqro_id }}">
                        <input type="hidden" name="siswa_id" value="{{ $siswa->id }}"> 
                    </div>
                    <div class="form-group">
                        <label>Iqro Jilid</label>
                        <select name="iqro_jilid" class="form-control" required>
                            <option value="">Pilih Jilid</option>
                            <option value="Jilid 1">Iqro Jilid 1</option>
                            <option value="Jilid 2">Iqro Jilid 2</option>
                            <option value="Jilid 3">Iqro Jilid 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Halaman</label>
                        <input type="number" name="halaman" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nilai</label>
                        <input type="number" name="nilai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" required></textarea>
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
{{-- 
<div class="modal fade" id="editIqroModal" tabindex="-1" role="dialog" aria-labelledby="editIqroModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editIqroModalLabel">Edit Hafalan Iqro</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT') <!-- Menggunakan metode PUT untuk update -->
                <div class="modal-body">
                    <!-- Form fields for editing -->
                    <div class="form-group">
                        <label for="iqro_jilid">Iqro Jilid</label>
                        <select name="iqro_jilid" id="iqro_jilid" class="form-control" required>
                            <option value="Jilid 1" {{ old('iqro_jilid', $history->iqro_jilid) == 1 ? 'selected' : '' }}>Iqro Jilid 1</option>
                            <option value="Jilid 2" {{ old('iqro_jilid', $history->iqro_jilid) == 2 ? 'selected' : '' }}>Iqro Jilid 2</option>
                            <option value="Jilid 3" {{ old('iqro_jilid', $history->iqro_jilid) == 3 ? 'selected' : '' }}>Iqro Jilid 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="halaman">Halaman</label>
                        <input type="number" name="halaman" id="halaman" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nilai">Nilai</label>
                        <input type="number" name="nilai" id="nilai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tgl_iqro">Tanggal Iqro</label>
                        <input type="date" name="tgl_iqro" id="tgl_iqro" class="form-control" required>
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
   $(document).on('click', '.edit-button', function() {
        var history_id = $(this).data('id');  // Ambil id dari iqro_history
        var siswa_id = $(this).data('siswa-id'); // Ambil siswa_id

        // Kirim request untuk mendapatkan data iqro_history
        $.get('/iqro-history/' + siswa_id + '/' + history_id + '/edit', function(data) {
            // Isi data modal berdasarkan response dari server
            $('#tgl_iqro').val(data.iqroHistory.tgl_iqro);
            $('#iqro_jilid').val(data.iqroHistory.iqro_jilid);
            $('#halaman').val(data.iqroHistory.halaman);
            $('#nilai').val(data.iqroHistory.nilai);
            $('#keterangan').val(data.iqroHistory.keterangan);

            // Update form action untuk update data
            $('#editForm').attr('action', '/iqro-history/' + siswa_id + '/' + history_id + '/update');
        });
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