<?php
include 'db_connect.php'; // Veritabanı bağlantısı
session_start();

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['customer_id'])) {
    die("Lütfen giriş yapın.");
}

$customer_id = $_SESSION['customer_id'];

// Kullanıcının siparişlerini getir
$sql = "SELECT o.order_id, o.total_price, o.status, o.created_at, d.dish_name, d.quantity
        FROM Orders o
        JOIN OrderDetails d ON o.order_id = d.order_id
        WHERE o.customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Siparişlerim</title>
    <link rel="stylesheet" href="restaurant.css">
</head>
<body>
    <div class="orders-container">
        <h1>Siparişlerim</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Sipariş ID</th>
                    <th>Ürün</th>
                    <th>Adet</th>
                    <th>Durum</th>
                    <th>Fiyat</th>
                    <th>Tarih</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['dish_name']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['total_price']; ?> TL</td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Henüz siparişiniz bulunmamaktadır.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>
