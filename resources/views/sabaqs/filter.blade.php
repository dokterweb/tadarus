@extends('layouts.app')
@section('content_title','Filter Sabaq')

@section('content')
    
    <div class="card">
        <div class="card-body">
           <!-- Form Filter Tanggal -->
            <form method="GET" action="#" class="form-inline">
                @csrf
                <div class="form-group">
                    <label for="start_date">Dari Tanggal:</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" required>
                </div>
                <div class="form-group ml-2">
                    <label for="end_date">Sampai Tanggal:</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                </div>
                <button type="submit" class="btn btn-primary ml-2">Cari</button>
            </form>

            <div class="row">
               <!-- Tabel untuk Menampilkan Hasil Filter -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Muroja'ah</th>
                            <th>Nama Surat</th>
                            <th>Ayat</th>
                            <th>Ustadz/Ustadzah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sabaqs as $index => $history)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($history->tgl_sabaq)->format('d F Y') }}</td>
                                <td>{{ $history->surat->sura_name }}</td>
                                <td>{{ $history->dariayat }} - {{ $history->sampaiayat }}</td>
                                <td>{{ $history->sabqi->ustadz->user->name }}</td>
                                <td>{{ $history->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>
    
@endsection


<!-- SweetAlert2 Script -->
@section('scripts')


@endsection