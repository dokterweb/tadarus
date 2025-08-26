@extends('layouts.app')
@section('content_title','History Sabqi Siswa')

@section('content')
    
    <div class="card">
        <div class="card-body">
            <table id="paketTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tgl Sabqi</th>
                        <th>Nama Surat</th>
                        <th>Dari dan ke Ayat</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sabqiHistories as $index => $history)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($history->tgl_sabqi)->format('d F Y') }}</td>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">No Data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
@endsection


<!-- SweetAlert2 Script -->
@section('scripts')
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