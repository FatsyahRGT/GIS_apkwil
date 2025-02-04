<div class="content-wrapper">
  <!-- Header -->
  <section class="content-header">
    <h1>Data Pemekaran Kelurahan</h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?= base_url('pkelurahan/index'); ?>">
          <i class="fa fa-dashboard"></i> Home
        </a>
      </li>
      <li class="active">Master Data Pemekaran Kelurahan</li>
    </ol>
  </section>

  <!-- Content Section -->
  <section class="content">
    <!-- Tombol Tambah Data -->
    <a href="<?= site_url('pkelurahan/tambah'); ?>" class="btn btn-primary mb-4">
      <i class="fa fa-plus"></i> Pemekaran Kelurahan
    </a>

    <!-- Form Pencarian -->
    <div class="navbar-form navbar-right mb-3">
      <?php echo form_open('PkelurahanController/search'); ?>
        <input type="text" name="keyword" class="form-control" placeholder="Nama Pemekaran kelurahan">
        <button type="submit" class="btn btn-success">Cari</button>
      <?php echo form_close(); ?>
    </div>

    <!-- Form Show Entries -->
    <form method="get" action="<?= site_url('PkelurahanController/index'); ?>" class="d-flex align-items-center mb-3">
      <label for="showEntries" class="me-2">Show Entries:</label>
      <select id="showEntries" name="limit" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="10" <?= $this->input->get('limit') == 10 ? 'selected' : ''; ?>>10</option>
        <option value="25" <?= $this->input->get('limit') == 25 ? 'selected' : ''; ?>>25</option>
        <option value="50" <?= $this->input->get('limit') == 50 ? 'selected' : ''; ?>>50</option>
        <option value="100" <?= $this->input->get('limit') == 100 ? 'selected' : ''; ?>>100</option>
        <option value="all" <?= $this->input->get('limit') == 'all' ? 'selected' : ''; ?>>All</option>
      </select>
    </form>

    <!-- Pesan Aktivasi -->
    <?php if ($this->session->flashdata('message')): ?>
      <div class="alert alert-info">
        <?= $this->session->flashdata('message'); ?>
      </div>
    <?php endif; ?>

    <!-- Tabel Data -->
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>NO</th>
          <th>NAMA KELURAHAN</th>
          <th>KABUPATEN ASAL</th>
          <th>KABUPATEN TERBARU</th>
          <th>TAHUN PEMEKARAN</th>
          <th colspan="3" class="text-center">ACTION</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = isset($page) ? $page + 1 : 1; ?>
        <?php foreach ($pemekaran_kelurahan as $pkel): ?>
          <tr>
          <td><?= $no++; ?></td>
            <td><?= $pkel->kelurahan_asal; ?></td>
            <td><?= $pkel->kabupaten_asal; ?></td>
            <td><?= $pkel->kabupaten_baru; ?></td>
            <td><?= date('Y', strtotime($pkel->created_at)); ?></td>
            <td class="text-center">
            <td class="text-center">
                <a href="<?= site_url('pkelurahan/detail/' . $pkel->id); ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-search-plus"></i> Detail
                </a>
            </td>
            <!-- <td class="text-center">
                <a href="<?= site_url('pkabupaten/edit/' . $pkel->id); ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-edit"></i> Edit
                </a>
            </td> -->
            <td class="text-center">
                <a href="<?= site_url('PkelurahanController/hapus/' . $pkel->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?');">
                    <i class="fa fa-trash"></i> Hapus
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination-wrapper">
      <?= $pagination; ?>
    </div>

    <!-- Tombol Kembali -->
    <?php if ($this->uri->segment(2) === 'search'): ?>
      <a href="<?= site_url('pkelurahan/index'); ?>" class="btn btn-secondary mb-3 d-flex align-items-center">
        <i class="fa fa-arrow-left mr-2"></i> Kembali
      </a>
    <?php endif; ?>
  </section>
</div>
