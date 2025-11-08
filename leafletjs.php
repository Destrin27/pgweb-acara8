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

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

// Generate table HTML
$table_html = "<table class='data-table'>";
$table_html .= "<thead><tr><th>Kecamatan</th><th>Luas (km²)</th><th>Jumlah Penduduk</th><th>Lihat di Peta</th></tr></thead><tbody>";
foreach ($data as $row) {
    $table_html .= "<tr data-id='{$row['id']}' data-lat='{$row['latitude']}' data-lng='{$row['longitude']}'>";
    $table_html .= "<td>{$row['kecamatan']}</td>";
    $table_html .= "<td>" . number_format($row['luas'], 2) . "</td>";
    $table_html .= "<td>" . number_format($row['jumlah_penduduk']) . "</td>";
    $table_html .= "<td><button class='fly-to-btn'>Go</button></td>";
    $table_html .= "</tr>";
}
$table_html .= "</tbody></table>";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Peta dan Data Kecamatan</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        body { 
            margin: 0; 
            padding: 0; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(to right, #83a4d4, #b6fbff);
            color: #333;
        }
        .page-header {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: #fff;
            padding: 1rem 1.5rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.18);
        }
        .page-header h1 {
            margin: 0;
            font-size: 2em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .back-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 1.5rem;
            background: rgba(255, 255, 255, 0.5);
            color: #333;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .back-button:hover {
            background: rgba(255, 255, 255, 0.8);
        }
        #map {
            height: 60vh;
            width: 100%;
        }
        .table-container {
            padding: 2rem;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .data-table thead tr {
            background: rgba(255, 255, 255, 0.4);
            color: #333;
            text-align: left;
        }
        .data-table th, .data-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .data-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
            cursor: pointer;
        }
        .fly-to-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }
        .fly-to-btn:hover {
            background: #0056b3;
        }
        .leaflet-popup-content-wrapper {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .leaflet-popup-content {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            text-align: center;
        }
        .popup-header {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .popup-info p {
            margin: 5px 0;
        }
        .leaflet-tooltip {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>

<div class="page-header">
    <a href="index.php" class="back-button">&laquo; Back to Home</a>
    <h1>Dashboard Peta Kecamatan</h1>
</div>

<div id="map"></div>

<div class="table-container">
    <?php echo $table_html; ?>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Basemaps
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    });

    var dark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    });

    // Map initialization
    var map = L.map('map', {
        center: [-6.9175, 107.6191],
        zoom: 10,
        layers: [osm] // Default layer
    });

    var baseMaps = {
        "OpenStreetMap": osm,
        "Satellite": satellite,
        "Dark Mode": dark
    };

    L.control.layers(baseMaps).addTo(map);

    var locations = <?php echo json_encode($data); ?>;
    var markers = {};

    locations.forEach(function(location) {
        if (location.latitude && location.longitude) {
            var marker = L.marker([location.latitude, location.longitude]).addTo(map);

            // Tooltip
            marker.bindTooltip(location.kecamatan, { permanent: false, direction: 'top' });

            // Popup
            var popupContent = 
                `<div class="popup-header">${location.kecamatan}</div>` +
                `<div class="popup-info">` +
                `<p><strong>Luas:</strong> ${parseFloat(location.luas).toFixed(2)} km²</p>` +
                `<p><strong>Jumlah Penduduk:</strong> ${parseInt(location.jumlah_penduduk).toLocaleString()}</p>` +
                `</div>`;
            marker.bindPopup(popupContent);
            
            // Marker click event
            marker.on('click', function() {
                map.flyTo([location.latitude, location.longitude], 14);
            });

            markers[location.id] = marker;
        }
    });

    if (Object.keys(markers).length > 0) {
        var group = new L.featureGroup(Object.values(markers));
        map.fitBounds(group.getBounds()).pad(0.1);
    }

    document.querySelectorAll('.data-table tr[data-id]').forEach(function(row) {
        row.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var lat = this.getAttribute('data-lat');
            var lng = this.getAttribute('data-lng');
            
            if (markers[id]) {
                map.flyTo([lat, lng], 14);
                markers[id].openPopup();
            }
        });
    });

</script>

</body>
</html>
