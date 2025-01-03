<?php
include 'db_connect.php'; // Veritabanı bağlantısını dahil et

$sql = "SELECT menu_id, dish_name, description, price, image FROM Menu"; // Görsel alanını da ekledim
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Menü öğelerini listele
    while($row = $result->fetch_assoc()) {
        echo "<div class='menu-item'>";
        echo "<img src='menu-img/" . $row['image'] . "' alt='" . $row['dish_name'] . "' class='menu-img'>";
        echo "<div class='item-info'>";
        echo "<h3>" . $row['dish_name'] . "</h3>";
        echo "<p>" . $row['description'] . "</p>";
        echo "<p><strong>Price:</strong> " . $row['price'] . " TL</p>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "Menüde hiç yemek yok!";
}

$conn->close();
?>
