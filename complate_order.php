<?php
include 'db_connect.php'; // Veritabanı bağlantısı
session_start();

// Kullanıcının ID'sini almak için oturum kontrolü
if (!isset($_SESSION['customer_id'])) {
    die("Lütfen giriş yapın.");
}

$customer_id = $_SESSION['customer_id'];

// Siparişleri sorgula
$sql = "SELECT order_id, total_price, status, created_at FROM Orders WHERE customer_id = ?";
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
                    <th>Toplam Tutar</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['total_price']; ?> TL</td>
                        <td><?php echo $row['status']; ?></td>
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

<?php
$conn->close();
?>
