<?php
include 'db_connect.php'; // Veritabanı bağlantısı

// SQL sorgusu: Sipariş bilgilerini al
$query = "SELECT m.dish_name, od.quantity, o.status, o.created_at
          FROM orders o
          LEFT JOIN order_details od ON o.order_id = od.order_id
          LEFT JOIN menu m ON od.menu_id = m.menu_id";  // Menu tablosunu da ekliyoruz
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
        <div class="content">
            <h1>Sipariş Listesi</h1>
            
            <!-- Sipariş Tablosu -->
            <div class="table-container">
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
                        if (mysqli_num_rows($result) > 0) {
                            // Veritabanındaki her sipariş için satır ekle
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['dish_name'] . "</td>"; // Ürün adı
                                echo "<td>" . ($row['quantity'] ? $row['quantity'] : 'NULL') . "</td>"; // Adet
                                echo "<td>" . $row['status'] . "</td>"; // Sipariş Durumu
                                echo "<td>" . $row['created_at'] . "</td>"; // Sipariş Tarihi
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
    </div>

</body>
</html>
