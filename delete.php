<?php
$id = $_GET['id'];

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "latian";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Hapus data
$sql = "DELETE FROM penduduk WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Record dengan id = $id berhasil dihapus.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

// Kembali ke index
header("Location: index.php");
exit;
?>
