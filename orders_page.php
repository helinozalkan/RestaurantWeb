<?php
include 'db_connect.php'; // Veritabanı bağlantısı
session_start();

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Güncel Siparişler</title>
    <link rel="stylesheet" href="orders_page.css">
</head>
<body>
    <div class="container">
        <!-- Header: Logo -->
        <header class="header">
            <div class="logo">
                <img src="menu-img/logo.jpg" alt="Logo">
                <span>Mrs. Kumsal's House</span>
            </div>
        </header>

        <!-- Geri Dön Butonu -->
        <div class="back-button-container">
            <a href="index.php" class="back-button">
                <i class="fa fa-arrow-left"></i> Geri Dön
            </a>
        </div>

        <!-- Siparişler Başlığı -->
        <div class="title-container">
            <h1>Gelen Siparişler</h1>
        </div>

        <!-- Tablo -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Sipariş Durumu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // JOIN işlemi ile orders, order_details ve menu tablolarından verileri alıyoruz
                    $sql = "
                    SELECT m.dish_name, o.status
                    FROM orders o
                    JOIN order_details od ON o.order_id = od.order_id
                    JOIN menu m ON od.menu_id = m.menu_id
                    WHERE o.status = 'Pending'"; // Yalnızca 'Pending' durumundaki siparişleri çek
                $result = $conn->query($sql);
                


                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['dish_name']}</td>
                                <td>{$row['status']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>Hiç sipariş bulunamadı.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
