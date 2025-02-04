<div class="content-wrapper">
    <section class="content">
        <h4><strong>DETAIL DATA USER</strong></h4>

        <table class="table">
            <tr>
                <th>Nama Lengkap</th>
                <td><?php echo $detail->first_name . ' ' . $detail->last_name; ?></td>
            </tr>
            <tr>
                <th>Nama Depan</th>
                <td><?php echo $detail->first_name; ?></td>
            </tr>
            <tr>
                <th>Nama Belakang</th>
                <td><?php echo $detail->last_name; ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?php echo $detail->username; ?></td>
            </tr>
            <!-- <tr>
                <th>Password</th>
                <td><?php echo str_repeat('*', strlen($detail->password)); ?></td>
            </tr> -->
            <tr>
                <th>Role</th>
                <td><?php echo $detail->role_name; ?></td>
            </tr>
            <tr>
                <th>Tanggal Bergabung</th>
                <td><?php echo $detail->join_date; ?></td>
            </tr>
            <tr>
                <th>Foto Profil</th>
                <td>
                    <?php if (!empty($detail->profile_photo)): ?>
                        <img src="<?= base_url('UserController/tampilkan_foto/' . $detail->profile_photo); ?>" alt="Foto User" style="max-width: 200px; max-height: 200px;">
                    <?php else: ?>
                        <p>Tidak ada foto profil</p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <a href="<?php echo base_url('user/index'); ?>" class="btn btn-primary">Kembali</a>
    </section>
</div>
