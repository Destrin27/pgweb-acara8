<!DOCTYPE html>
<html>
<head>
<title>Edit Data - Web GIS Kecamatan Sleman</title>
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
    input[type='text'] {
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
    input[type='text']:focus {
        background: rgba(255, 255, 255, 0.8);
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
    input[type='submit'] {
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
    input[type='submit']:hover {
        background-color: #0056b3;
    }
    #informasi {
        color: #dc3545; /* Red */
        text-align: center;
        margin-top: 1rem;
        font-weight: bold;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Edit Data Kecamatan</h2>
    <?php
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

    $id = $_GET['id'];
    $sql = "SELECT * FROM penduduk WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<form action='edit.php' onsubmit='return validateForm()' method='post'>";
        while ($row = $result->fetch_assoc()) {
            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
            echo "<label for='kecamatan'>Kecamatan:</label>";
            echo "<input type='text' id='kec' name='kecamatan' value='" . $row['kecamatan'] . "'>";
            echo "<label for='longitude'>Longitude:</label>";
            echo "<input type='text' id='long' name='longitude' value='" . $row['longitude'] . "'>";
            echo "<label for='latitude'>Latitude:</label>";
            echo "<input type='text' id='lat' name='latitude' value='" . $row['latitude'] . "'>";
            echo "<label for='luas'>Luas:</label>";
            echo "<input type='text' id='luas' name='luas' value='" . $row['luas'] . "'>";
            echo "<label for='jumlah_penduduk'>Jumlah Penduduk:</label>";
            echo "<input type='text' id='jml_pddk' name='jumlah_penduduk' value='" . $row['jumlah_penduduk'] . "'>";
        }
        echo "<input type='submit' value='Submit'>";
        echo "</form>";
    }
    ?>

    <p id="informasi"></p>
</div>
    <script>
        function validateForm() {

            let luas = document.getElementById("luas").value;
            let text = "";
            if (isNaN(luas) || luas < 1) {
                text = "Data luas harus angka dan tidak boleh bernilai negatif";
                // stop the form submission 
                event.preventDefault();
            }

            document.getElementById("informasi").innerHTML = text;
        }
    </script>
</body>

</html>