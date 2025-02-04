<div class="content-wrapper">
  <!-- Header -->
  <section class="content-header">
    <h1>Data Kelurahan</h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?= base_url('kelurahan/index'); ?>">
          <i class="fa fa-dashboard"></i> Home
        </a>
      </li>
      <li class="active">Master Data Kelurahan</li>
    </ol>
  </section>
  <section class="content">
    <h4><strong>EDIT DATA KELURAHAN</strong></h4>

    <?php echo form_open('kelurahan/update/' . $kelurahan->id); ?>

    <input type="hidden" name="id" value="<?php echo $kelurahan->id; ?>">

    <!-- Nama Provinsi -->
    <div class="form-group">
        <label for="provinsi">Nama Provinsi</label>
        <select name="id_prov" id="provinsi" class="form-control" required>
            <option value="">Pilih Provinsi</option>
            <?php foreach ($provinsi as $prov): ?>
                <option value="<?= $prov->id ?>" <?= set_select('id_prov', $prov->id, $prov->id == $kelurahan->id_prov); ?>>
                    <?= $prov->nama ?>
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
                <option value="<?= $kab->id ?>" <?= set_select('id_kab', $kab->id, $kab->id == $kelurahan->id_kab); ?>>
                    <?= $kab->nama_kabupaten ?>
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
                <option value="<?= $kec->id ?>" <?= set_select('id_kec', $kec->id, $kec->id == $kelurahan->id_kec); ?>>
                    <?= $kec->nama_kec ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Nama Kelurahan -->
    <div class="form-group">
        <label for="nama">Nama Kelurahan</label>
        <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Kelurahan" value="<?= set_value('nama', $kelurahan->nama); ?>" required>
    </div>

    <!-- Kode Dagri -->
    <div class="form-group">
        <label for="kode_dagri">Kode Wilayah Dagri</label>
        <input type="text" name="kode_dagri" id="kode_dagri" class="form-control" placeholder="Masukkan Kode Wilayah Dagri" value="<?= set_value('kode_dagri', $kelurahan->kode_dagri); ?>" required>
    </div>

    <!-- Kode BPS -->
    <div class="form-group">
        <label for="kode_bps">Kode Wilayah BPS</label>
        <input type="text" name="kode_bps" id="kode_bps" class="form-control" placeholder="Masukkan Kode Wilayah BPS" value="<?= set_value('kode_bps', $kelurahan->kode_bps); ?>" required>
    </div>

    <!-- Latitude -->
    <div class="form-group">
        <label for="latitude">Latitude</label>
        <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Latitude Otomatis Terisi" value="<?= set_value('latitude', $coords->latitude); ?>" readonly>
    </div>

    <!-- Longitude -->
    <div class="form-group">
        <label for="longitude">Longitude</label>
        <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Longitude Otomatis Terisi" value="<?= set_value('longitude', $coords->longitude); ?>" readonly>
    </div>

    <!-- Buttons -->
    <div class="form-group">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mapModal">Lihat Map...</button>
        <button type="reset" class="btn btn-danger">Reset</button>
        <button type="submit" class="btn btn-success">Simpan</button>
    </div>

    <?php echo form_close(); ?>
  </section>
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

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-search/dist/leaflet-search.min.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-search/dist/leaflet-search.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
  // Inisialisasi Peta
  var map = L.map('map').setView([-6.25754, 106.9664952], 13);

  // Tambahkan Tile Layer (Peta Dasar)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  // Tambahkan Marker yang Dapat Dipindah
  var marker = L.marker([-6.25754, 106.9664952], { draggable: true }).addTo(map);

  // Update Koordinat saat Marker Dipindahkan
  marker.on('dragend', function () {
    var latLng = marker.getLatLng();
    document.getElementById('latitude').value = latLng.lat;
    document.getElementById('longitude').value = latLng.lng;
  });

  // Update Posisi Marker saat Peta Diklik
  map.on('click', function (e) {
    marker.setLatLng(e.latlng);
    document.getElementById('latitude').value = e.latlng.lat;
    document.getElementById('longitude').value = e.latlng.lng;
  });

  // Tambahkan Control Pencarian
  var searchControl = new L.Control.Search({
    url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}',
    jsonpParam: 'json_callback',
    propertyName: 'display_name',
    propertyLoc: ['lat', 'lon'],
    marker: false,
    autoCollapse: true,
    autoType: false,
    minLength: 2
  });

  // Tangani Hasil Pencarian
  searchControl.on('search:locationfound', function (e) {
    marker.setLatLng(e.latlng);
    map.setView(e.latlng, 15);
    document.getElementById('latitude').value = e.latlng.lat;
    document.getElementById('longitude').value = e.latlng.lng;
  });

  // Tambahkan Control Pencarian ke Peta
  map.addControl(searchControl);

  // Event untuk Menutup Modal
  document.getElementById('closeMap').addEventListener('click', function () {
    $('#mapModal').modal('hide');
  });

  // Perbarui Ukuran Peta Ketika Modal Ditampilkan
  $('#mapModal').on('shown.bs.modal', function () {
    map.invalidateSize();
  });

  // Inisialisasi Select2
  $(document).ready(function() {
    $('#provinsi').select2({
      placeholder: 'Pilih Provinsi',
      ajax: {
        url: '<?= base_url('kabupaten/get_provinsi'); ?>',
        dataType: 'json',
        processResults: function(data) {
          return {
            results: data.map(function(item) {
              return { id: item.id, text: item.nama };
            })
          };
        }
      }
    });

    $('#kabupaten_id').select2({
      placeholder: 'Pilih Kabupaten',
      ajax: {
        url: '<?= base_url('kecamatan/get_kabupaten'); ?>',
        dataType: 'json',
        processResults: function(data) {
          return {
            results: data.map(function(item) {
              return { id: item.id, text: item.nama_kabupaten };
            })
          };
        }
      }
    });
  });
</script>
