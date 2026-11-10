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

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data lama sekolah dari database
$stmt = $conn->prepare("SELECT * FROM sma_smk_sby WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data   = $result->fetch_assoc();

if (!$data) {
    die("<div class='container py-5'><div class='alert alert-danger'>Data tidak ditemukan! <a href='../index.php'>Kembali</a></div></div>");
}

// Proses submit form update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npsn      = $_POST['npsn'];
    $nama      = $_POST['nama'];
    $status    = $_POST['status'];
    $alamat    = $_POST['alamat'];
    $desa      = $_POST['desa'];
    $kecamatan = $_POST['kecamatan'];
    $kabupaten = $_POST['kabupaten'];
    $latitude  = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $update_stmt = $conn->prepare("UPDATE sma_smk_sby SET NPSN=?, Nama=?, Status=?, Alamat=?, Desa=?, Kecamatan=?, Kabupaten=?, Latitude=?, Longitude=? WHERE id=?");
    $update_stmt->bind_param("sssssssssi", $npsn, $nama, $status, $alamat, $desa, $kecamatan, $kabupaten, $latitude, $longitude, $id);

    if ($update_stmt->execute()) {
        header("Location: ../index.php");
        exit();
    } else {
        $error_msg = "Gagal memperbarui data: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Data Sekolah - Web GIS</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5" style="max-width: 700px;">
        <div class="card shadow-sm border-0">
            <!-- Header Card (Warna Kuning/Warning untuk penanda Mode Edit) -->
            <div class="card-header bg-warning text-dark fw-bold py-3">
                <h4 class="mb-0 fs-5 text-center">Form Edit Data Sekolah</h4>
            </div>

            <!-- Body / Form -->
            <div class="card-body p-4">
                <?php if (isset($error_msg)): ?>
                    <div class="alert alert-danger"><?= $error_msg; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">NPSN</label>
                            <input type="text" name="npsn" class="form-control" value="<?= htmlspecialchars($data['NPSN']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Negeri" <?= ($data['Status'] == 'Negeri') ? 'selected' : ''; ?>>Negeri</option>
                                <option value="Swasta" <?= ($data['Status'] == 'Swasta') ? 'selected' : ''; ?>>Swasta</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Sekolah</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['Nama']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required><?= htmlspecialchars($data['Alamat']); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Desa / Kelurahan</label>
                            <input type="text" name="desa" class="form-control" value="<?= htmlspecialchars($data['Desa'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="<?= htmlspecialchars($data['Kecamatan']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kabupaten / Kota</label>
                        <input type="text" name="kabupaten" class="form-control" value="<?= htmlspecialchars($data['Kabupaten'] ?? 'Kota Surabaya'); ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="text" name="latitude" class="form-control" value="<?= htmlspecialchars($data['Latitude']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="text" name="longitude" class="form-control" value="<?= htmlspecialchars($data['Longitude']); ?>" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Tombol Navigasi -->
                    <div class="d-flex justify-content-between">
                        <a href="../index.php" class="btn btn-outline-secondary px-4">← Batal</a>
                        <button type="submit" class="btn btn-warning px-4 fw-semibold text-dark">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/bootstrap.bundle.min.js"></script>
</body>
</html>