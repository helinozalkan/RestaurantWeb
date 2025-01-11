<?php
include 'db_connect.php'; // Veritabanı bağlantısı
$sql = "SELECT menu_id, dish_name, description, price, image FROM Menu"; // Görsel alanını da ekledim
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stocks_style.css">
    <link rel="stylesheet" href="improved_stocks_style.css">
    <link rel="stylesheet" href="orders_page.css">
    
    <title>Şef Ekranı</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Stil düzenlemelerini buraya ekliyoruz */
        .button {
            background-color: #c30000;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin: 20px;
        }

        .button:hover {
            background-color: #a20000;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <img src="menu-img/logo.png" alt="Logo">
                <span>Mrs. Kumsal's House</span>
            </div>
            <input type="text" class="search-bar" placeholder="Stok veya sipariş ara...">
        </header>

        <div class="categories">
            <!-- Güncel Stok ve Güncel Sipariş Butonları -->
            <a href="stocks_page.php" class="category-button">Güncel Stok</a>
            <a href="orders_page.php" class="category-button">Güncel Sipariş</a>
        </div>
    </div>

    <script src="scripts/script.js"></script>
</body>
</html>


