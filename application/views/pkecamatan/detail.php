<div class="content-wrapper">
    <!-- Header -->
  <section class="content-header">
    <h1>Data Detail</h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?= base_url('pkecamatan/index'); ?>">
          <i class="fa fa-dashboard"></i> Home
        </a>
      </li>
      <li class="active">Master Data Pemekaran Kecamatan</li>
    </ol>
  </section>
    <section class="content">
        <h4><strong>Detail Pemekaran Kecamatan</strong></h4>

        <table class="table">
            <tr>
                <th>Provinsi Asal</th>
                <td><?= $pemekaran_kecamatan->provinsi_asal; ?></td>
            </tr>
            <tr>
                <th>Kabupaten Asal</th>
                <td><?= $pemekaran_kecamatan->kabupaten_asal; ?></td>
            </tr>
            <tr>
              <th>Provinsi Baru</th>
              <td><?= $pemekaran_kecamatan->provinsi_baru; ?></td>
            </tr>
            <tr>
              <th>Kabupaten Baru</th>
              <td><?= $pemekaran_kecamatan->kabupaten_baru; ?></td>
            </tr>
            <tr>
                <th>Kecamatan</th>
                <td><?= $pemekaran_kecamatan->kecamatan_asal; ?></td>
            </tr>
            <tr>
                <th>Tahun Pemekaran</th>
                <td><?= date('d-m-Y', strtotime($pemekaran_kecamatan->created_at)); ?></td>
            </tr>

        </table>

        <a href="<?php echo base_url('pkecamatan/index'); ?>" class="btn btn-primary">Kembali</a>
    </section>
</div>

