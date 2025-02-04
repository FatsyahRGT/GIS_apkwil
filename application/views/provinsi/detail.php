<div class="content-wrapper">
    <!-- Header -->
  <section class="content-header">
    <h1>Data Provinsi</h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?= base_url('provinsi/index'); ?>">
          <i class="fa fa-dashboard"></i> Home
        </a>
      </li>
      <li class="active">Master Data Provinsi</li>
    </ol>
  </section>
    <section class="content">
        <h4><strong>DETAIL DATA PROVINSI</strong></h4>

        <table class="table">
            <tr>
                <th>Nama Provinsi</th>
                <td><?php echo $detail->nama_provinsi; ?></td>
            </tr>
            <tr>
                <th>Longitude</th>
                <td><?php echo $detail->longitude; ?></td>
            </tr>
            <tr>
                <th>Latitude</th>
                <td><?php echo $detail->latitude; ?></td>
            </tr>
        </table>

        <!-- Peta yang dipindahkan ke konten utama -->
        <h4><strong>Lokasi di Peta</strong></h4>
        <div id="map" style="height: 300px; width: 60%;"></div>

        <a href="<?php echo base_url('provinsi/index'); ?>" class="btn btn-primary">Kembali</a>
    </section>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-search/dist/leaflet-search.min.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-search/dist/leaflet-search.min.js"></script>

<script>
    // Ambil data longitude dan latitude dari database PHP
    var longitude = <?php echo $detail->longitude; ?>;
    var latitude = <?php echo $detail->latitude; ?>;

    // Inisialisasi map
    var map = L.map('map').setView([latitude, longitude], 13);

    // Tambahkan tile layer ke map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    // Tambahkan marker pada lokasi dari database
    var marker = L.marker([latitude, longitude], { draggable: true }).addTo(map);

    // Perbarui koordinat saat marker di-drag
    marker.on('dragend', function () {
        var latLng = marker.getLatLng();
        document.getElementById('latitude').value = latLng.lat;
        document.getElementById('longitude').value = latLng.lng;
    });

    // Tambahkan event click pada map untuk memperbarui posisi marker
    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
    });

    // Tambahkan kontrol pencarian
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

    // Tambahkan kontrol pencarian ke map
    map.addControl(searchControl);

    // Perbarui ukuran map saat konten dimuat
    map.invalidateSize();
</script>
