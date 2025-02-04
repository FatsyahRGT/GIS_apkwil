<div class="content-wrapper">
    <section class="content-header">
        <h1>Data Pemekaran Puskesmas</h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('ppuskes/index'); ?>">
                <i class="fa fa-dashboard"></i> Home
            </a></li>
            <li class="active">Master Data Pemekaran Puskesmas</li>
        </ol>
    </section>

    <section class="content">
        <h4><strong>Tambah Pemekaran Puskesmas</strong></h4>

        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('PkesController/tambah_aksi') ?>" method="post">
                    <!-- Puskesmas -->
                    <div class="form-group">
                        <label for="puskesmas_asal">Puskesmas</label>
                        <select id="puskesmas_asal" name="puskesmas_asal" class="form-control select2">
                            <option value="">Pilih Puskesmas</option>
                            <?php foreach ($puskesmas as $pkes): ?>
                                <option value="<?= $pkes->id; ?>"><?= $pkes->nama_puskesmas; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Hidden Input untuk ID Provinsi, Kabupaten, dan Kecamatan Asal -->
                    <input type="hidden" id="id_prov_asal" name="id_prov_asal">
                    <input type="hidden" id="id_kab_asal" name="id_kab_asal">
                    <input type="hidden" id="id_kec_asal" name="id_kec_asal">

                    <!-- Provinsi Asal (Readonly) -->
                    <div class="form-group">
                        <label for="provinsi_asal">Provinsi Asal</label>
                        <input type="text" id="provinsi_asal" class="form-control" readonly />
                    </div>

                    <!-- Kabupaten Asal (Readonly) -->
                    <div class="form-group">
                        <label for="kabupaten_asal">Kabupaten Asal</label>
                        <input type="text" id="kabupaten_asal" class="form-control" readonly />
                    </div>

                    <!-- Kecamatan Asal (Readonly) -->
                    <div class="form-group">
                        <label for="kecamatan_asal">Kecamatan Asal</label>
                        <input type="text" id="kecamatan_asal" class="form-control" readonly />
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

                    <!-- Kecamatan Baru -->
                    <div class="form-group">
                        <label for="kecamatan_baru">Kecamatan Baru</label>
                        <select id="kecamatan_baru" name="kecamatan_baru" class="form-control select2">
                            <option value="">Pilih Kecamatan</option>
                            <?php foreach ($kecamatan as $kec): ?>
                                <option value="<?= $kec->id; ?>"><?= $kec->nama_kec; ?></option>
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
  $(document).ready(function () {
    // Initialize Select2 for Kecamatan dropdown
    $('#puskesmas_asal').select2({
        placeholder: "Pilih Puskesmas",
        allowClear: true,
        ajax: {
            url: '<?= base_url('puskesmas/search_kecamatan'); ?>', // Endpoint untuk mencari kecamatan
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return { results: data }; // Format data untuk Select2
            },
            cache: true
        }
    });

    // Initialize Select2 for Provinsi Baru dropdown
    $('#provinsi_baru').select2({
        placeholder: "Pilih Provinsi Baru",
        allowClear: true,
        ajax: {
            url: '<?= base_url('provinsi/search_provinsi'); ?>', // Endpoint untuk mencari provinsi
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return { results: data }; // Format data untuk Select2
            },
            cache: true
        }
    });
});

document.getElementById('puskesmas_asal').addEventListener('change', function () {
    const puskesmasId = this.value; // Ambil ID puskesmas dari dropdown

    // Validasi puskesmasId
    if (puskesmasId) {
        // Lakukan request ke controller menggunakan fetch
        fetch(`<?= base_url('PkesController/get_provinsi_kabupaten_kecamatan_by_puskesmas/') ?>${puskesmasId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error); // Tampilkan error jika ada
                    document.getElementById('provinsi_asal').value = '';
                    document.getElementById('kabupaten_asal').value = '';
                    document.getElementById('kecamatan_asal').value = '';
                } else {
                    // Update input Provinsi dan Kabupaten dengan data yang diterima
                    document.getElementById('provinsi_asal').value = data.nama_provinsi || 'Tidak ditemukan';
                    document.getElementById('kabupaten_asal').value = data.nama_kabupaten || 'Tidak ditemukan';
                    document.getElementById('kecamatan_asal').value = data.nama_kecamatan || 'Tidak ditemukan';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses data.');
                document.getElementById('provinsi_asal').value = '';
                document.getElementById('kabupaten_asal').value = '';
                document.getElementById('kecamatan_asal').value = '';
            });
    } else {
        // Kosongkan input jika tidak ada kecamatan yang dipilih
        document.getElementById('provinsi_asal').value = '';
        document.getElementById('kabupaten_asal').value = '';
        document.getElementById('kecamatan_asal').value = '';
    }
});
</script>

