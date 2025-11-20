@extends('layouts.app')
@section('content_title','Input Iqro')

@section('content')
    
    <div class="card card-primary">
        <div class="card-header">
        <h3 class="card-title">Tambah Data</h3>
        </div>
        
        <div class="card-body">
            <form action="{{ route('iqros.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label >Tanggal</label>    
                        <input type="date" name="tgl" id="tgl" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label >Nama kelompok</label>
                            <select name="kelompok_id" id="kelompok_id" class="form-control" required>
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompoks as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelompok }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="form-control" required disabled>
                                <option value="">Pilih Kelompok dulu</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Absensi Siswa</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <option value="hadir">Hadir</option>
                                <option value="ghoib">Ghoib</option>
                                <option value="izin">Izin</option>
                                <option value="tugas">Tugas</option>
                                <option value="sakit">Sakit</option>
                                <option value="pulang">Pulang</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Keterangan (opsional)</label>
                        <input type="text" name="keterangan" id ="keterangan" class="form-control" placeholder="…">
                    </div>
                </div>
                <div class="row">
                    {{-- SECTION TADARUS — tampil hanya bila status=hadir --}}
                    <div id="iqroSection" class="col-md-12 mt-4" style="display:none;">
                        <div class="border border-primary rounded p-3">
                            <h6 class="mb-3">Detail Iqra</h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Jenis Pembelajaran</label>
                                    <select name="jenisiqro_id" id="jenisiqro_id" class="form-control">
                                        <option value="">Pilih Jenis IQRO</option>
                                        @foreach($jenisiqros as $jenisiqro)
                                            <option value="{{ $jenisiqro->id }}">{{ $jenisiqro->nama_iqro }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <select name="nilaibacaan" class="form-control" style="width:100%">
                                            <option value="Jayyid Jayyid" {{ old('nilaibacaan') == 'Jayyid Jayyid' ? 'selected' : '' }}>Jayyid Jayyid</option>
                                            <option value="Jayyid" {{ old('nilaibacaan') == 'Jayyid' ? 'selected' : '' }}>Jayyid</option>
                                            <option value="Maqbul" {{ old('nilaibacaan') == 'Maqbul' ? 'selected' : '' }}>Maqbul</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Halaman Awal</label>
                                        <input type="number" name="hal_awal" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Halaman Akhir</label>
                                        <input type="number" name="hal_akhir" class="form-control" required>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
            <div class="row mt-4" id="historyWrapper" style="display:none;">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                        <h3 class="card-title">History Iqro Santri</h3>
                        </div>
                    
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tableHistory" style="width:100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jenis Iqra</th>
                                        <th>Hal Awal</th>
                                        <th>Hal Akhir</th>
                                        <th>Nilai Bacaan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
@endsection

@section('scripts')

{{-- Day.js untuk format tanggal --}}
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>

<script>
    $(document).ready(function () {
        $('#tgl').on('change', function () {
            let tanggal = $(this).val();
            if (!tanggal) return;

            $.ajax({
                url: "{{ url('/cek-hari-libur') }}/" + tanggal,
                method: "GET",
                success: function (res) {
                    if (res.status === 'libur') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tanggal Libur!',
                            text: `Tanggal ini adalah: ${res.nama_libur} (${res.tipe})`,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // kosongkan tanggal supaya form nggak bisa di-submit
                            $('#tgl').val('');
                        });
                    }
                }
            });
        });
    });

  $(document).ready(function() {
    // Ketika kelompok dipilih, tampilkan siswa dari kelompok tersebut
    $('#kelompok_id').on('change', function() {
        var kelompokId = $(this).val();
        if (kelompokId) {
            $.ajax({
                url: '/iqros/get-siswa-by-kelompok/' + kelompokId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#siswa_id').prop('disabled', false);
                    $('#siswa_id').empty();
                    $('#siswa_id').append('<option value="">Pilih Siswa</option>');
                    $.each(data, function(key, value) {
                        $('#siswa_id').append('<option value="'+value.id+'">'+value.nama_siswa+'</option>');
                    });
                }
            });
        } else {
            $('#siswa_id').prop('disabled', true);
        }
    });

    // Ketika status absen berubah, tampilkan input IQRO jika status = 'hadir'
    $('#status').on('change', function() {
        if ($(this).val() == 'hadir') {
            $('#iqroSection').show();
        } else {
            $('#iqroSection').hide();
        }
    });
});


$(function () {

let table = null;

// inisialisasi DataTable sekali saja saat pertama pakai
function initTable() {
    if ($.fn.dataTable.isDataTable('#tableHistory')) {
        table = $('#tableHistory').DataTable();
        return;
    }

    table = $('#tableHistory').DataTable({
        data: [],
        paging: true,
        searching: false,
        ordering: false,
        info: false,
        pageLength: 5,
        language: {
            emptyTable: "Belum ada history Iqro.",
            lengthMenu: "Tampilkan _MENU_ baris",
            paginate: { next: "›", previous: "‹" }
        },
        columns: [
            { data: 'tgl_iqro_formatted' },  // kita bikin di JS
            { data: 'nama_iqro' },           // dari join Jenisiqro
            { data: 'hal_awal' },
            { data: 'hal_akhir' },
            { data: 'nilaibacaan' },
            {
                data: 'id',
                render: function (data, type, row) {
                    return `
                        <a href="/iqros/${data}/edit" class="btn btn-sm btn-warning">Edit</a>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="${data}">
                            Delete
                        </button>
                    `;
                }
            }
        ]
    });
}

// ketika siswa dipilih
$('#siswa_id').on('change', function () {
    const siswaId = $(this).val();

    // kalau kosong: sembunyikan wrapper & clear tabel
    if (!siswaId) {
        if (table) {
            table.clear().draw();
        }
        $('#historyWrapper').hide();
        return;
    }

    // panggil API untuk ambil history iqro
    $.ajax({
        url: `/api/siswas/${siswaId}/iqrohistories`,
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            const rows = Array.isArray(res) ? res : (res.data || []);

            // format tanggal
            rows.forEach(r => {
                // kalau pakai dayjs
                if (window.dayjs) {
                    r.tgl_iqro_formatted = dayjs(r.tgl_iqro).format('DD-MM-YYYY');
                } else {
                    r.tgl_iqro_formatted = r.tgl_iqro; // fallback
                }
            });

            initTable();
            table.clear().rows.add(rows).draw();
            $('#historyWrapper').show();
        },
        error: function () {
            initTable();
            table.clear().draw();
            $('#historyWrapper').show();
            alert('Gagal memuat history Iqro santri.');
        }
    });
});

});
</script>


@endsection