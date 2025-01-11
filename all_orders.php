<?php
include 'db_connect.php'; // Veritabanı bağlantısı

// Saklı yordamı çağır
$query = "CALL GetOrderDetails()";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişler</title>
    <link rel="stylesheet" href="all_orders.css">
</head>
<body>
    <!-- Ana Düzen -->
    <div class="container">
        <!-- Geri Dön Butonu -->
        <div class="back-button">
            <a href="javascript:history.back()">← Geri Dön</a>
        </div>

        <!-- İçerik -->
        <div class="table-container">
            <h1>Sipariş Listesi</h1>
            <table>
                <thead>
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Adet</th>
                        <th>Sipariş Durumu</th>
                        <th>Sipariş Tarihi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        // Veritabanındaki her sipariş için satır ekle
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['dish_name'] . "</td>";
                            echo "<td>" . ($row['quantity'] ? $row['quantity'] : 'NULL') . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td>" . $row['created_at'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Hiç sipariş bulunamadı.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
