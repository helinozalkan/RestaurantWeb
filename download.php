<?php
if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']); // Dosya adını temizle
    $filePath = "C:\\Program Files\\Ampps\\www\\webPHP\\RestaurantWeb\\" . $fileName; // Doğru tam dosya yolu

    // Dosyanın mevcut olup olmadığını kontrol et
    if (file_exists($filePath)) {
        // Dosya türünü belirleyin
        header('Content-Type: text/plain; charset=utf-8'); // UTF-8 için charset ekle
        header('Content-Disposition: attachment; filename="' . $fileName . '"'); // İndirme başlığı
        header('Content-Length: ' . filesize($filePath)); // Dosya boyutunu belirle

        // Dosyayı okuyun ve tarayıcıya gönderin
        readfile($filePath);
        exit;
    } else {
        echo "Dosya bulunamadı: $filePath"; // Hata mesajı
    }
} else {
    echo "Geçersiz istek!"; // Geçersiz istek durumunda hata mesajı
}
?>
