<?php
// Koneksi ke database pgweb9
$host     = "localhost";
$user     = "root";
$password = "";
$database = "pgweb9"; // Sesuaikan jika nama database Anda berbeda

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil seluruh data sekolah dari tabel sma_smk_sby
$query  = "SELECT * FROM sma_smk_sby";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Web GIS & Data SMA/SMK Surabaya</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    
    <style>
        #map {
            height: 650px;
            width: 100%;
            border-radius: 8px;
        }
        /* Container scrollable horizontal & vertikal */
        .table-scroll-container {
            max-height: 580px;
            overflow-x: auto;
            overflow-y: auto;
        }
        /* Mencegah teks melipat/terpotong agar scroll horizontal aktif */
        .table-scroll-container th,
        .table-scroll-container td {
            white-space: nowrap;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container-fluid py-4 px-4">
        <h2 class="mb-4 text-center fw-bold">Web Persebaran SMA/SMK di Surabaya</h2>
        
        <div class="row g-3">
            
            <!-- KOLOM KIRI: PETA LEAFLET -->
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white fw-bold">
                        Peta Persebaran Titik
                    </div>
                    <div class="card-body p-2">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: TABEL DATA (LENGKAP SEMUA KOLOM + SCROLL HORIZONTAL) -->
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Data SMA / SMK</span>
                        <a href="input/index.html" class="btn btn-sm btn-success">+ Tambah Data</a>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-scroll-container">
                            <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 13px;">
                                <thead class="table-secondary text-center sticky-top">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Sekolah</th>
                                        <th>NPSN</th>
                                        <th>Status</th>
                                        <th>Alamat</th>
                                        <th>Desa / Kelurahan</th>
                                        <th>Kecamatan</th>
                                        <th>Kabupaten / Kota</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $markersData = [];
                                    if ($result && $result->num_rows > 0) {
                                        $no = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $id        = $row['id'];
                                            $nama      = $row['Nama'];
                                            $npsn      = $row['NPSN'];
                                            $status    = $row['Status'];
                                            $alamat    = $row['Alamat'];
                                            $desa      = $row['Desa'] ?? '-';
                                            $kecamatan = $row['Kecamatan'];
                                            $kabupaten = $row['Kabupaten'] ?? 'Kota Surabaya';
                                            
                                            $lat = floatval($row['Latitude']);
                                            $lng = floatval($row['Longitude']);

                                            if ($lat > 0) {
                                                $lat = -$lat;
                                            }

                                            $markersData[] = [
                                                'id'        => $id,
                                                'nama'      => $nama,
                                                'npsn'      => $npsn,
                                                'status'    => $status,
                                                'alamat'    => $alamat,
                                                'desa'      => $desa,
                                                'kecamatan' => $kecamatan,
                                                'kabupaten' => $kabupaten,
                                                'lat'       => $lat,
                                                'lng'       => $lng
                                            ];
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><strong><?= htmlspecialchars($nama); ?></strong></td>
                                        <td class="text-center"><?= htmlspecialchars($npsn); ?></td>
                                        <td class="text-center">
                                            <span class="badge <?= ($status == 'Negeri') ? 'bg-success' : 'bg-info'; ?>">
                                                <?= htmlspecialchars($status); ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($alamat); ?></td>
                                        <td><?= htmlspecialchars($desa); ?></td>
                                        <td><?= htmlspecialchars($kecamatan); ?></td>
                                        <td><?= htmlspecialchars($kabupaten); ?></td>
                                        <td class="text-center"><?= $lat; ?></td>
                                        <td class="text-center"><?= $lng; ?></td>
                                        <td class="text-center">
                                            <a href="edit/index.php?id=<?= $id; ?>" class="btn btn-warning btn-sm py-0 px-2">Edit</a>
                                            <a href="hapus.php?id=<?= $id; ?>" 
                                            class="btn btn-danger btn-sm py-0 px-2" 
                                            onclick="return confirm('Yakin menghapus data <?= addslashes($nama); ?>?')">
                                            Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    } else {
                                        echo '<tr><td colspan="11" class="text-center">Data kosong.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Leaflet JS & Bootstrap JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/bootstrap.bundle.min.js"></script>

    <script>
        /* Inisialisasi Peta Surabaya */
        var map = L.map('map').setView([-7.2575, 112.7521], 12);

        /* Basemap Layers */
        var basemap1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var basemap2 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });

        var basemap1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var markersGroup = L.layerGroup().addTo(map);

        /* Data dari PHP ke Marker Leaflet */
        var dataSekolah = <?= json_encode($markersData); ?>;

        dataSekolah.forEach(function(item) {
            var popupContent = `
                <div style="font-size: 13px;">
                    <strong>${item.nama}</strong><br>
                    <b>NPSN:</b> ${item.npsn}<br>
                    <b>Status:</b> ${item.status}<br>
                    <b>Alamat:</b> ${item.alamat}, Kec. ${item.kecamatan}<br><br>
                    <a href="edit/index.php?id=${item.id}" class="btn btn-warning btn-sm py-0 px-1 text-white">Edit</a>
                    <a href="hapus.php?id=${item.id}" class="btn btn-danger btn-sm py-0 px-1" onclick="return confirm('Hapus data?')">Hapus</a>
                </div>
            `;

            L.marker([item.lat, item.lng])
            .bindPopup(popupContent)
            .addTo(markersGroup);
        });

        /* Layer Control */
        var baseMaps = {
            "OpenStreetMap": basemap1,
            "Esri Imagery": basemap2
        };

        var overlayMaps = {
            "Titik Sekolah": markersGroup
        };

        L.control.layers(baseMaps, overlayMaps, {collapsed: false}).addTo(map);
        L.control.scale({position: 'bottomright'}).addTo(map);
    </script>
</body>
</html>