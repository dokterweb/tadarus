@extends('layouts.app')
@section('content_title','Dashboard')

@section('content')
    <div class="card">
        <div class="card-body">
            Welcome to Tahfizh <strong class="capitilize">{{auth()->user()->name}}</strong>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-6">
          <!-- small card -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{$totalSiswa}}</h3>
    
              <p>Total Siswa</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{route('siswas')}}" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
          <!-- small card -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{$totalUstadz}}</h3>
    
              <p>Total Ustadz</p>
            </div>
            <div class="icon">
                <i class="fas fa-house-user"></i>
            </div>
            <a href="{{route('ustadzs')}}" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
          <!-- small card -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{$totalKelas}}</h3>
              
              <p>Total Kelas</p>
            </div>
            <div class="icon">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="{{route('kelasnyas')}}" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-md-6">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Tadarus terbaru</h3>
              </div>
              <div class="card-body">
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                            <th>Tanggal</th>
                            <th>Nama Santri</th>
                            <th>Surat</th>
                            <th>Dari Ayat</th>
                            <th>Sampai Ayat</th>
                          </tr>
                      </thead>
                      <tbody>
                        @forelse($latestTadarus as $t)
                          <tr>
                              <td>{{ \Carbon\Carbon::parse($t->tgl_tadarusnya)->format('d-m-Y') }}</td>
                              <td>{{ $t->siswa->nama_siswa }}</td>
                              <td>{{ $t->surat->sura_name ?? '-' }}</td>
                              {{-- <td>{{ $t->surat_no }}</td> --}}
                              <td>{{ $t->dariayat }}</td>
                              <td>{{ $t->sampaiayat }}</td>
                          </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Belum ada data</td></tr>
                        @endforelse
                      </tbody>
                  </table>
              </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Iqra terbaru</h3>
              </div>
              <div class="card-body">
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                            <th>Tanggal</th>
                            <th>Nama Santri</th>
                            <th>Jenis Iqro</th>
                            <th>Hal. Awal</th>
                            <th>Hal. Akhir</th>
                          </tr>
                      </thead>
                      <tbody>
                        @forelse($latestIqro as $i)
                          <tr>
                              <td>{{ \Carbon\Carbon::parse($i->tgl_iqro)->format('d-m-Y') }}</td>
                              <td>{{ $i->siswa->nama_siswa }}</td>
                              <td>{{ $i->jenisiqro->nama_iqro }}</td>
                              <td>{{ $i->hal_awal }}</td>
                              <td>{{ $i->hal_akhir }}</td>
                          </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Belum ada data</td></tr>
                        @endforelse
                      </tbody>
                  </table>
              </div>
            </div>
        </div>
    </div>
@endsection