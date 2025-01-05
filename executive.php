<?php
include 'db_connect.php'; // Veritabanı bağlantısı
$sql = "SELECT menu_id, dish_name, description, price, image FROM Menu"; // Görsel alanını da ekledim
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Panel</title>
    <link rel="stylesheet" href="executive.css">
</head>
<body>
    <!-- Ana Düzen -->
    <div class="container">
        <!-- Üst Menü -->
        <div class="top-bar">
            <div class="logo-area">
                <img src="menu-img/logo.jpg" alt="Restaurant Logo" class="logo">
                <h1>Kumsal's Restaurant</h1>
            </div>
            <div class="search-area">
                <input type="text" placeholder="Search dishes..." class="search-input">
            </div>
            <div class="profile-area">
                <img src="menu-img/profile.png" alt="Profile" class="profile-img">
            </div>
        </div>
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Executive Panel</h2>
            <ul>
                <li><a href="stock_status.php">Stok Durumu</a></li>
                <li><a href="all_orders.php">Siparişler</a></li>
                <li><a href="invoices.php">Faturalar</a></li>
                <li><a href="suppliers.php">Tedarikçiler</a></li>
            </ul>
        </div>

        <!-- Menü Listeleme -->
        <div class="menu-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='menu-item'>";
                    echo "<img src='" . $row['image'] . "' alt='Dish Image' class='menu-image'>";
                    echo "<h3>" . $row['dish_name'] . "</h3>";
                    echo "<p>" . $row['description'] . "</p>";
                    echo "<p>Price: " . $row['price'] . " TL</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Menüde hiç öğe bulunamadı.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
