<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header text-center">
    <img 
      src="<?= base_url('assets/img/garuda.png') ?>" 
      alt="Dashboard Image" 
      style="width: 100%; max-width: 400px; height: auto; margin-top: 10px;">
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <!-- Box 1: Jumlah User -->
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?= $jumlah_user; ?></h3>
            <p>Jumlah User</p>
          </div>
          <div class="icon">
            <i class="ion ion-person"></i>
          </div>
          <a href="<?= base_url('user/index'); ?>" class="small-box-footer">
            Info Selanjutnya <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <!-- Box 2: Jumlah Provinsi -->
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="small-box bg-blue">
          <div class="inner">
            <h3><?= $jumlah_provinsi; ?></h3>
            <p>Jumlah Provinsi</p>
          </div>
          <div class="icon">
            <i class="fa fa-map"></i>
          </div>
          <a href="<?= base_url('provinsi/index'); ?>" class="small-box-footer">
            Info Selanjutnya <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <!-- Box 3: Jumlah Kabupaten -->
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?= $jumlah_kabupaten; ?></h3>
            <p>Jumlah Kabupaten</p>
          </div>
          <div class="icon">
            <i class="fa fa-map-marker"></i>
          </div>
          <a href="<?= base_url('kabupaten/index'); ?>" class="small-box-footer">
            Info Selanjutnya <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <!-- Box 4: Jumlah Kecamatan -->
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?= $jumlah_kecamatan; ?></h3>
            <p>Jumlah Kecamatan</p>
          </div>
          <div class="icon">
            <i class="fa fa-location-arrow"></i>
          </div>
          <a href="<?= base_url('kecamatan/index'); ?>" class="small-box-footer">
            Info Selanjutnya <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>
  </section>
</div>
