<?php
include 'db_connect.php'; // Veritabanı bağlantısı
session_start();

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}

// Sipariş durumunu "Preparing" olarak güncelleme
if (isset($_POST['confirm_order'])) {
    $order_id = $_POST['order_id'];
    $update_status_sql = "UPDATE orders SET status = 'Preparing' WHERE order_id = $order_id";
    $conn->query($update_status_sql);
}
// hazırlanan sipariş durumunu "Prepared" olarak güncelleme
if (isset($_POST['order_ready'])) {
    $order_id = $_POST['order_id'];
    $update_status_sql = "UPDATE orders SET status = 'Prepared' WHERE order_id = $order_id";
    $conn->query($update_status_sql);
}


// Gelen siparişler (Pending)
$sql_pending = "SELECT o.order_id, m.dish_name, o.status 
                FROM orders o 
                JOIN order_details od ON o.order_id = od.order_id
                JOIN menu m ON od.menu_id = m.menu_id
                WHERE o.status = 'Pending'";
$result_pending = $conn->query($sql_pending);

// Hazırlanan siparişler (Preparing)
$sql_preparing = "SELECT o.order_id, m.dish_name, o.status 
                  FROM orders o 
                  JOIN order_details od ON o.order_id = od.order_id
                  JOIN menu m ON od.menu_id = m.menu_id
                  WHERE o.status = 'Preparing'";
$result_preparing = $conn->query($sql_preparing);

// Hazır siparişler (Prepared)
$sql_prepared = "SELECT o.order_id, m.dish_name, o.status 
                 FROM orders o 
                 JOIN order_details od ON o.order_id = od.order_id
                 JOIN menu m ON od.menu_id = m.menu_id
                 WHERE o.status = 'Prepared'";
$result_prepared = $conn->query($sql_prepared);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Yönetimi</title>
    <link rel="stylesheet" href="orders_page.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
        <div class="logo">
                <img src="menu-img/logo.png" alt="Logo">
                <span>Mrs. Kumsal's House</span>
            </div>
            <input type="text" class="search-bar" placeholder="Sipariş ara...">
        </header>

        <!-- Geri Dön Butonu -->
        <div class="back-button-container">
            <a href="chef.php" class="back-button">
                <i class="fa fa-arrow-left"></i> Geri Dön
            </a>
        </div>

<!-- Gelen Siparişler Tablosu -->
<div class="table-section">
    <div class="title-container">
        <h1>Gelen Siparişler</h1>
    </div>
    <div class="table-container">
        <table class="gelen-siparisler">
            <thead>
                <tr>
                    <th>Ürün Adı</th>
                    <th>Sipariş Durumu</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_pending->num_rows > 0) {
                    while ($row = $result_pending->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['dish_name']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <form method='POST'>
                                    <input type='hidden' name='order_id' value='{$row['order_id']}'>
                                    <button type='submit' name='confirm_order' class='confirm-button'>Onayla</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Hiç sipariş bulunamadı.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Hazırlanan Siparişler Tablosu -->
<div class="table-section">
    <div class="title-container">
        <h1>Hazırlanan Siparişler</h1>
    </div>
    <div class="table-container">
        <table class="hazirlanan-siparisler">
            <thead>
                <tr>
                    <th>Ürün Adı</th>
                    <th>Sipariş Durumu</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_preparing->num_rows > 0) {
                    while ($row = $result_preparing->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['dish_name']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <form method='POST'>
                                    <input type='hidden' name='order_id' value='{$row['order_id']}'>
                                    <button type='submit' name='order_ready' class='ready-button'>Sipariş Hazır</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Hiç sipariş bulunamadı.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Hazır Siparişler Tablosu -->
<div class="table-section">
    <div class="title-container">
        <h1>Hazır Siparişler</h1>
    </div>
    <div class="table-container">
        <table class="hazir-siparisler">
            <thead>
                <tr>
                    <th>Ürün Adı</th>
                    <th>Sipariş Durumu</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_prepared->num_rows > 0) {
                    while ($row = $result_prepared->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['dish_name']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <form method='POST'>
                                    <input type='hidden' name='order_id' value='{$row['order_id']}'>
                                    <button type='submit' name='order_delivered' class='delivered-button'>Afiyet Olsun!</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Hiç sipariş bulunamadı.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<?php $conn->close(); ?>
