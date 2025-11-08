<!DOCTYPE html>
<html>
<head>
<title>Web GIS Kecamatan Sleman</title>
<style>
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background: linear-gradient(to right, #83a4d4, #b6fbff);
    color: #333;
    padding: 2rem;
    min-height: 100vh;
}
.main-container {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.18);
    padding: 2rem;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
}
.header-container {
    text-align: center;
    margin-bottom: 2rem;
}
.header-container h1 {
    font-size: 3em;
    color: #fff;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}
.nav-links {
    text-align: center;
    margin-bottom: 2rem;
}
.nav-button {
    background: rgba(255, 255, 255, 0.5);
    color: #333;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    text-decoration: none;
    margin: 0 0.5rem;
    font-weight: bold;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
}
.nav-button:hover {
    background: rgba(255, 255, 255, 0.8);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    background: transparent;
}
table thead tr {
    background: rgba(255, 255, 255, 0.4);
    color: #333;
    text-align: left;
}
table th, table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
}
table th {
    font-weight: bold;
}
table tbody tr:hover {
    background: rgba(255, 255, 255, 0.1);
}
.action-links a {
    margin: 0 5px;
    display: inline-block;
}
.action-links svg {
    width: 20px;
    height: 20px;
    transition: transform 0.2s;
}
.action-links a:hover svg {
    transform: scale(1.2);
}
</style>
</head>
<body>

<div class="main-container">
    <div class="header-container">
        <h1>Web GIS Kecamatan Sleman</h1>
    </div>

    <div class="nav-links">
        <a href='input.php' class="nav-button">Input Data Baru</a>
        <a href='leafletjs.php' class="nav-button">Lihat Peta Sebaran</a>
    </div>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "latian";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM penduduk";
    $result = $conn->query($sql);

    echo "<table>
    <thead>
    <tr>
        <th>Kecamatan</th>
        <th>Longitude</th>
        <th>Latitude</th>
        <th>Luas (km²)</th>
        <th>Jumlah Penduduk</th>
        <th style='text-align:center;'>Aksi</th>
    </tr>
    </thead>
    <tbody>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['kecamatan']}</td>
                <td>{$row['longitude']}</td>
                <td>{$row['latitude']}</td>
                <td>" . number_format($row['luas'], 2) . "</td>
                <td align='right'>" . number_format($row['jumlah_penduduk']) . "</td>
                <td class='action-links' style='text-align:center;'>
                    <a href='edit/index.php?id={$row['id']}' title='Edit'>
                        <svg viewBox='0 0 24 24' fill='none' stroke='#007bff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path><path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path></svg>
                    </a>
                    <a href='delete.php?id={$row['id']}' title='Hapus'>
                        <svg viewBox='0 0 24 24' fill='none' stroke='#dc3545' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg>
                    </a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data.</td></tr>";
    }

    echo "</tbody></table>";
    $conn->close();
    ?>
</div>

</body>
</html>
