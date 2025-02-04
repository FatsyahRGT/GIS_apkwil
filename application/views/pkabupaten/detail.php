<div class="content-wrapper">
    <!-- Header -->
  <section class="content-header">
    <h1>Data Detail</h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?= base_url('pkabupaten/index'); ?>">
          <i class="fa fa-dashboard"></i> Home
        </a>
      </li>
      <li class="active">Master Data Pemekaran Kabupaten</li>
    </ol>
  </section>
    <section class="content">
        <h4><strong>Detail Pemekaran Kabupaten</strong></h4>

        <table class="table">
            <tr>
                <th>Provinsi Asal</th>
                <td><?= $pemekaran_kabupaten->provinsi_asal; ?></td>
            </tr>
            <tr>
                <th>Provinsi Baru</th>
                <td><?= $pemekaran_kabupaten->provinsi_baru; ?></td>
            </tr>
            <tr>
                <th>Kabupaten</th>
                <td><?= $pemekaran_kabupaten->kabupaten; ?></td>
            </tr>
            <tr>
                <th>Tahun Pemekaran</th>
                <td><?= date('d-m-Y', strtotime($pemekaran_kabupaten->created_at)); ?></td>
            </tr>
        </table>

        <a href="<?php echo base_url('pkabupaten/index'); ?>" class="btn btn-primary">Kembali</a>
    </section>
</div>

