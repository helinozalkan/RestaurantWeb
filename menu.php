<?php
include 'db_connect.php';
session_start();

// Kategori ID kontrolü
$category_id = isset($_GET['category']) ? intval($_GET['category']) : null;

// Menü sorgusu
$sql = $category_id 
    ? "SELECT menu_id, dish_name, description, price, image FROM Menu WHERE category_id = ?"
    : "SELECT menu_id, dish_name, description, price, image FROM Menu";

$stmt = $conn->prepare($sql);
if ($category_id) $stmt->bind_param('i', $category_id);
$stmt->execute();
$result = $stmt->get_result();

// Sipariş veritabanı işlemi
if (isset($_POST['order']) && isset($_SESSION['customer_id'])) {
    $orderItems = $_POST['order'];
    $userId = $_SESSION['customer_id'];
    $totalPrice = 0;

    // Siparişi veritabanına ekleyelim
    $conn->begin_transaction();
    try {
        // Siparişi ekleyelim
        $stmt = $conn->prepare("INSERT INTO Orders (customer_id, total_price) VALUES (?, ?)");
        $stmt->bind_param('id', $userId, $totalPrice);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        // Sipariş ürünlerini ekleyelim
        foreach ($orderItems as $item) {
            $stmt = $conn->prepare("INSERT INTO Order_Items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iiid', $orderId, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
            $totalPrice += $item['quantity'] * $item['price'];
        }

        // Toplam fiyatı güncelleyelim
        $stmt = $conn->prepare("UPDATE Orders SET total_price = ? WHERE order_id = ?");
        $stmt->bind_param('di', $totalPrice, $orderId);
        $stmt->execute();

        // İşlemi başarılı şekilde sonlandıralım
        $conn->commit();

        echo json_encode(['status' => 'success', 'message' => 'Siparişiniz başarıyla alınmıştır!']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Sipariş alınırken bir hata oluştu.']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menü</title>
    <link rel="stylesheet" href="restaurant.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="top-bar">
        <div class="logo-area">
            <img src="menu-img/logo.png" alt="Logo" class="logo">
            <h1>Mrs. Kumsal's House</h1>
        </div>
        <div class="search-area">
            <input type="text" placeholder="Yemek ara..." class="search-input" id="search-input">
        </div>
        <div class="profile-area">
            <img src="menu-img/profile.png" alt="Profil" class="profile-img">
        </div>
    </div>

    <div class="sidebar">
        <h2>Restaurant Menü</h2>
        <ul>
            <li><a href="menu.php">Tüm Ürünler</a></li>
            <li><a href="menu.php?category=1">Burger</a></li>
            <li><a href="menu.php?category=2">Tatlı</a></li>
            <li><a href="menu.php?category=3">İçecek</a></li>
            <li><a href="menu.php?category=4">Atıştırmalık</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="menu-container" id="menu-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="menu-item">
                        <img src="menu-img/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['dish_name']); ?>" class="menu-img">
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($row['dish_name']); ?></h3>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p><strong>Fiyat:</strong> <?php echo htmlspecialchars($row['price']); ?> TL</p>
                            <div class="cart-controls">
                                <button class="decrease">−</button>
                                <span class="quantity">1</span>
                                <button class="increase">+</button>
                                <button class="add-to-cart" 
                                    data-id="<?php echo htmlspecialchars($row['menu_id']); ?>" 
                                    data-name="<?php echo htmlspecialchars($row['dish_name']); ?>" 
                                    data-price="<?php echo htmlspecialchars($row['price']); ?>">Sepete Ekle</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Bu kategoride henüz yemek bulunmamaktadır.</p>
            <?php endif; ?>
        </div>

        <div class="cart-container">
            <h2>Sepetiniz</h2>
            <ul id="cart-items"></ul>
            <p><strong>Toplam Fiyat:</strong> <span id="total-price">0</span> TL</p>
            <button id="complete-order">Siparişi Tamamla</button>
        </div>
    </div>

    <script>
        let cart = [];

        $(document).ready(function () {
            $('.increase').click(function () {
                const quantityElement = $(this).siblings('.quantity');
                let quantity = parseInt(quantityElement.text());
                quantityElement.text(++quantity);
            });

            $('.decrease').click(function () {
                const quantityElement = $(this).siblings('.quantity');
                let quantity = parseInt(quantityElement.text());
                if (quantity > 1) quantityElement.text(--quantity);
            });

            $('.add-to-cart').click(function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const price = parseFloat($(this).data('price'));
                const quantity = parseInt($(this).siblings('.quantity').text());

                const item = cart.find(i => i.id === id);
                if (item) {
                    item.quantity += quantity;
                } else {
                    cart.push({ id, name, price, quantity });
                }
                updateCart();
            });

            function updateCart() {
                let total = 0;
                $('#cart-items').empty();
                cart.forEach(item => {
                    total += item.price * item.quantity;
                    $('#cart-items').append(
                        `<li>${item.name} x${item.quantity} - ${item.price * item.quantity} TL
                        <button class="remove-item" data-id="${item.id}">Sil</button></li>`
                    );
                });
                $('#total-price').text(total.toFixed(2));
            }

            $(document).on('click', '.remove-item', function () {
                const id = $(this).data('id');
                cart = cart.filter(item => item.id !== id);
                updateCart();
            });
            $('#complete-order').click(function () {
    if (cart.length === 0) {
        alert("Sepetiniz boş. Lütfen ürün ekleyin.");
        return;
    }

    const orderData = {
        cart: JSON.stringify(cart),
        total_price: $('#total-price').text()
    };

    console.log("Sipariş verisi gönderiliyor:", orderData);  // Debugging: Sipariş verisi
    
    $.ajax({
        url: 'complete-order.php',
        method: 'POST',
        data: {
            cart: JSON.stringify(cart),
            total_price: $('#total-price').text()
        },
        contentType: 'application/x-www-form-urlencoded',  // Bu satır eklendi
        success: function(response) {
            console.log("Başarıyla sipariş gönderildi:", response);  // Debugging: Başarı durumu
            try {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    alert(result.message);
                    cart = []; // Sepeti sıfırla
                    updateCart(); // Sepet güncelle
                } else {
                    alert("Sipariş sırasında bir hata oluştu.");
                }
            } catch (e) {
                console.log("JSON Hatası: ", e);  // JSON parse hatalarını debug et
                alert("Bir hata oluştu. Lütfen tekrar deneyin.");
            }
        },
        error: function(xhr, status, error) {
            console.log("AJAX Hatası:", status, error);  // Debugging: Hata mesajı
            alert("Bir hata oluştu. Lütfen tekrar deneyin.");
        }
    });
});
});


    </script>
</body>
</html>
