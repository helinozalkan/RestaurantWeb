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
$status = 'pending'; // Sipariş durumu (başlangıçta 'pending')

try {
    // Siparişi ekle (created_at kullanıldı)
    $orderQuery = "INSERT INTO Orders (customer_id, total_price, status, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param('dss', $user_id, $totalPrice, $status);

    if (!$stmt->execute()) {
        throw new Exception('Sipariş eklenirken hata oluştu: ' . $stmt->error);
    }

    // Yeni siparişin ID'sini al
    $orderId = $stmt->insert_id;

    // Siparişteki her bir öğeyi ekle
    $orderItemQuery = "INSERT INTO Order_Items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($orderItemQuery);

    foreach ($orderData as $item) {
        $stmt->bind_param('iiid', $orderId, $item['id'], $item['quantity'], $item['price']);

        if (!$stmt->execute()) {
            throw new Exception('Ürün eklenirken hata oluştu: ' . $stmt->error);
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Sipariş başarıyla verildi!']);
} catch (Exception $e) {
    // Hata durumunda
    echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
}
?>
