@extends('layouts.app')
@section('content_title','History Iqro Siswa')

@section('content')
    
    <div class="card">
        <div class="card-body">
            <table id="paketTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tgl Iqro</th>
                        <th>Jilid Iqro</th>
                        <th>Halaman</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($iqroHistories as $index => $history)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($history->tgl_iqro)->format('d F Y') }}</td>
                        <td>{{ $history->iqro_jilid}}</td>
                        <td>{{ $history->halaman}}</td>
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