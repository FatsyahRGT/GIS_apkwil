<div class="content-wrapper">
  <section class="content-header">
    <h1>Data Puskesmas</h1>
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
    <h4><strong>Tambah Data Puskesmas</strong></h4>

    <div class="card">
      <div class="card-body">
        <?php echo form_open('PuskesmasController/tambah_aksi'); ?>

        <div class="form-group">
                <label for="provinsi_id">Nama Provinsi</label>
                <select name="provinsi_id" id="provinsi_id" class="form-control select2" required>
                    <option value="">Pilih Provinsi</option>
                    <?php foreach ($provinsi as $prov): ?>
                        <option value="<?= $prov->id ?>"><?= $prov->nama ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="kabupaten_id">Nama Kabupaten</label>
                <select name="kabupaten_id" id="kabupaten_id" class="form-control select2" required>
                    <option value="">Pilih Kabupaten</option>
                    <?php foreach ($kabupaten as $kab): ?>
                        <option value="<?= $kab->id ?>"><?= $kab->nama_kabupaten ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="kecamatan_id">Nama Kecamatan</label>
                <select name="kecamatan_id" id="kecamatan_id" class="form-control select2" required>
                    <option value="">Pilih Kecamatan</option>
                    <?php foreach ($kecamatan as $kec): ?>
                        <option value="<?= $kec->id ?>"><?= $kec->nama_kec ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        <div class="form-group">
          <label for="nama_puskesmas">Nama Puskesmas</label>
          <input type="text" name="nama_puskesmas" id="nama_puskesmas" class="form-control" placeholder="Masukkan Nama Puskesmas" required>
        </div>

        <div class="form-group">
          <label for="alamat">Alamat</label>
          <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat" required>
        </div>

        <div class="form-group">
          <label for="kode_2">Kode 2</label>
          <input type="text" name="kode_2" id="kode_2" class="form-control" placeholder="Masukkan Kode 2" required>
        </div>

        <div class="form-group">
          <label for="kode_3">Kode 3</label>
          <input type="text" name="kode_3" id="kode_3" class="form-control" placeholder="Masukkan Kode 3" required>
        </div>

        <div class="form-group">
          <button type="reset" class="btn btn-danger">Reset</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>

        <?php echo form_close(); ?>
      </div>
    </div>

    <!-- Modal Map -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="text-center" id="mapModalLabel">Pilih Lokasi di Map</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-0">
            <div id="map" style="height: 500px; width: 100%;"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="closeMap">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

