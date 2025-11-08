<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $kecamatan = $_POST['kecamatan'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];

    // Koneksi database
    $conn = new mysqli("localhost", "root", "", "latian");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query insert with prepared statement
    $stmt = $conn->prepare("INSERT INTO penduduk (kecamatan, longitude, latitude, luas, jumlah_penduduk) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sddii", $kecamatan, $longitude, $latitude, $luas, $jumlah_penduduk);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Data - Web GIS Kecamatan Sleman</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(to right, #83a4d4, #b6fbff);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 2rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        label {
            font-weight: bold;
            margin-bottom: 0.5rem;
            display: block;
            color: #eee;
        }
        input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            box-sizing: border-box;
            color: #333;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus {
            background: rgba(255, 255, 255, 0.8);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        input[type="submit"] {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.2s ease-in-out;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Input Data Kecamatan</h2>
    <form action="input.php" method="POST">
        <label for="kecamatan">Kecamatan:</label>
        <input type="text" id="kecamatan" name="kecamatan" required>

        <label for="longitude">Longitude:</label>
        <input type="text" id="longitude" name="longitude" required>

        <label for="latitude">Latitude:</label>
        <input type="text" id="latitude" name="latitude" required>

        <label for="luas">Luas:</label>
        <input type="text" id="luas" name="luas" required>

        <label for="jumlah_penduduk">Jumlah Penduduk:</label>
        <input type="text" id="jumlah_penduduk" name="jumlah_penduduk" required>

        <input type="submit" value="Simpan">
    </form>
</div>

</body>
</html>