@extends('layouts.app')
@section('content_title','Siswa')

@section('content')
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('absensis.store') }}" method="POST">
                @csrf  
                <!-- Dropdown Pilih Kelas -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kelas">Pilih Kelas</label>
                        <select name="kelas_id" id="kelas" class="form-control" onchange="getSiswa()">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $kelasItem)
                                <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tgl_absen">Tanggal Absensi</label>
                        <input type="date" name="tgl_absen" id="tgl_absen" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <hr>
                    <!-- Daftar Siswa yang Muncul Berdasarkan Kelas -->
                    <div id="siswa_list" class="form-group" style="display:none;">
                        <label for="siswa">Data Siswa</label>
                        <hr>
                        <div id="siswa_container">
                            <!-- Siswa akan ditambahkan secara dinamis dengan jQuery -->
                        </div>
                    </div>
            
                    <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                </div>
            </form>
            
        </div>
    </div>
    
@endsection


<!-- SweetAlert2 Script -->
@section('scripts')
<script>
    $(document).ready(function() {
        // Fungsi untuk mendapatkan siswa berdasarkan kelas yang dipilih
        function getSiswa() {
            var kelas_id = $('#kelas').val(); // Menggunakan jQuery untuk mengambil nilai kelas_id

            if (kelas_id) {
                // Jika kelas dipilih, lakukan AJAX untuk mengambil siswa
                $.ajax({
                    url: '/get-siswa/' + kelas_id,  // Kirim kelas_id sebagai parameter
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Kosongkan container siswa terlebih dahulu
                        $('#siswa_container').empty();

                        // Menambahkan daftar siswa ke container
                        var siswaList = '';
                        data.siswa.forEach(function(siswa) {
                            // Mengakses nama siswa melalui relasi user
                            var siswaName = siswa.user.name;

                            siswaList += `
                                <div class="form-group">
                                    <label for="status_${siswa.id}">${siswaName}</label>
                                    <select class="form-control" name="status[${siswa.id}]" id="status_${siswa.id}">
                                        <option value="hadir">Hadir</option>
                                        <option value="absen">Absen</option>
                                        <option value="izin">Izin</option>
                                    </select>
                                </div>
                            `;
                        });

                        // Masukkan siswaList ke dalam container siswa
                        $('#siswa_container').html(siswaList);
                        $('#siswa_list').show();  // Tampilkan container siswa
                    },
                    error: function() {
                        alert("Terjadi kesalahan saat mengambil data siswa.");
                    }
                });
            } else {
                $('#siswa_list').hide();  // Sembunyikan daftar siswa jika kelas belum dipilih
            }
        }

        // Mengaktifkan fungsi getSiswa ketika dropdown kelas berubah
        $('#kelas').change(function() {
            getSiswa();  // Memanggil fungsi getSiswa saat kelas dipilih
        });

       // Form submit event
       $('form').submit(function(event) {
            event.preventDefault();  // Mencegah form submit default

            var formData = $(this).serialize();  // Ambil data form

            // Cek terlebih dahulu apakah absensi sudah ada untuk kelas dan tanggal
            var kelas_id = $('#kelas').val();
            var tgl_absen = $('#tgl_absen').val();

            $.ajax({
                url: '/check-absensi',  // URL untuk memeriksa apakah absensi sudah ada
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(), // Kirimkan token CSRF
                    kelas_id: kelas_id,
                    tgl_absen: tgl_absen
                },
                success: function(response) {
                    if (response.exists) {
                        // Jika sudah ada absensi, tampilkan alert
                        alert('Absensi sudah ada di tanggal tersebut. Semua siswa sudah absen.');
                    } else {
                        // Jika absensi belum ada, lanjutkan dengan pengiriman form
                        $.ajax({
                            url: "{{ route('absensis.store') }}",  // URL form
                            method: 'POST',  // Pastikan menggunakan method POST
                            data: formData,
                            success: function(response) {
                                // Jika berhasil, redirect atau tampilkan notifikasi
                                alert('Absensi berhasil disimpan.');
                                window.location.href = '{{ route("absensis") }}';  // Redirect ke halaman absensi
                            },
                            error: function(xhr) {
                                // Jika ada error, tampilkan pesan error
                                alert('Terjadi kesalahan. Silakan coba lagi.');
                            }
                        });
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat memeriksa absensi.');
                }
            });
        });
    });
</script>





@endsection