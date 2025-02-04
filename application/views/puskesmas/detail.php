<div class="content-wrapper">
    <!-- Header -->
  <section class="content-header">
    <h1>Data Detail</h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?= base_url('puskesmas/index'); ?>">
          <i class="fa fa-dashboard"></i> Home
        </a>
      </li>
      <li class="active">Master Data Puskesmas</li>
    </ol>
  </section>
    <section class="content">
        <h4><strong>DETAIL DATA PUSKESMAS</strong></h4>

        <table class="table">
            <tr>
                <th>Nama Provinsi</th>
                <td><?php echo $provinsi->nama; ?></td>
            </tr>
            <tr>
                <th>Nama Kabupaten</th>
                <td><?php echo $kabupaten->nama; ?></td>
            </tr>
            <tr>
                <th>Nama Kecamatan</th>
                <td><?php echo $kecamatan->nama_kec; ?></td>
            </tr>   
            <tr>
                <th>Nama Puskesmas</th>
                <td><?php echo $detail->nama_puskesmas; ?></td>
            </tr>
            <tr>
                <th>Alamat Puskesmas</th>
                <td><?php echo $detail->alamat; ?></td>
            </tr>
            <tr>
                <th>Kode 2</th>
                <td><?php echo $detail->kode_2; ?></td>
            </tr>
            <tr>
                <th>Kode 3</th>
                <td><?php echo $detail->kode_3; ?></td>
            </tr>

        </table>

        <a href="<?php echo base_url('puskesmas/index'); ?>" class="btn btn-primary">Kembali</a>
    </section>
</div>

