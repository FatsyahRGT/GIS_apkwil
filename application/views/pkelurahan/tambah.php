<div class="content-wrapper">
    <section class="content-header">
        <h1>Data Pemekaran Kelurahan</h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('pkelurahan/index'); ?>">
                <i class="fa fa-dashboard"></i> Home
            </a></li>
            <li class="active">Master Data Pemekaran Kelurahan</li>
        </ol>
    </section>

    <section class="content">
        <h4><strong>Tambah Pemekaran Kelurahan</strong></h4>

        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('PkelurahanController/tambah_aksi') ?>" method="post">
                    <!-- Provinsi Asal (Readonly) -->
                    <div class="form-group">
                        <label for="provinsi_asal">Provinsi Asal</label>
                        <input type="text" id="provinsi_asal" name="provinsi_asal" class="form-control" readonly />
                    </div>

                    <!-- Kabupaten Asal (Readonly) -->
                    <div class="form-group">
                        <label for="kabupaten_asal">Kabupaten Asal</label>
                        <input type="text" id="kabupaten_asal" name="kabupaten_asal" class="form-control" readonly />
                    </div>

                    <!-- Kecamatan Asal (Readonly) -->
                    <div class="form-group">
                        <label for="kecamatan_asal">Kecamatan Asal</label>
                        <input type="text" id="kecamatan_asal" name="kecamatan_asal" class="form-control" readonly />
                    </div>

                    <!-- Provinsi Baru -->
                    <div class="form-group">
                        <label for="provinsi_baru">Provinsi Baru</label>
                        <select id="provinsi_baru" name="provinsi_baru" class="form-control select2">
                            <option value="">Pilih Provinsi Baru</option>
                            <?php foreach ($provinsi as $prov): ?>
                                <option value="<?= $prov->id; ?>"><?= $prov->nama; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Kabupaten Baru -->
                    <div class="form-group">
                        <label for="kabupaten_baru">Kabupaten Baru</label>
                        <select id="kabupaten_baru" name="kabupaten_baru" class="form-control select2">
                            <option value="">Pilih Kabupaten Baru</option>
                            <?php foreach ($kabupaten as $kab): ?>
                                <option value="<?= $kab->id; ?>"><?= $kab->nama_kabupaten; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Kecamatan -->
                    <div class="form-group"> 
                        <label for="kecamatan_asal">Kecamatan</label>
                        <select id="kecamatan_asal" name="kecamatan_asal" class="form-control select2">
                            <option value="">Pilih Kecamatan</option>
                            <?php foreach ($kecamatan as $kec): ?>
                                <option value="<?= $kec->id; ?>"><?= $kec->nama_kec; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Kelurahan -->
                    <div class="form-group">
                        <label for="kelurahan_asal">Kelurahan</label>
                        <select id="kelurahan_asal" name="kelurahan_asal" class="form-control select2">
                            <option value="">Pilih Kelurahan</option>
                            <?php foreach ($kelurahan as $kel): ?>
                                <option value="<?= $kel->id; ?>"><?= $kel->nama; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="form-group">
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2(); // Inisialisasi Select2

    // Saat memilih kelurahan, otomatis isi provinsi, kabupaten, dan kecamatan
    $('#kelurahan_asal').change(function() {
        let idKelurahan = $(this).val();

        if (idKelurahan) {
            $.ajax({
                url: "<?= base_url('PkelurahanController/get_provinsi_kabupaten_kecamatan_by_kelurahan') ?>",
                type: "POST",
                data: { id_kelurahan: idKelurahan },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        $('#provinsi_asal').val(response.nama_provinsi);
                        $('#kabupaten_asal').val(response.nama_kabupaten);
                        $('#kecamatan_asal').val(response.nama_kecamatan);
                    }
                }
            });
        } else {
            $('#provinsi_asal').val('');
            $('#kabupaten_asal').val('');
            $('#kecamatan_asal').val('');
        }
    });
});
</script>

