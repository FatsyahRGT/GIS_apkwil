<div class="content-wrapper">
    <section class="content-header">
        <h1>Data Pemekaran Kecamatan</h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('pkecamatan/index'); ?>">
                <i class="fa fa-dashboard"></i> Home
            </a></li>
            <li class="active">Master Data Pemekaran Kecamatan</li>
        </ol>
    </section>

    <section class="content">
        <h4><strong>Tambah Pemekaran Kecamatan</strong></h4>

        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('PkecamatanController/tambah_aksi') ?>" method="post">
                    
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
    $('#kecamatan_asal').select2({
        placeholder: "Pilih Kecamatan",
        allowClear: true,
        ajax: {
            url: '<?= base_url('kecamatan/search_kecamatan'); ?>', // Endpoint untuk mencari kecamatan
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

document.getElementById('kecamatan_asal').addEventListener('change', function () {
    const kecamatanId = this.value; // Ambil ID Kecamatan dari dropdown

    // Validasi kecamatanId
    if (kecamatanId) {
        // Lakukan request ke controller menggunakan fetch
        fetch(`<?= base_url('PkecamatanController/get_provinsi_kabupaten_by_kecamatan/') ?>${kecamatanId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error); // Tampilkan error jika ada
                    document.getElementById('provinsi_asal').value = '';
                    document.getElementById('kabupaten_asal').value = '';
                } else {
                    // Update input Provinsi dan Kabupaten dengan data yang diterima
                    document.getElementById('provinsi_asal').value = data.nama_provinsi || 'Tidak ditemukan';
                    document.getElementById('kabupaten_asal').value = data.nama_kabupaten || 'Tidak ditemukan';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses data.');
                document.getElementById('provinsi_asal').value = '';
                document.getElementById('kabupaten_asal').value = '';
            });
    } else {
        // Kosongkan input jika tidak ada kecamatan yang dipilih
        document.getElementById('provinsi_asal').value = '';
        document.getElementById('kabupaten_asal').value = '';
    }
});



</script>
