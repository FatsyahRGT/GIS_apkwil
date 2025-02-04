<div class="content-wrapper">
    <section class="content">
        <h4><strong>EDIT DATA USER</strong></h4>

        <?php echo form_open_multipart('UserController/update'); ?>

        <input type="hidden" name="id" value="<?php echo $user->id; ?>">

        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $user->first_name . ' ' . $user->last_name; ?>" required readonly>
        </div>

        <div class="form-group">
            <label for="first_name">Nama Depan</label>
            <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo $user->first_name; ?>" >
        </div>

        <div class="form-group">
            <label for="last_name">Nama Belakang</label>
            <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo $user->last_name; ?>" >
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username" value="<?php echo $user->username; ?>" >
        </div>

        <div class="form-group">
              <label for="password">Password</label>
              <div style="display: flex; align-items: center;">
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password"  style="flex: 1;">
                <button type="button" id="togglePassword" style="margin-left: 8px;">👁️</button>
              </div>
        </div>

        <div class="form-group">
              <label for="confirm_password">Konfirmasi Password</label>
              <div style="display: flex; align-items: center;">
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Konfirmasi Password"  style="flex: 1;">
                <button type="button" id="toggleConfirmPassword" style="margin-left: 8px;">👁️</button>
              </div>
        </div>

        <p id="passwordMismatch" style="color: red; display: none;">Kata sandi tidak cocok!</p>

        <div class="form-group">
            <label for="id_role">Role</label>
            <select name="id_role" id="id_role" class="form-control" required>
                <option value="">Pilih Role</option>
                <option value="1" <?php echo ($user->id_role == 1) ? 'selected' : ''; ?>>Superadmin</option>
                <option value="2" <?php echo ($user->id_role == 2) ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        

        <label for="profile_photo">Foto</label>
        <div class="form-group">
            <?php if (!empty($user->profile_photo) && file_exists('./assets/foto/' . $user->profile_photo)): ?>
                <!-- Menampilkan foto profil jika ada -->
                <img src="<?= base_url('UserController/tampilkan_foto/' . $user->profile_photo); ?>" 
                     alt="Foto User" 
                     style="max-width: 200px; max-height: 200px; margin-top: 10px;">
                <p>Foto saat ini</p>
            <?php else: ?>
                <!-- Menampilkan pesan jika foto tidak ada -->
                <p>Tidak ada foto profil</p>
            <?php endif; ?>  
            <input type="file" class="form-control" name="profile_photo" id="profile_photo">
        </div>

        <div class="form-group d-flex justify-content-between align-items-center">
            <button type="reset" class="btn btn-danger">Reset</button>
            <a href="<?php echo base_url('UserController'); ?>" class="btn btn-primary">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>

        <?php echo form_close(); ?>
    </section>  
</div>

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
