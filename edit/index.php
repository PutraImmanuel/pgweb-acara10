<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Edit Data Sekolah</title>
</head>
<body>
<h2>Form Edit Data Sekolah</h2>

<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "pgweb9";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "SELECT * FROM sma_smk_sby WHERE id = $id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<form action='edit.php' onsubmit='return validateForm(event)' method='post'>";
        echo "<input type='hidden' name='id' value='".$row['id']."'>";

        echo "<label for='npsn'>NPSN:</label><br>";
        echo "<input type='text' id='npsn' name='npsn' value='".$row['NPSN']."'><br><br>";

        echo "<label for='nama'>Nama Sekolah:</label><br>";
        echo "<input type='text' id='nama' name='nama' value='".$row['Nama']."'><br><br>";

        echo "<label for='status'>Status:</label><br>";
        echo "<input type='text' id='status' name='status' value='".$row['Status']."'><br><br>";

        echo "<label for='alamat'>Alamat:</label><br>";
        echo "<input type='text' id='alamat' name='alamat' value='".$row['Alamat']."'><br><br>";

        echo "<label for='desa'>Desa/Kelurahan:</label><br>";
        echo "<input type='text' id='desa' name='desa' value='".$row['Desa']."'><br><br>";

        echo "<label for='kec'>Kecamatan:</label><br>";
        echo "<input type='text' id='kec' name='kecamatan' value='".$row['Kecamatan']."'><br><br>";

        echo "<label for='kab'>Kabupaten/Kota:</label><br>";
        echo "<input type='text' id='kab' name='kabupaten' value='".$row['Kabupaten']."'><br><br>";

        echo "<label for='lat'>Latitude:</label><br>";
        echo "<input type='text' id='lat' name='latitude' value='".$row['Latitude']."'><br><br>";

        echo "<label for='long'>Longitude:</label><br>";
        echo "<input type='text' id='long' name='longitude' value='".$row['Longitude']."'><br><br>";

        echo "<input type='submit' value='Submit'>";
        echo "</form>";
    }
} else {
    echo "Data sekolah tidak ditemukan.";
}
$conn->close();
?>

<p id="informasi" style="color: red;"></p>

<script>
function validateForm(event) {
    let lat = document.getElementById("lat").value;
    let long = document.getElementById("long").value;
    let text = "";

    if (isNaN(lat) || lat === "" || isNaN(long) || long === "") {
        text = "Latitude dan Longitude harus berupa angka koordinat yang valid!";
        event.preventDefault();
        document.getElementById("informasi").innerHTML = text;
        return false;
    }
    return true;
}
</script>
</body>
</html>