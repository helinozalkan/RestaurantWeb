<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// POST isteğiyle çalışıp çalışmadığını kontrol edin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen verileri alın
    $dish_name = isset($_POST['dish_name']) ? $_POST['dish_name'] : 'Bilinmiyor';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '0';
    $total_price = isset($_POST['total_price']) ? $_POST['total_price'] : '0.00';
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');

    // İçeriği oluştur
    $content = "Sipariş Bilgileri:\n";
    $content .= "Yemek Adı: $dish_name\n";
    $content .= "Adet: $quantity\n";
    $content .= "Toplam Fiyat: $total_price\n";
    $content .= "Sipariş Tarihi: $created_at\n";

    // Dosya adını ve hedef klasörü ayarla
    $fileName = "order_" . time() . ".txt";
    $directory = "C:\\Program Files\\Ampps\\www\\webPHP\\RestaurantWeb\\"; // Doğru hedef dizin
    $filePath = $directory . $fileName;

    // Hedef dizini kontrol et
    if (!is_dir($directory)) {
        echo "Hedef dizin bulunamadı: $directory";
        exit;
    }

    // Dizine yazma iznini kontrol et
    if (!is_writable($directory)) {
        echo "Hedef dizine yazma izni yok: $directory";
        exit;
    }

    // Dosyayı oluştur ve yaz
    if (file_put_contents($filePath, $content)) {
        echo "Dosya başarıyla oluşturuldu: $filePath";
    } else {
        echo "Dosya oluşturulamadı. Lütfen tekrar deneyin.";
    }
} else {
    echo "Geçersiz istek yöntemi. Sadece POST isteklerine izin verilir.";
}
?>
