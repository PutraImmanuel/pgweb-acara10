<?php
// 1. Koneksi ke database MySQL
$conn = new mysqli("localhost", "root", "", "pgweb9");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2. Query mengambil data sekolah
$query  = "SELECT * FROM sma_smk_sby";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Web GIS SMA/SMK Surabaya</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            display: flex;
            gap: 20px;
        }
        #map {
            width: 50%;
            height: 800px;
            border: 1px solid #ccc;
        }
        .table-container {
            width: 50%;
            height: 800px;
            overflow: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: left;
            white-space: nowrap;
        }
        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
        }
    </style>
</head>
<body>

    <h2>Web GIS Persebaran SMA/SMK Surabaya</h2>
    <p><a href="input/index.html">+ Tambah Data Baru</a></p>

    <div class="container">
        <!-- Kolom Kiri: Peta -->
        <div id="map"></div>

        <!-- Kolom Kanan: Tabel Data -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
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
                                'kecamatan' => $kecamatan,
                                'lat'       => $lat,
                                'lng'       => $lng
                            ];
                    ?>
                    <tr>
                        <td style="text-align: center;"><?= $id; ?></td> 
                        <td><b><?= htmlspecialchars($nama); ?></b></td>
                        <td><?= htmlspecialchars($npsn); ?></td>
                        <td><?= htmlspecialchars($status); ?></td>
                        <td><?= htmlspecialchars($alamat); ?></td>
                        <td><?= htmlspecialchars($desa); ?></td>
                        <td><?= htmlspecialchars($kecamatan); ?></td>
                        <td><?= htmlspecialchars($kabupaten); ?></td>
                        <td><?= $lat; ?></td>
                        <td><?= $lng; ?></td>
                        <td>
                            <a href="edit/index.php?id=<?= $id; ?>">Edit</a> | 
                            <a href="hapus.php?id=<?= $id; ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="11">Data kosong.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Leaflet JS Library -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-7.2575, 112.7521], 12);

        var basemap1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var basemap2 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });

        var markersGroup = L.layerGroup().addTo(map);

        var dataSekolah = <?= json_encode($markersData); ?>;

        dataSekolah.forEach(function(item) {
            var popupContent = `
                <b>${item.nama}</b><br>
                NPSN: ${item.npsn}<br>
                Status: ${item.status}<br>
                Alamat: ${item.alamat}, Kec. ${item.kecamatan}<br><br>
                <a href="edit/index.php?id=${item.id}">Edit</a> | 
                <a href="hapus.php?id=${item.id}" onclick="return confirm('Hapus data?')">Hapus</a>
            `;

            L.marker([item.lat, item.lng])
            .bindPopup(popupContent)
            .addTo(markersGroup);
        });

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