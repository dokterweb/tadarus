<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link text-center">
      {{-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
      <span class="brand-text font-weight-light">{{env('APP_NAME')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          {{-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> --}}
        </div>
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link {{request()->routeIs('dashboard')?'active':''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('tadaruses')}}" class="nav-link {{request()->routeIs('tadaruses')?'active':''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Data Tadarus</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('tadaruses.create')}}" class="nav-link {{request()->routeIs('tadaruses.create')?'active':''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Input Tadarus</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('iqros')}}" class="nav-link {{request()->routeIs('iqros')?'active':''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Data Iqra</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('iqros.create')}}" class="nav-link {{request()->routeIs('iqros.create')?'active':''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Input Iqra</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('absensiustadz.index')}}" class="nav-link {{request()->routeIs('absensiustadz.index')?'active':''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Absensi Ustadz</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Master<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('adminsiswas')}}" class="nav-link {{request()->routeIs('adminsiswas')?'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Admin Siswa</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('siswas')}}" class="nav-link {{request()->routeIs('siswas')?'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Siswa</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('ustadzs')}}" class="nav-link {{request()->routeIs('ustadzs')?'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ustadz</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('kelasnyas')}}" class="nav-link {{request()->routeIs('kelasnyas')?'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kelas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('kelompoks')}}" class="nav-link {{request()->routeIs('kelompoks')?'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kelompok</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('jenisiqros')}}" class="nav-link {{request()->routeIs('jenisiqros')?'active':''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Jenis Iqro</p>
                </a>
              </li>
            </ul>
          </li> 

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>