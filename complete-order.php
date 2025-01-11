<?php
include 'db_connect.php';
session_start();

// Oturum kontrolü
if (!isset($_SESSION['customer_id']) || empty($_SESSION['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz kullanıcı! Lütfen giriş yapın.']);
    exit;
}

// Sepet ve toplam fiyat verisini al
$orderData = json_decode($_POST['cart'], true); // Sepet verisi
$totalPrice = $_POST['total_price']; // Toplam fiyat

if (empty($orderData)) {
    echo json_encode(['status' => 'error', 'message' => 'Sepet boş!']);
    exit;
}

// Sipariş verisini veritabanına ekleme işlemi
$user_id = $_SESSION['customer_id']; // Kullanıcı ID'si, oturum açmışsa

try {
    // Sipariş ekleme saklı yordamını çağır
    $stmt = $conn->prepare("CALL AddOrder(?, ?, @order_id)");
    $stmt->bind_param('id', $user_id, $totalPrice);
    $stmt->execute();

    // Yeni siparişin ID'sini almak için çıktı parametresini al
    $stmt = $conn->prepare("SELECT @order_id AS order_id");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $orderId = $row['order_id'];

    // Siparişteki her bir öğeyi ekle
    $stmt = $conn->prepare("CALL AddOrderItem(?, ?, ?, ?)");
    foreach ($orderData as $item) {
        $stmt->bind_param('iiid', $orderId, $item['id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }

    echo json_encode(['status' => 'success', 'message' => 'Sipariş başarıyla verildi!']);
} catch (Exception $e) {
    // Hata durumunda
    echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
}
?>
