<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.html");
    exit();
}

$npsn = $_POST['npsn'];
$nama = $_POST['nama'];
$status = $_POST['status'];
$alamat = $_POST['alamat'];
$desa = $_POST['desa'];
$kecamatan = $_POST['kecamatan'];
$kabupaten = $_POST['kabupaten'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "pgweb9";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "INSERT INTO sma_smk_sby (NPSN, Nama, Status, Alamat, Desa, Kecamatan, Kabupaten, Latitude, Longitude)
        VALUES ('$npsn', '$nama', '$status', '$alamat', '$desa', '$kecamatan', '$kabupaten', $latitude, $longitude)";

if ($conn->query($sql) === TRUE) {
    header("Location: ../index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>