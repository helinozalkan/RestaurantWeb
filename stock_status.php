<?php
include 'db_connect.php'; // Veritabanı bağlantısı

// SQL sorgusu: Stok bilgilerini al
$query = "SELECT stock_id, ingredient_id, quantity, threshold FROM stock";
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
                                <th>Stock ID</th>
                                <th>Ingredient ID</th>
                                <th>Quantity</th>
                                <th>Threshold</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                // Veritabanındaki her stok için satır ekle
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['stock_id'] . "</td>";
                                    echo "<td>" . $row['ingredient_id'] . "</td>";
                                    echo "<td>" . $row['quantity'] . "</td>";
                                    echo "<td>" . $row['threshold'] . "</td>";
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
    </div>

</body>
</html>

<?php
// Veritabanı bağlantısını kapat
$conn->close();
?>
