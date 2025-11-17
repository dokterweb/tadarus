
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Page</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('adminlte')}}/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('adminlte')}}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('adminlte')}}/dist/css/adminlte.min.css">
  <style>
    .login-bg-custom {
        background-color: #ccfbf1 !important;
    }
  
    .login-logo img {
        max-width: 90px;            /* sesuaikan ukuran logo */
        margin-bottom: 10px;
    }
  
    .login-logo h2,
    .login-logo h3 {
        margin: 0;
        font-weight: 600;
    }
  
    .login-logo h2 {
        font-size: 20px;
        letter-spacing: 2px;
    }
  
    .login-logo h3 {
        font-size: 18px;
    }
  
    .login-logo {
        margin-bottom: 25px;
    }
  
    .login-box .card {
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="hold-transition login-page login-bg-custom">
<div class="login-box">
  <div class="login-logo">
    <img src="{{ asset('images/logo.jpg') }}" alt="Logo Pesantren">
    <h2>SANTRI TILAWAH</h2>
    <h3>PESANTREN DARUNNAJAH 2 CIPINING</h3>
  </div>
  <div class="card card-outline card-primary">
    
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
      <form action="{{route('login')}}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('adminlte')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('adminlte')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('adminlte')}}/dist/js/adminlte.min.js"></script>
</body>
</html>
