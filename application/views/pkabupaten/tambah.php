<div class="content-wrapper">
    <section class="content-header">
        <h1>Data Pemekaran Kabupaten</h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('pkabupaten/index'); ?>">
                <i class="fa fa-dashboard"></i> Home
            </a></li>
            <li class="active">Master Data Pemekaran Kabupaten</li>
        </ol>
    </section>

    <section class="content">
        <h4><strong>Tambah Pemekaran Kabupaten</strong></h4>

        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('PkabupatenController/tambah_aksi') ?>" method="post">
                    
                    <div class="form-group">
                        <label for="provinsi_asal">Provinsi Asal</label>
                        <input type="text" id="provinsi_asal" name="provinsi_asal" class="form-control" readonly />
                    </div>
                    
                    <div class="form-group">
                        <label for="provinsi_baru">Provinsi Baru</label>
                        <select id="provinsi_baru" name="provinsi_baru" class="form-control">
                            <option value="">Pilih Provinsi Baru</option>
                            <?php foreach ($provinsi as $prov): ?>
                                <option value="<?= $prov->id ?>"><?= $prov->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="kabupaten_asal">Kabupaten Asal</label>
                            <select id="kabupaten_asal" name="kabupaten_asal" class="form-control">
                                <option value="">Pilih kabupaten</option>
                                <?php foreach ($kabupaten as $kab): ?>
                                    <option value="<?= $kab->id; ?>"><?= $kab->nama_kabupaten; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

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
     // Tambahkan event listener untuk dropdown Kabupaten
     document.getElementById('kabupaten_asal').addEventListener('change', function () {
        const kabupatenId = this.value; // Dapatkan ID Kabupaten yang dipilih

        // Cek apakah kabupatenId ada
        if (kabupatenId) {
            // Lakukan AJAX request ke controller
            fetch(`<?= base_url('PkabupatenController/get_provinsi_by_kabupaten/') ?>${kabupatenId}`)
                .then(response => response.json())
                .then(data => {
                    // Isi input Provinsi Asal dengan nama provinsi yang dikembalikan
                    document.getElementById('provinsi_asal').value = data.nama_provinsi || 'Tidak ditemukan';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('provinsi_asal').value = 'Error mendapatkan data';
                });
        } else {
            // Kosongkan input jika tidak ada kabupaten yang dipilih
            document.getElementById('provinsi_asal').value = '';
        }
    });
    $(document).ready(function() {
        // Initialize Select2 for Kabupaten dropdown
        $('#kabupaten_asal').select2({
            placeholder: "Pilih Kabupaten",
            allowClear: true,
            ajax: {
                url: '<?= base_url('kabupaten/search_kabupaten'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            }
        });

        // Initialize Select2 for Provinsi Baru dropdown
        $('#provinsi_baru').select2({
            placeholder: "Pilih Provinsi Baru",
            allowClear: true,
            ajax: {
                url: '<?= base_url('provinsi/search_provinsi'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            }
        });
    });
</script>
