@extends('layouts.app')
@section('content_title','Edit Tadarus')

@section('content')
    
    <div class="card card-primary">
        <div class="card-header">
        <h3 class="card-title">Edit Data</h3>
        </div>
        
        <div class="card-body">
            <form action="{{ route('tadaruses.update', $tadarus->id) }}" method="POST" id="formTadarus">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4">
                        <label >Tanggal</label>    
                        <input type="date" name="tgl" class="form-control" value="{{ $tadarus->tgl_tadarusnya }}" required>
                        <input type="hidden" name="id" value="{{ $tadarus->id }}">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label >Nama kelompok</label>
                            <select name="kelompok_id" id="kelompok_id" class="form-control" required>
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompoks as $kelompok)
                                    <option value="{{ $kelompok->id }}" {{ $kelompok->id == $tadarus->siswa->kelompok_id ? 'selected' : '' }}>{{ $kelompok->nama_kelompok }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="form-control" required>
                                <option value="{{ $tadarus->siswa_id }}">{{ $tadarus->siswa->nama_siswa }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Absensi Siswa</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="hadir" {{ $tadarus->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="ghoib" {{ $tadarus->status == 'ghoib' ? 'selected' : '' }}>Ghoib</option>
                                <option value="izin" {{ $tadarus->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="tugas" {{ $tadarus->status == 'tugas' ? 'selected' : '' }}>Tugas</option>
                                <option value="sakit" {{ $tadarus->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="pulang" {{ $tadarus->status == 'pulang' ? 'selected' : '' }}>Pulang</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Keterangan (opsional)</label>
                        <input type="text" name="keteranganabsen" id ="keteranganabsen" class="form-control" value="{{ $absensi->keterangan ?? '' }}">
                    </div>
                </div>
                <div class="row">
                    <div id="tadarusSection" class="mt-4" style="display: {{ $tadarus->status == 'hadir' ? 'block' : 'none' }};">
                        <div class="border rounded p-3">
                            <h5>Detail Tadarus</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="surat_name" class="form-label">Nama Surat</label>
                                        <select name="surat_no" id="surat_no" class="form-control" required>
                                            <option value="">Pilih Surat</option>
                                            @foreach($surat as $s)
                                                <option value="{{ $s->sura_no }}" {{ $s->sura_no == $tadarus->surat_no ? 'selected' : '' }}>{{ $s->sura_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">No. Surat</label>
                                        <input type="text" id="no_surat" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Juz</label>
                                        <input type="text" id="jozz" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="start_page" class="form-label">Mulai Halaman</label>
                                        <input type="text" id="start_page" class="form-control" readonly>
                                    </div>
                                </div>
                
                                <div class="col-md-3">
                                    <label for="end_page" class="form-label">Akhir Halaman</label>
                                    <input type="text" id="end_page" class="form-control" readonly>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dariayat" class="form-label">Dari Ayat</label>
                                        <input type="number" id="dariayat" name="dariayat" value="{{ $tadarus->dariayat }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sampaiayat" class="form-label">Sampai Ayat</label>
                                        <input type="number" id="sampaiayat" name="sampaiayat" value="{{ $tadarus->sampaiayat }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <select name="keterangantadarus" id="keterangantadarus" class="form-control" required>
                                            <option value="Jayyid Jayyid" {{ $tadarus->keterangan == 'Jayyid Jayyid' ? 'selected' : '' }}>Jayyid Jayyid</option>
                                            <option value="Jayyid" {{ $tadarus->keterangan == 'Jayyid' ? 'selected' : '' }}>Jayyid</option>
                                            <option value="Maqbul" {{ $tadarus->keterangan == 'Maqbul' ? 'selected' : '' }}>Maqbul</option>
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
        </div>
    </div>
 
@endsection

@section('scripts')

<script>
    $(document).ready(function () {
        const statusSelect = $('#status');
        const tadarusSection = $('#tadarusSection');

        // Setiap kali status berubah
        statusSelect.on('change', function () {
            const status = $(this).val();
            
            // Jika status = HADIR, tampilkan form tadarus
            if (status === 'hadir') {
                tadarusSection.show();
            } else {
                tadarusSection.hide();
            }
        });

        // Inisialisasi tampilan sesuai status yang ada di DB
        const currentStatus = statusSelect.val();
        if (currentStatus === 'hadir') {
            tadarusSection.show();
        } else {
            tadarusSection.hide();
        }
    });

    $('#editForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize(); // Ambil data form

        $.ajax({
            url: `/tadaruses/${$('#edit_id').val()}/update`, // Ambil ID dari hidden input
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire('Success!', 'Data berhasil diperbarui!', 'success');
                    location.reload();  // Refresh halaman
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function (err) {
                Swal.fire('Error!', 'Gagal mengupdate data.', 'error');
            }
        });
    });

    $(document).ready(function () {
    const suratSelect = $('#surat_no');

    function loadSuratDetails(sura_no) {
        if (!sura_no) {
            $('#no_surat').val('');
            $('#jozz').val('');
            $('#start_page').val('');
            $('#end_page').val('');
            return;
        }

        $.ajax({
            url: `/api/madina/${sura_no}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const d = response.data;
                    $('#no_surat').val(d.no_surat);
                    $('#jozz').val(d.jozz);
                    $('#start_page').val(d.start_page);
                    $('#end_page').val(d.end_page);
                }
            },
            error: function () {
                console.error('Gagal mengambil data surat');
            }
        });
    }

    // ⬅️ PENTING: saat halaman edit pertama kali dibuka
    const initialSuraNo = suratSelect.val();
    if (initialSuraNo) {
        loadSuratDetails(initialSuraNo);
    }

    // Saat user ganti surat secara manual
    suratSelect.on('change', function () {
        const sura_no = $(this).val();
        loadSuratDetails(sura_no);
    });
});

    $(document).ready(function() {
    // Ketika surat dipilih
    $('#surat_no').on('change', function() {
        const suratNo = $(this).val();
        
        if (suratNo) {
            // Ambil data surat dari API
            $.ajax({
                url: `/api/madina/${suratNo}`,  // Endpoint API untuk mengambil detail surat
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Update form dengan data surat
                        $('#no_surat').val(response.data.no_surat);
                        $('#jozz').val(response.data.jozz);
                        $('#start_page').val(response.data.start_page);
                        $('#end_page').val(response.data.end_page);
                        $('#suratDetails').show(); // Tampilkan detail surat
                    }
                },
                error: function() {
                    // Reset input jika terjadi error
                    $('#suratDetails').hide();
                    alert('Gagal mengambil data surat.');
                }
            });
        } else {
            // Reset jika tidak ada surat yang dipilih
            $('#suratDetails').hide();
        }
    });
});

</script>
@endsection