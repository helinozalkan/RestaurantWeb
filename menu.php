<?php
include 'db_connect.php'; // Veritabanı bağlantısını dahil et

$sql = "SELECT menu_id, dish_name, description, price, image FROM Menu"; // Görsel alanını da ekledim
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menü</title>
    <link rel="stylesheet" href="restaurant.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Menü Kategorileri</h2>
        <ul>
            <li><a href="#">Burger</a></li>
            <li><a href="#">Tatlı</a></li>
            <li><a href="#">İçecek</a></li>
            <li><a href="#">Atıştırmalık</a></li>
        </ul>
    </div>

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

    <!-- Menü İçeriği -->
    <div class="menu-container">
        <?php
        if ($result->num_rows > 0) {
            // Menü öğelerini listele
            while($row = $result->fetch_assoc()) {
                echo "<div class='menu-item'>";
                echo "<img src='menu-img/" . $row['image'] . "' alt='" . $row['dish_name'] . "' class='menu-img'>";
                echo "<div class='item-info'>";
                echo "<h3>" . $row['dish_name'] . "</h3>";
                echo "<p>" . $row['description'] . "</p>";
                echo "<p><strong>Fiyat:</strong> " . $row['price'] . " TL</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "Menüde hiç yemek yok!";
        }
        ?>
    </div>
</body>

</html>

<?php
$conn->close();
?>
