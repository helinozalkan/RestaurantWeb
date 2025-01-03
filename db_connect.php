<?php
$servername = "localhost";  // Sunucu adresi
$username = "root";         // Veritabanı kullanıcı adı
$password = "mysql";             // Veritabanı şifresi
$dbname = "restaurant_management"; // Kullanılacak veritabanı

// Veritabanı bağlantısını oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}
?>
