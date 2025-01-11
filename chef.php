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

        /* Çıkış yap butonunun başlangıçta gizlenmesi */
        #logout-button {
            display: none;
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
            <div class="profile-area">
                <img src="menu-img/user-boy-profile.png" alt="Profil" class="profile-img" onclick="toggleLogoutButton()">
                <button id="logout-button" onclick="location.href='login.php'" class="logout-button">Çıkış Yap</button>
            </div>
        </header>

        <div class="categories">
            <!-- Güncel Stok ve Güncel Sipariş Butonları -->
            <a href="stocks_page.php" class="category-button">GÜNCEL STOK</a>
            <a href="orders_page.php" class="category-button">GÜNCEL SİPARİŞ</a>
        </div>
    </div>

    <script src="scripts/script.js"></script>
    <script>
        // Profil resmine tıklandığında çıkış yap butonunun görünmesini sağlayan işlev
        function toggleLogoutButton() {
            const logoutButton = document.getElementById('logout-button');
            if (logoutButton.style.display === 'none') {
                logoutButton.style.display = 'block';
            } else {
                logoutButton.style.display = 'none';
            }
        }
    </script>
</body>
</html>
