<div class="content-wrapper">
    <!-- Header -->
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
        <h4><strong>EDIT DATA PUSKESMAS</strong></h4>

        <?= form_open('puskesmas/update/' . $puskesmas->id); ?>
        <input type="hidden" name="id" value="<?= $puskesmas->id; ?>">

        <!-- Nama Provinsi -->
        <div class="form-group">
            <label for="provinsi">Nama Provinsi</label>
            <select name="id_prov" id="provinsi" class="form-control" required>
                <option value="">Pilih Provinsi</option>
                <?php foreach ($provinsi as $prov): ?>
                    <option value="<?= $prov->id; ?>" <?= set_select('id_prov', $prov->id, $prov->id == $puskesmas->id_prov); ?>>
                        <?= $prov->nama; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Kabupaten -->
        <div class="form-group">
            <label for="id_kab">Kabupaten</label>
            <select name="id_kab" id="id_kab" class="form-control" required>
                <option value="">Pilih Kabupaten</option>
                <?php foreach ($kabupaten as $kab): ?>
                    <option value="<?= $kab->id; ?>" <?= set_select('id_kab', $kab->id, $kab->id == $puskesmas->id_kab); ?>>
                        <?= $kab->nama_kabupaten; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Kecamatan -->
        <div class="form-group">
            <label for="id_kec">Kecamatan</label>
            <select name="id_kec" id="id_kec" class="form-control" required>
                <option value="">Pilih Kecamatan</option>
                <?php foreach ($kecamatan as $kec): ?>
                    <option value="<?= $kec->id; ?>" <?= set_select('id_kec', $kec->id, $kec->id == $puskesmas->id_kec); ?>>
                        <?= $kec->nama_kec; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Nama Puskesmas -->
        <div class="form-group">
            <label for="nama_puskesmas">Nama Puskesmas</label>
            <input type="text" name="nama_puskesmas" id="nama_puskesmas" class="form-control" 
                   placeholder="Masukkan Nama Puskesmas" 
                   value="<?= set_value('nama_puskesmas', $puskesmas->nama_puskesmas); ?>" required>
        </div>

        <!-- Alamat -->
        <div class="form-group">
            <label for="alamat">Alamat Puskesmas</label>
            <input type="text" name="alamat" id="alamat" class="form-control" 
                   placeholder="Masukkan Alamat" 
                   value="<?= set_value('alamat', $puskesmas->alamat); ?>" required>
        </div>

        <!-- Kode 2 -->
        <div class="form-group">
            <label for="kode_2">Kode 2</label>
            <input type="text" name="kode_2" id="kode_2" class="form-control" 
                   placeholder="Masukkan Kode 2 Puskesmas" 
                   value="<?= set_value('kode_2', $puskesmas->kode_2); ?>" required>
        </div>

        <!-- Kode 3 -->
        <div class="form-group">
            <label for="kode_3">Kode 3</label>
            <input type="text" name="kode_3" id="kode_3" class="form-control" 
                   placeholder="Masukkan Kode 3 Puskesmas" 
                   value="<?= set_value('kode_3', $puskesmas->kode_3); ?>" required>
        </div>

        <!-- Buttons -->
        <div class="form-group">
            <button type="reset" class="btn btn-danger">Reset</button>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>

        <?= form_close(); ?>
    </section>
</div>
