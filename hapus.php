<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pgweb9";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM sma_smk_sby WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error menghapus data: " . $conn->error;
    }
}

$conn->close();
?>