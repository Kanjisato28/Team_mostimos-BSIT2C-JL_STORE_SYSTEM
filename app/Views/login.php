<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>JL Store Sales and Inventory System | Log in</title>

  <!-- Fonts & Styles -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">

  <style>
    body {
      background: linear-gradient(135deg, #4e73df, #1cc88a);
      font-family: 'Source Sans Pro', sans-serif;
    }

    .login-box {
      margin-top: 5%;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .login-card-body {
      padding: 2rem;
    }

    .login-title {
      font-weight: 600;
      font-size: 22px;
      text-align: center;
      margin-bottom: 10px;
    }

    .login-subtitle {
      text-align: center;
      color: #666;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .form-control {
      border-radius: 8px;
    }

    .btn-primary {
      border-radius: 8px;
      font-weight: 600;
    }

    .alert {
      border-radius: 8px;
      font-size: 14px;
    }

    .icheck-primary label {
      font-size: 14px;
    }
  </style>
</head>

<body class="hold-transition login-page">
<div class="login-box">
  <div class="card">
    <div class="card-body login-card-body">

      <div class="login-title">JL Store Sales and Inventory System</div>
      <div class="login-subtitle">Sign in to continue</div>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('/auth') ?>" method="post">
        <?= csrf_field() ?>

        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-6">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Remember Me</label>
            </div>
          </div>
          <div class="col-6 text-right">
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-sign-in-alt"></i> Login
            </button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Scripts -->
<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js') ?>"></script>

</body>
</html>