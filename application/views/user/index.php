<div class="content-wrapper">
  <!-- Header -->
  <section class="content-header">
    <h1>Data User</h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('user/index'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Data User</li>
    </ol>
  </section>

  <!-- Content Section -->
  <section class="content">
    <!-- Tombol Tambah Data -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">
      <i class="fa fa-plus"></i> Tambah Data User
    </button>

    <!-- Search Form -->
    <div class="navbar-form navbar-right">
      <?php echo form_open('UserController/search', ['class' => 'form-inline']); ?>
      <div class="form-group">
        <input type="text" name="keyword" class="form-control" placeholder="Search" required>
      </div>
      <button type="submit" class="btn btn-success ml-2">
        <i class="fa fa-search"></i> Cari
      </button>
      <?php echo form_close(); ?>
    </div>

    <!-- Tabel Data User -->
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>NO</th>
          <th>USERNAME</th>
          <th>ROLE</th>
          <th>TANGGAL BERGABUNG</th>
          <th colspan="3" class="text-center">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = isset($page) ? $page + 1 : 1; ?>
        <?php foreach ($user as $usr): ?>
          <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $usr->username; ?></td>
            <td><?php echo $usr->role_name; ?></td>
            <td><?php echo $usr->join_date; ?></td>
            <!-- Aksi -->
            <td class="text-center">
              <a href="<?php echo site_url('user/detail/' . $usr->id); ?>" class="btn btn-success btn-sm">
                <i class="fa fa-search-plus"></i> Detail
              </a>
            </td>
            <td class="text-center">
              <a href="<?php echo site_url('user/edit/' . $usr->id); ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-edit"></i> Edit
              </a>
            </td>
            <td class="text-center">
              <a href="<?php echo site_url('UserController/hapus/' . $usr->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?');">
                <i class="fa fa-trash"></i> Hapus
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <!-- Tampilkan pagination -->
<div class="pagination-wrapper">
    <?php echo $pagination; ?>
</div>

<!-- Tombol Kembali -->
<?php if ($this->uri->segment(2) == 'search'): ?>
    <a href="<?php echo site_url('user/index'); ?>" class="btn btn-secondary mb-3 d-flex align-items-center">
        <i class="fa fa-arrow-left mr-2"></i> Kembali
    </a>
<?php endif; ?>


  </section>

  <!-- Modal Tambah Data -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header justify-content-center">
        <h4 class="modal-title font-weight-bold text-center" id="exampleModalLabel" style="font-size: 1.5rem;">Tambah Data User</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <!-- Modal Body -->
        <div class="modal-body">
          <?php echo form_open_multipart('UserController/tambah_aksi'); ?>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan Username" required>
          </div>

          <div class="form-group">
            <label for="first_name">Nama Depan</label>
            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Masukkan Nama Depan" required>
          </div>

          <div class="form-group">
            <label for="last_name">Nama Belakang</label>
            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Masukkan Nama Belakang" required>
          </div>

          <div class="form-group">
            <label for="id_role">Role</label>
            <select name="id_role" id="id_role" class="form-control" required>
              <option value="">Pilih Role</option>
              <option value="1">Superadmin</option>
              <option value="2">Admin</option>
            </select>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <div style="display: flex; align-items: center; gap: 8px;">
              <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password" required style="flex: 1;">
              <button type="button" id="togglePassword" class="btn btn-outline-secondary" aria-label="Toggle password visibility">👁️</button>
            </div>
          </div>

          <div class="form-group">
            <label for="confirm_password">Konfirmasi Password</label>
            <div style="display: flex; align-items: center; gap: 8px;">
              <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Konfirmasi Password" required style="flex: 1;">
              <button type="button" id="toggleConfirmPassword" class="btn btn-outline-secondary" aria-label="Toggle password visibility">👁️</button>
            </div>
          </div>
         
          <p id="passwordMismatch" style="color: red; display: none;">Kata sandi tidak sinkron!</p>

          <div class="form-group">
            <label for="profile_photo">Foto Profil</label>
            <input type="file" name="profile_photo" id="profile_photo" class="form-control">
          </div>
          
          <div class="modal-footer">
            <button type="reset" class="btn btn-danger">Reset</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
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
