<?php
include 'db_connect.php'; // Veritabanı bağlantısı

// SQL sorgusu: Stok ve ingredient bilgilerini al
$query = "
    SELECT 
        i.name AS ingredient_name, 
        i.unit AS ingredient_unit, 
        s.quantity 
    FROM stock s
    JOIN ingredients i ON s.ingredient_id = i.ingredient_id
";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Durumu</title>
    <link rel="stylesheet" href="suppliers.css">
</head>
<body>
    <!-- Ana Düzen -->
    <div class="container">
        <div class="back-button">
            <a href="javascript:history.back()">← Geri Dön</a>
        </div>

        <!-- İçerik -->
        <div class="content">
            <h1>Stok Durumu</h1>

            <!-- Stok Tablosu -->
            <div class="stock-table-container">
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Sıra</th>
                            <th>Malzeme Adı</th>
                            <th>Birim</th>
                            <th>Miktar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            $sira = 1; // Sıra numarası için başlangıç değeri
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $sira++ . "</td>"; // Sıra numarasını yazdır ve artır
                                echo "<td>" . $row['ingredient_name'] . "</td>";
                                echo "<td>" . $row['ingredient_unit'] . "</td>";
                                echo "<td>" . $row['quantity'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Hiç stok bilgisi bulunamadı.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Veritabanı bağlantısını kapat
$conn->close();
?>
