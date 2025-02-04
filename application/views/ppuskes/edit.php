<div class="content-wrapper">
    <section class="content-header">
        <h1>Data Pemekaran Kabupaten</h1>
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
        <h4><strong>Edit Pemekaran Kabupaten</strong></h4>

        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('pkabupaten/update/' . (isset($pemekaran_kabupaten->id) ? $pemekaran_kabupaten->id : '')) ?>" method="post">

                    <input type="hidden" name="id" value="<?= isset($pemekaran_kabupaten->id) ? $pemekaran_kabupaten->id : ''; ?>">

                    <!-- Provinsi Asal (Readonly) -->
                    <div class="form-group">
                        <label for="provinsi_asal">Provinsi Asal</label>
                        <input type="text" id="provinsi_asal" name="provinsi_asal" 
                            value="<?= isset($pemekaran_kabupaten->provinsi_asal) ? $pemekaran_kabupaten->provinsi_asal : ''; ?>" 
                            class="form-control" readonly />
                    </div>

                    <!-- Provinsi Baru -->
                    <div class="form-group">
                        <label for="provinsi_baru">Provinsi Baru</label>
                        <select id="provinsi_baru" name="provinsi_baru" class="form-control">
                            <?php if (!empty($provinsi)): ?>
                                <?php foreach ($provinsi as $prov): ?>
                                    <option value="<?= $prov->id ?>" <?= (isset($pemekaran_kabupaten->id_prov_new) && $prov->id == $pemekaran_kabupaten->id_prov_new) ? 'selected' : '' ?>>
                                        <?= $prov->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Kabupaten Asal -->
                    <div class="form-group">
                        <label for="kabupaten_asal">Kabupaten Asal</label>
                        <select id="kabupaten_asal" name="id_kab" class="form-control">
                            <?php if (!empty($kabupaten)): ?>
                                <?php foreach ($kabupaten as $kab): ?>
                                    <option value="<?= $kab->id ?>" <?= (isset($pemekaran_kabupaten->id_kab) && $kab->id == $pemekaran_kabupaten->id_kab) ? 'selected' : '' ?>>
                                        <?= $kab->nama_kabupaten ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
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

document.getElementById('kabupaten_asal').addEventListener('change', function () {
        const kabupatenId = this.value; // Dapatkan ID Kabupaten yang dipilih

        if (kabupatenId) {
            // AJAX request ke controller
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
        $('#kabupaten_asal, #kabupaten_baru').select2({
            placeholder: "Pilih Kabupaten",
            allowClear: true
        });

        // Initialize Select2 for Provinsi dropdowns
        $('#provinsi_baru').select2({
            placeholder: "Pilih Provinsi Baru",
            allowClear: true
        });
    });
</script>
