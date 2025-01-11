<?php
include 'db_connect.php'; // Veritabanı bağlantısı

// Saklı yordamı çağır
$query = "CALL GetSuppliers()";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tedarikçiler</title>
    <link rel="stylesheet" href="suppliers.css">
</head>
<body>
    <!-- Geri Dön Butonu -->
    <div class="back-button">
        <a href="javascript:history.back()">← Geri Dön</a>
    </div>

    <!-- Tedarikçi Tablosu -->
    <div class="table-container">
        <h1>Tedarikçi Listesi</h1>
        <table>
            <thead>
                <tr>
                    <th>Sıra</th>
                    <th>Tedarikçi Firma Adı</th>
                    <th>Hizmet Alanı</th>
                    <th>Bilgi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    $sirano = 1; // Sıra sütunu için başlangıç değeri
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $sirano++ . "</td>"; // Sıra sütunu
                        echo "<td>" . $row['name'] . "</td>"; // Firma Adı
                        echo "<td>" . $row['service_area'] . "</td>"; // Hizmet Alanı
                        echo "<td>" . $row['contact_info'] . "</td>"; // Bilgi
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Hiç tedarikçi bulunamadı.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    // Veritabanı bağlantısını kapat
    $conn->close();
    ?>
</body>
</html>
