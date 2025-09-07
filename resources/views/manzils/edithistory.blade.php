@extends('layouts.app')
@section('content_title','Siswa')

@section('content')
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('manzils.history.update', $history->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tgl_manzil">Tanggal Muroja'ah</label>
                            <input type="date" name="tgl_manzil" class="form-control" value="{{ $history->tgl_manzil }}" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="surat_no">Nama Surat</label>
                            <select name="surat_no" id="edit_sura_no" class="form-control" required>
                                
                                @foreach ($suratList as $suratItem)
                                    <option value="{{ $suratItem->sura_no }}" 
                                        {{ $history->surat_no == $suratItem->sura_no ? 'selected' : '' }}>
                                        {{ $suratItem->sura_name }}
                                    </option>
                                @endforeach
                                                        
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_no_surat">No. Surat</label>
                            <input type="text" class="form-control" id="edit_no_surat" value="{{ $surat->no_surat }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_jozz">Juz</label>
                            <input type="text" class="form-control" id="edit_jozz" value="{{ $surat->jozz }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_start_page">Mulai Hal</label>
                            <input type="text" class="form-control" id="edit_start_page" value="{{ $surat->start_page }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_end_page">Akhir Hal</label>
                            <input type="text" class="form-control" id="edit_end_page" value="{{ $surat->end_page }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dariayat">Dari Ayat</label>
                            <input type="number" name="dariayat" class="form-control" value="{{ $history->dariayat }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sampaiayat">Sampai Ayat</label>
                            <input type="number" name="sampaiayat" class="form-control" value="{{ $history->sampaiayat }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nilai">Nilai</label>
                            <input type="number" name="nilai" class="form-control" value="{{ $history->nilai }}" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" class="form-control">{{ $history->keterangan }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    
@endsection


<!-- SweetAlert2 Script -->
@section('scripts')

<script>
    $(document).ready(function () {
        $('#edit_sura_no').change(function () {
            var selectedSuraNo = $(this).val();

            if (selectedSuraNo) {
                $.ajax({
                    url: '/get-surat-manzil/' + selectedSuraNo,  // Pastikan URL benar
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Update field berdasarkan data surat
                            $('#edit_no_surat').val(response.data.no_surat);
                            $('#edit_jozz').val(response.data.jozz);
                            $('#edit_start_page').val(response.data.start_page);
                            $('#edit_end_page').val(response.data.end_page);
                        } else {
                            alert('Data surat tidak ditemukan.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mengambil data surat.');
                    }
                });
            }
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