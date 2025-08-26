@extends('layouts.app')
@section('content_title','History Manzil Siswa')

@section('content')
    
    <div class="card">
        <div class="card-body">
            <table id="paketTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tgl Manzil</th>
                        <th>Nama Surat</th>
                        <th>Dari dan ke Ayat</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($manzilHistories as $index => $history)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($history->tgl_manzil)->format('d F Y') }}</td>
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
    </script>
@endsection