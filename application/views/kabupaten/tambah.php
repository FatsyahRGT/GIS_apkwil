<div class="content-wrapper">
  <section class="content-header">
    <h1>Data Kabupaten</h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?= base_url('kabupaten/index'); ?>">
          <i class="fa fa-dashboard"></i> Home
        </a>
      </li>
      <li class="active">Master Data Kabupaten</li>
    </ol>
  </section>
  <section class="content">
    <h4><strong>Tambah Data Kabupaten</strong></h4>

    <div class="card">
        <div class="card-body">
            <?php echo form_open_multipart('KabupatenController/tambah_aksi'); ?>

            <div class="form-group">
                <label for="provinsi_id">Nama Provinsi</label>
                <select name="provinsi_id" id="provinsi_id" class="form-control" required>
                    <option value="">Pilih Provinsi</option>
                    <?php foreach ($provinsi as $prov): ?>
                        <option value="<?= $prov->id ?>"><?= $prov->nama ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="nama">Nama Kabupaten</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Kabupaten" required>
            </div>

            <div class="form-group">
                <label for="kode_dagri">Kode Wilayah Dagri</label>
                <input type="text" name="kode_dagri" id="kode_dagri" class="form-control" placeholder="Masukkan Kode Wilayah Dagri" required>
            </div>

            <div class="form-group">
                <label for="kode_bps">Kode Wilayah BPS</label>
                <input type="text" name="kode_bps" id="kode_bps" class="form-control" placeholder="Masukkan Kode Wilayah BPS" required>
            </div>

            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Latitude Otomatis Terisi" readonly>
            </div>

            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Longitude Otomatis Terisi" readonly>
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mapModal">Lihat Map...</button>
                <button type="reset" class="btn btn-danger">Reset</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
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

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
  // Initialize Select2 for the provinsi dropdown
  $(document).ready(function() {
    $('#provinsi_id').select2({
        placeholder: "Pilih Provinsi",
        allowClear: true,
        ajax: {
            url: '<?= base_url('kabupaten/search_provinsi'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
  });

  // Initialize Map
  var map = L.map('map').setView([-6.25754, 106.9664952], 13);

  // Add Tile Layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  // Add Draggable Marker
  var marker = L.marker([-6.25754, 106.9664952], { draggable: true }).addTo(map);

  // Update coordinates on marker drag
  marker.on('dragend', function () {
    var latLng = marker.getLatLng();
    document.getElementById('latitude').value = latLng.lat;
    document.getElementById('longitude').value = latLng.lng;
  });

  // Update marker position on map click
  map.on('click', function (e) {
    marker.setLatLng(e.latlng);
    document.getElementById('latitude').value = e.latlng.lat;
    document.getElementById('longitude').value = e.latlng.lng;
  });

  // Add Search Control
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

  // Handle search result
  searchControl.on('search:locationfound', function (e) {
    marker.setLatLng(e.latlng);
    map.setView(e.latlng, 15);
    document.getElementById('latitude').value = e.latlng.lat;
    document.getElementById('longitude').value = e.latlng.lng;
  });

  // Add search control to map
  map.addControl(searchControl);

  // Close modal event
  document.getElementById('closeMap').addEventListener('click', function () {
    $('#mapModal').modal('hide');
  });

  // Invalidate map size when modal is shown
  $('#mapModal').on('shown.bs.modal', function () {
    map.invalidateSize();
  });
</script>
