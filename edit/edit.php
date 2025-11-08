<?php
$id = $_POST['id'];
$kecamatan = $_POST['kecamatan'];
$longitude = $_POST['longitude'];
$latitude = $_POST['latitude'];
$luas = $_POST['luas'];
$jumlah_penduduk = $_POST['jumlah_penduduk'];

// Sesuaikan dengan setting MySQL 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "latian";

// Create connection 
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("UPDATE penduduk SET kecamatan=?, longitude=?, latitude=?, luas=?, jumlah_penduduk=? WHERE id=?");
$stmt->bind_param("sddiii", $kecamatan, $longitude, $latitude, $luas, $jumlah_penduduk, $id);

if ($stmt->execute()) {
    // Record edited successfully
} else {
    // Error
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: ../index.php");
exit();
?>
