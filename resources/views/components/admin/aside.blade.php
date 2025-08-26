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
          {{-- <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Starter Pages
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active Page</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inactive Page</p>
                </a>
              </li>
            </ul>
          </li> --}}
          <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link {{request()->routeIs('dashboard')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('siswas.index')}}" class="nav-link {{request()->routeIs('siswas.index')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Siswa</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('ustadzs.index')}}" class="nav-link {{request()->routeIs('ustadzs.index')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Ustadz</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('kelasnyas')}}" class="nav-link {{request()->routeIs('kelasnyas')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Kelas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('sabaqs')}}" class="nav-link {{request()->routeIs('sabaqs')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Sabaq</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('sabqis')}}" class="nav-link {{request()->routeIs('sabqis')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Sabqi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('manzils')}}" class="nav-link {{request()->routeIs('manzils')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Manzil</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('iqros')}}" class="nav-link {{request()->routeIs('iqros')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Iqro</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('sabaqs.sabaqsiswa')}}" class="nav-link {{request()->routeIs('sabaqs.sabaqsiswa')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Sabaq</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('sabqis.sabqisiswa')}}" class="nav-link {{request()->routeIs('sabqis.sabqisiswa')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Sabqi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('manzils.manzilsiswa')}}" class="nav-link {{request()->routeIs('manzils.manzilsiswa')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Manzil</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('iqros.iqrosiswa')}}" class="nav-link {{request()->routeIs('iqros.iqrosiswa')?'active':''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>Iqro</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>