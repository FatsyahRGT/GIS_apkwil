<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= base_url('assets/foto/' . ($this->session->userdata('profile_photo') ? $this->session->userdata('profile_photo') : 'default.png')) ?>" 
                     class="img-circle" 
                     alt="User Image" 
                     style="width: 50px; height: 50px; object-fit: cover;">
            </div>
            <div class="pull-left info">
                <p><?= $this->session->userdata('username') ? $this->session->userdata('username') : 'Guest'; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>    

        <!-- Sidebar menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MASTER DATA</li>

            <li>
                <a href="<?= base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('user/index'); ?>">
                    <i class="fa fa-user"></i>
                    <span>User</span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-map"></i>
                    <span>Master Data Wilayah</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= base_url('provinsi/index'); ?>">
                            <i class="fa fa-circle-o"></i> Provinsi
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('kabupaten/index'); ?>">
                            <i class="fa fa-circle-o"></i> Kabupaten
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('kecamatan/index'); ?>">
                            <i class="fa fa-circle-o"></i> Kecamatan
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('kelurahan/index'); ?>">
                            <i class="fa fa-circle-o"></i> Kelurahan
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('puskesmas/index'); ?>">
                            <i class="fa fa-circle-o"></i> Puskesmas
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-expand"></i>
                    <span>Pemekaran Wilayah</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= base_url('pkabupaten/index'); ?>">
                            <i class="fa fa-circle-o"></i> Pemekaran Kabupaten
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('pkecamatan/index'); ?>">
                            <i class="fa fa-circle-o"></i> Pemekaran Kecamatan
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('pkelurahan/index'); ?>">
                            <i class="fa fa-circle-o"></i> Pemekaran Kelurahan
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('ppuskes/index'); ?>">
                            <i class="fa fa-circle-o"></i> Pemekaran Puskesmas
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#" onclick="confirmLogout(event)">
                    <i class="fa fa-sign-out"></i>
                    <span>LogOut</span>
                </a>
            </li>
        </ul>
    </section>
</aside>

<script type="text/javascript">
    function confirmLogout(event) {
        // Menampilkan konfirmasi kepada pengguna
        var confirmation = confirm("Apakah Anda yakin ingin keluar?");
        
        // Jika pengguna mengklik OK, arahkan ke URL logout
        if (confirmation) {
            window.location.href = "<?= base_url('logout'); ?>";
        } else {
            // Mencegah aksi default jika memilih Cancel
            event.preventDefault();
        }
    }
</script>
