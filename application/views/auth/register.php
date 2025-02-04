<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>APLIKASI | WILAYAH</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/garuda.png'); ?>">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- iCheck Bootstrap (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    <!-- AdminLTE Theme Style (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>APP</b>WIL</a>
            </div>
            <div class="mt-3 text-center">
                <img src="<?= base_url('assets/img/garuda.png') ?>" alt="Logo STIKOM" style="max-width: 100px; max-height: 100px;">
            </div>
            <div class="card-body">
                <p class="login-box-msg">Daftar untuk Memulai</p>

                <!-- Form Register -->
                <form action="<?= site_url('register/register_aksi') ?>" method="post">
                    <!-- Menambahkan Token CSRF -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" value="<?= set_value('username') ?>" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="first_name" class="form-control" placeholder="Nama Depan" value="<?= set_value('first_name') ?>" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="last_name" class="form-control" placeholder="Nama Belakang" value="<?= set_value('last_name') ?>" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-group mb-3">
                        <select name="id_role" id="id_role" class="form-control" required>
                            <option value="">Pilih Role</option>
                            <option value="1" <?php echo (isset($user) && $user->id_role == 1) ? 'selected' : ''; ?>>Superadmin</option>
                            <option value="2" <?php echo (isset($user) && $user->id_role == 2) ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
 
                    <div class="input-group mb-3">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required/>
                    <div class="input-group-append">
                            <button type="button" id="togglePassword" class="btn btn-outline-secondary" aria-label="Toggle password visibility">👁️</button>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Konfirmasi Password" required/>
                        <div class="input-group-append">
                            <button type="button" id="toggleConfirmPassword" class="btn btn-outline-secondary" aria-label="Toggle password visibility">👁️</button>
                        </div>
                    </div>

                    <p id="passwordMismatch" style="color: red; display: none;">Kata sandi tidak sinkron!</p>


                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="<?= site_url('login') ?>">Sudah punya akun? Login</a>
                </p>
            </div>
        </div>
    </div>  

    <!-- jQuery (CDN) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App (CDN) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
    <script>
  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirm_password');
  const togglePassword = document.getElementById('togglePassword');
  const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
  const passwordMismatch = document.getElementById('passwordMismatch');

  // Toggle visibility for Password
  togglePassword.addEventListener('click', () => {
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    togglePassword.textContent = type === 'password' ? '👁️' : '🔒';
  });

  // Toggle visibility for Confirm Password
  toggleConfirmPassword.addEventListener('click', () => {
    const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
    confirmPasswordInput.type = type;
    toggleConfirmPassword.textContent = type === 'password' ? '👁️' : '🔒';
  });

  // Validate password and confirm password match
  confirmPasswordInput.addEventListener('input', () => {
    if (confirmPasswordInput.value !== passwordInput.value) {
      passwordMismatch.style.display = 'block';
    } else {
      passwordMismatch.style.display = 'none';
    }
  });
</script>
</body>

</html>
