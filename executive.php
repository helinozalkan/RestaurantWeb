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

        <!-- İçerik -->
        <div class="content">
            <h1>Hoşgeldiniz!</h1>
            <p>Sol menüden bir işlem seçin.</p>
        </div>
    </div>
</body>
</html>
