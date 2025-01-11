<?php
include 'db_connect.php'; // Veritabanı bağlantısı
$sql = "SELECT menu_id, dish_name, description, price, image FROM Menu";
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
                <img src="menu-img/logo.png" alt="Restaurant Logo" class="logo">
                <h1>Mrs. Kumsal's House</h1>
            </div>
            <div class="search-area">
                <input type="text" placeholder="Search dishes..." class="search-input">
            </div>
            <div class="profile-area">
                <img src="menu-img/user-boy-profile.png" alt="Profile" class="profile-img" onclick="toggleLogoutButton()">
                <button id="logout-button" onclick="location.href='login.php'" class="logout-button">Çıkış Yap</button>
            </div>
        </div>

        <!-- Butonlar -->
        <div class="button-panel">
            <button onclick="location.href='stock_status.php'" class="panel-button">Stok Durumu</button>
            <button onclick="location.href='all_orders.php'" class="panel-button">Siparişler</button>
            <button onclick="location.href='invoices.php'" class="panel-button">Faturalar</button>
            <button onclick="location.href='suppliers.php'" class="panel-button">Tedarikçiler</button>
        </div>
    </div>

    <script>
        function toggleLogoutButton() {
            const logoutButton = document.getElementById('logout-button');
            logoutButton.style.display = logoutButton.style.display === 'block' ? 'none' : 'block';
        }
    </script>

    
</body>
</html>
