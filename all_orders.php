<?php
include 'db_connect.php'; // Veritabanı bağlantısı

// SQL sorgusu: Sipariş bilgilerini al
$query = "SELECT o.order_id, o.customer_id, o.total_price, o.status, o.created_at, od.quantity
          FROM orders o
          LEFT JOIN order_details od ON o.order_id = od.order_id";
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
                            <th>Order ID</th>
                            <th>Customer ID</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            // Veritabanındaki her sipariş için satır ekle
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['order_id'] . "</td>";
                                echo "<td>" . $row['customer_id'] . "</td>";
                                echo "<td>" . $row['total_price'] . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "<td>" . $row['created_at'] . "</td>";
                                echo "<td>" . ($row['quantity'] ? $row['quantity'] : 'NULL') . "</td>";
                                echo "<td>
                                        <a href='edit_order.php?order_id=" . $row['order_id'] . "'>Düzenle</a> | 
                                        <a href='copy_order.php?order_id=" . $row['order_id'] . "'>Kopyala</a> | 
                                        <a href='delete_order.php?order_id=" . $row['order_id'] . "'>Sil</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Hiç sipariş bulunamadı.</td></tr>";
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
