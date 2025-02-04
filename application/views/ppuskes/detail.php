<div class="content-wrapper">
    <!-- Header -->
  <section class="content-header">
    <h1>Data Detail</h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?= base_url('ppuskesmas/index'); ?>">
          <i class="fa fa-dashboard"></i> Home
        </a>
      </li>
      <li class="active">Master Data Pemekaran Puskesmas</li>
    </ol>
  </section>
    <section class="content">
        <h4><strong>Detail Pemekaran Puskesmas</strong></h4>

        <table class="table">
            <tr>
                <th>Provinsi Asal</th>
                <td><?= $pemekaran_puskesmas->provinsi_asal; ?></td>
            </tr>
            <tr>
                <th>Kabupaten Asal</th>
                <td><?= $pemekaran_puskesmas->kabupaten_asal; ?></td>
            </tr>
            <tr>
                <th>Kabupaten Asal</th>
                <td><?= $pemekaran_puskesmas->kecamatan_asal; ?></td>
            </tr>
            <tr>
              <th>Provinsi Baru</th>
              <td><?= $pemekaran_puskesmas->provinsi_baru; ?></td>
            </tr>
            <tr>
              <th>Kabupaten Baru</th>
              <td><?= $pemekaran_puskesmas->kabupaten_baru; ?></td>
            </tr>
            <tr>
              <th>Kecamatan Baru</th>
              <td><?= $pemekaran_puskesmas->kecamatan_baru; ?></td>
            </tr>
            <tr>
                <th>Puskesmas</th>
                <td><?= $pemekaran_puskesmas->puskesmas_asal; ?></td>
            </tr>
            <tr>
                <th>Tahun Pemekaran</th>
                <td><?= date('d-m-Y', strtotime($pemekaran_puskesmas->created_at)); ?></td>
            </tr>

        </table>

        <a href="<?php echo base_url('puskesmas/index'); ?>" class="btn btn-primary">Kembali</a>
    </section>
</div>

