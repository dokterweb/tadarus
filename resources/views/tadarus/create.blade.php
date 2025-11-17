@extends('layouts.app')
@section('content_title','Input Tadarus')

@section('content')
    
    <div class="card card-primary">
        <div class="card-header">
        <h3 class="card-title">Tambah Data</h3>
        </div>
        
        <div class="card-body">
            <form action="{{ route('tadarus.store') }}" method="POST" id="formTadarus">
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
                        <input type="text" name="keteranganabsen" id ="keteranganabsen" class="form-control" placeholder="…">
                    </div>
                </div>
                <div class="row">
                    {{-- SECTION TADARUS — tampil hanya bila status=hadir --}}
                    <div id="sectionTadarus" class="col-md-12 mt-4" style="display:none;">
                        <div class="border rounded p-3">
                            <h6 class="mb-3">Detail Tadarus</h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Surat</label>
                                    <select name="surat_no" id="surat_no" class="form-control">
                                        <option value="">Pilih Surat</option>
                                        @foreach($surat as $s)
                                            <option value="{{ $s->sura_no }}">{{ $s->sura_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">No. Surat</label>
                                    <input type="text" id="no_surat_view" class="form-control" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Juz</label>
                                    <input type="text" id="juz_view" class="form-control" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Mulai Hal</label>
                                    <input type="text" id="start_page_view" class="form-control" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Akhir Hal</label>
                                    <input type="text" id="end_page_view" class="form-control" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Dari Ayat</label>
                                    <input type="number" name="dariayat" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Sampai Ayat</label>
                                    <input type="number" name="sampaiayat" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <select name="keterangantadarus" class="form-control" style="width:100%">
                                            <option value="Jayyid Jayyid" {{ old('keterangan') == 'Jayyid Jayyid' ? 'selected' : '' }}>Jayyid Jayyid</option>
                                            <option value="Jayyid" {{ old('keterangan') == 'Jayyid' ? 'selected' : '' }}>Jayyid</option>
                                            <option value="Maqbul" {{ old('keterangan') == 'Maqbul' ? 'selected' : '' }}>Maqbul</option>
                                        </select>
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
                        <h3 class="card-title">History Tadarus Santri</h3>
                        </div>
                    
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tableHistory" style="width:100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Surat</th>
                                        <th>Dari Ayat</th>
                                        <th>Sampai Ayat</th>
                                        <th>Keterangan</th>
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
                        });
                    }
                }
            });
        });
    });
    
    document.addEventListener('DOMContentLoaded', () => {
    
        // pilih kelompok → load siswa
        const kelompok = document.getElementById('kelompok_id');
        const siswa    = document.getElementById('siswa_id');
    
        kelompok.addEventListener('change', async (e) => {
            siswa.innerHTML = '<option value="">Memuat…</option>';
            siswa.disabled = true;
    
            const val = e.target.value;
            if (!val) {
                siswa.innerHTML = '<option value="">Pilih Kelompok dulu</option>';
                return;
            }
    
            try {
                const res = await fetch(`/api/kelompoks/${val}/siswas`);
                const data = await res.json();
    
                siswa.innerHTML = '<option value="">Pilih Siswa</option>';
                data.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.nama_siswa;
                    siswa.appendChild(opt);
                });
                siswa.disabled = false;
            } catch (err) {
                siswa.innerHTML = '<option value="">Gagal memuat siswa</option>';
            }
        });
    
        // status → toggle section tadarus
        const status = document.getElementById('status');
        const section = document.getElementById('sectionTadarus');
        status.addEventListener('change', (e) => {
            section.style.display = (e.target.value === 'hadir') ? 'block' : 'none';
        });
    
        // ganti surat → isi info no/juz/hal otomatis (opsional)
        const suratNo = document.getElementById('surat_no');
        suratNo?.addEventListener('change', async (e) => {
            const no = e.target.value;
            if (!no) return;
    
            try {
                const res = await fetch(`/api/madina/${no}`);
                const json = await res.json();
                if (json.status === 'success') {
                    document.getElementById('no_surat_view').value    = json.data.no_surat ?? '';
                    document.getElementById('juz_view').value         = json.data.jozz ?? '';
                    document.getElementById('start_page_view').value  = json.data.start_page ?? '';
                    document.getElementById('end_page_view').value    = json.data.end_page ?? '';
                }
            } catch (err) {}
        });
    
    });

    $(document).ready(function() {
        const siswaSelect = $('#siswa_id');
        const historyWrapper = $('#historyWrapper');
        let table;  // DataTables instance

        // Ketika siswa dipilih, ambil histori tadarus
        siswaSelect.on('change', function() {
            const siswaId = $(this).val();
            if (!siswaId) {
                historyWrapper.hide();
                return;
            }

            $.ajax({
                url: `/api/siswas/${siswaId}/tadarus`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Format tanggal dan keterangan
                    data.forEach(row => {
                        row.tgl_tadarusnya = dayjs(row.tgl_tadarusnya).format('DD-MM-YYYY');
                        row.keterangan = row.keterangan ?? '-';
                    });

                    // Jika DataTables belum diinisialisasi, inisialisasi sekarang
                    if (!$.fn.dataTable.isDataTable('#tableHistory')) {
                        table = $('#tableHistory').DataTable({
                            data: data,  // Data langsung dimasukkan
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: false,
                            pageLength: 5,
                            language: {
                                emptyTable: "Belum ada histori tadarus.",
                                search: "Cari:",
                                lengthMenu: "Tampilkan _MENU_ baris",
                                paginate: { next: "›", previous: "‹" }
                            },
                            columns: [
                                { data: 'tgl_tadarusnya' },
                                { data: 'sura_name' },
                                { data: 'dariayat' },
                                { data: 'sampaiayat' },
                                { data: 'keterangan' },
                                {
                                    data: 'id', 
                                    render: function(data, type, row) {
                                        // Tombol Edit dan Delete
                                        return `
                                            <a href="/tadaruses/${data}/edit" class="btn btn-warning btn-sm">Edit</a>
                                            <button class="btn btn-danger btn-sm btn-delete" data-id="${data}">Delete</button>
                                        `;
                                    }
                                }
                            ]
                        });
                    } else {
                        // Kalau sudah ada instance DataTables, update data-nya
                        table.clear().rows.add(data).draw();
                    }

                    historyWrapper.show();
                },
                error: function() {
                    table.clear().draw();  // Jika ada error, kosongkan tabel
                    historyWrapper.show();
                    alert('Gagal memuat histori tadarus.');
                }
            });
        });

        // Hapus data
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');  // Ambil ID dari tombol delete

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/tadaruses/${id}/destroy`,
                        type: 'DELETE',
                        success: function(response) {
                            Swal.fire('Deleted!', 'Data berhasil dihapus.', 'success');
                            table.ajax.reload();  // Reload tabel DataTables
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus data.', 'error');
                        }
                    });
                }
            });
        });
    });



</script>


@endsection