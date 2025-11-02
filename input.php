<?php
$servername = "localhost";
$username = "root";
$password = ""; // sesuaikan dengan server kamu
$dbname = "latian";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ==== BAGIAN DELETE ====
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_sql = "DELETE FROM penduduk WHERE id = $id";
    if ($conn->query($delete_sql) === TRUE) {
        // Setelah hapus, langsung refresh halaman biar tabel terbaru muncul
        header("Location: input.php");
        exit();
    } else {
        echo "Error menghapus data: " . $conn->error;
    }
}

// ==== BAGIAN INSERT ====
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kecamatan = $_POST['kecamatan'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];

    $sql = "INSERT INTO penduduk (kecamatan, longitude, latitude, luas, jumlah_penduduk)
            VALUES ('$kecamatan', $longitude, $latitude, $luas, $jumlah_penduduk)";

    if ($conn->query($sql) === TRUE) {
        echo "Data baru berhasil disimpan.<br><a href='index.html'>Kembali ke Form</a><br><br>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// ==== TAMPILKAN DATA ====
$result = $conn->query("SELECT * FROM penduduk");
if ($result->num_rows > 0) {
    echo "<h3>Daftar Data Penduduk</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>
            <tr style='background-color:#ddd'>
                <th>ID</th>
                <th>Kecamatan</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Luas</th>
                <th>Jumlah Penduduk</th>
                <th>Aksi</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['kecamatan']}</td>
                <td>{$row['longitude']}</td>
                <td>{$row['latitude']}</td>
                <td>{$row['luas']}</td>
                <td>{$row['jumlah_penduduk']}</td>
                <td><a href='?delete={$row['id']}' style='color:red; text-decoration:none;'>Hapus</a></td>
            </tr>";
    }
    echo "</table><br><a href='index.html'>Kembali ke Form Input</a>";
} else {
    echo "Belum ada data.<br><a href='index.html'>Tambah Data</a>";
}

$conn->close();
?>
