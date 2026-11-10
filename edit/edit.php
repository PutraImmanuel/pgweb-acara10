<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit();
}

$id = $_POST['id'];
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

$sql = "UPDATE sma_smk_sby SET 
            NPSN='$npsn', 
            Nama='$nama', 
            Status='$status', 
            Alamat='$alamat', 
            Desa='$desa', 
            Kecamatan='$kecamatan', 
            Kabupaten='$kabupaten', 
            Latitude=$latitude, 
            Longitude=$longitude 
        WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: ../index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>