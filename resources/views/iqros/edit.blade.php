@extends('layouts.app')
@section('content_title','Edit Iqro')

@section('content')
    
    <div class="card card-primary">
        <div class="card-header">
        <h3 class="card-title">Edit Data</h3>
        </div>
        
        <div class="card-body">
            <form action="{{ route('iqros.update', $iqro->id) }}" method="POST" id="formTadarus">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4">
                        <label >Tanggal</label>    
                        <input type="date" name="tgl" id="tgl" class="form-control" value="{{ $iqro->tgl_iqro }}" required>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label >Nama kelompok</label>
                            <select name="kelompok_id" id="kelompok_id" class="form-control" required>
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompoks as $kelompok)
                                    <option value="{{ $kelompok->id }}" {{ $kelompok->id == $iqro->siswa->kelompok_id ? 'selected' : '' }}>
                                        {{ $kelompok->nama_kelompok }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="form-control" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($iqro->siswa->kelompok->siswas as $siswa)
                                    <option value="{{ $siswa->id }}" {{ $siswa->id == $iqro->siswa_id ? 'selected' : '' }}>
                                        {{ $siswa->nama_siswa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Absensi Siswa</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="hadir" {{ $iqro->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="ghoib" {{ $iqro->status == 'ghoib' ? 'selected' : '' }}>Ghoib</option>
                                <option value="izin" {{ $iqro->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="tugas" {{ $iqro->status == 'tugas' ? 'selected' : '' }}>Tugas</option>
                                <option value="sakit" {{ $iqro->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="pulang" {{ $iqro->status == 'pulang' ? 'selected' : '' }}>Pulang</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Keterangan (opsional)</label>
                        <input type="text" name="keterangan" id ="keterangan" class="form-control" value="{{ $absensi->keterangan }}">
                    </div>
                </div>
                <div class="row">
                    {{-- SECTION TADARUS — tampil hanya bila status=hadir --}}
                    <div id="iqroSection" style="display:none;">
                        <div class="border rounded p-3">
                            <h6 class="mb-3">Detail Iqra</h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Jenis Pembelajaran</label>
                                    <select name="jenisiqro_id" id="jenisiqro_id" class="form-control">
                                        @foreach($jenisiqros as $jenisiqro)
                                            <option value="{{ $jenisiqro->id }}" {{ $jenisiqro->id == $iqro->jenisiqro_id ? 'selected' : '' }}>
                                                {{ $jenisiqro->nama_iqro }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <select name="nilaibacaan" class="form-control" style="width:100%">
                                            <option value="Jayyid Jayyid" {{ $iqro->nilaibacaan == 'Jayyid Jayyid' ? 'selected' : '' }}>Jayyid Jayyid</option>
                                            <option value="Jayyid" {{ $iqro->nilaibacaan == 'Jayyid' ? 'selected' : '' }}>Jayyid</option>
                                            <option value="Maqbul" {{ $iqro->nilaibacaan == 'Maqbul' ? 'selected' : '' }}>Maqbul</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Halaman Awal</label>
                                        <input type="number" name="hal_awal" class="form-control" value="{{ $iqro->hal_awal }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Halaman Akhir</label>
                                        <input type="number" name="hal_akhir" class="form-control" value="{{ $iqro->hal_akhir }}" required>
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

    $(function () {
        function toggleIqroSection() {
            const status = $('#status').val();
            if (status === 'hadir') {
                $('#iqroSection').slideDown();   // atau .show()
            } else {
                $('#iqroSection').slideUp();     // atau .hide()
            }
        }
    
        // 1. Jalanin sekali pas halaman pertama kali dibuka
        toggleIqroSection();
    
        // 2. Jalanin lagi tiap kali status diubah
        $('#status').on('change', toggleIqroSection);
    });
    </script>


@endsection