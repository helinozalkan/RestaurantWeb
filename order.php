<?php
include 'db_connect.php'; // Veritabanı bağlantısını dahil et

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id']; // Kullanıcı ID'si (formdan alınacak)
    $menu_id = $_POST['menu_id']; // Menü ID'si (formdan alınacak)
    $quantity = $_POST['quantity']; // Sipariş miktarı (formdan alınacak)
    $price = $_POST['price']; // Fiyat (formdan alınacak)

    // Siparişi veritabanına ekle
    $sql = "INSERT INTO Orders (customer_id, total_price, status) 
            VALUES ('$customer_id', '$price', 'Pending')";

    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id; // Yeni eklenen siparişin ID'si

        // Sipariş detaylarını ekle
        $sql_detail = "INSERT INTO Order_Details (order_id, menu_id, quantity, price) 
                       VALUES ('$order_id', '$menu_id', '$quantity', '$price')";
        
        if ($conn->query($sql_detail) === TRUE) {
            echo "Sipariş başarıyla verildi!";
        } else {
            echo "Sipariş detayları eklenirken hata oluştu: " . $conn->error;
        }
    } else {
        echo "Sipariş kaydedilirken hata oluştu: " . $conn->error;
    }

    $conn->close();
}
?>

<!-- Sipariş Formu -->
<form method="POST" action="order.php">
    <label for="customer_id">Müşteri ID:</label>
    <input type="text" id="customer_id" name="customer_id" required><br>
    
    <label for="menu_id">Yemek ID:</label>
    <input type="text" id="menu_id" name="menu_id" required><br>
    
    <label for="quantity">Miktar:</label>
    <input type="number" id="quantity" name="quantity" required><br>
    
    <label for="price">Fiyat:</label>
    <input type="text" id="price" name="price" required><br>
    
    <input type="submit" value="Sipariş Ver">
</form>
