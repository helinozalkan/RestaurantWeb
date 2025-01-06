<?php
include 'db_connect.php'; // Veritabanı bağlantısı
session_start();

$category_id = isset($_GET['category']) ? intval($_GET['category']) : null;

$sql = $category_id 
    ? "SELECT menu_id, dish_name, description, price, image FROM Menu WHERE category_id = $category_id"
    : "SELECT menu_id, dish_name, description, price, image FROM Menu";

$result = $conn->query($sql);
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
    <!-- Üst Menü -->
    <div class="top-bar">
        <div class="logo-area">
            <img src="menu-img/logo.jpg" alt="Logo" class="logo">
            <h1>Mrs. Kumsal's House</h1>
        </div>
        <div class="search-area">
            <input type="text" placeholder="Yemek ara..." class="search-input">
        </div>
        <div class="profile-area">
            <img src="menu-img/profile.png" alt="Profil" class="profile-img">
        </div>
    </div>

    <!-- Sidebar -->
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

    <!-- Menü ve Sepet -->
    <div class="main-content">
        <div class="menu-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="menu-item">
                        <img src="menu-img/<?php echo $row['image']; ?>" alt="<?php echo $row['dish_name']; ?>" class="menu-img">
                        <div class="item-info">
                            <h3><?php echo $row['dish_name']; ?></h3>
                            <p><?php echo $row['description']; ?></p>
                            <p><strong>Fiyat:</strong> <?php echo $row['price']; ?> TL</p>
                            <div class="cart-controls">
                                <button class="decrease">−</button>
                                <span class="quantity">1</span>
                                <button class="increase">+</button>
                                <button class="add-to-cart" data-id="<?php echo $row['menu_id']; ?>" data-name="<?php echo $row['dish_name']; ?>" data-price="<?php echo $row['price']; ?>">Sepete Ekle</button>
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
            // Miktar artırma
            $('.increase').click(function () {
                const quantityElement = $(this).siblings('.quantity');
                let quantity = parseInt(quantityElement.text());
                quantityElement.text(++quantity);
            });

            // Miktar azaltma
            $('.decrease').click(function () {
                const quantityElement = $(this).siblings('.quantity');
                let quantity = parseInt(quantityElement.text());
                if (quantity > 1) quantityElement.text(--quantity);
            });

            // Sepete ekle butonuna tıklama
            $('.add-to-cart').click(function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const price = parseFloat($(this).data('price'));
                const quantity = parseInt($(this).siblings('.quantity').text());

                const item = cart.find(i => i.id === id);
                if (item) {
                    item.quantity += quantity; // Aynı üründen eklenirse miktarı artır
                } else {
                    cart.push({ id, name, price, quantity }); // Yeni ürün ekle
                }
                updateCart(); // Sepeti güncelle
            });

            // Sepet güncelleme fonksiyonu
            function updateCart() {
                let total = 0;
                $('#cart-items').empty();
                cart.forEach(item => {
                    total += item.price * item.quantity;
                    $('#cart-items').append(
                        `<li>
                            ${item.name} x${item.quantity} - ${item.price * item.quantity} TL
                            <button class="remove-item" data-id="${item.id}">Sil</button>
                        </li>`
                    );
                });
                $('#total-price').text(total.toFixed(2)); // Toplam fiyatı güncelle
            }

            // Sepet öğesi silme
            $(document).on('click', '.remove-item', function () {
                const id = $(this).data('id');
                cart = cart.filter(item => item.id !== id); // Silinen öğeyi sepetten çıkar
                updateCart(); // Sepeti güncelle
            });

            // Siparişi tamamlama
            $('#complete-order').click(function () {
                if (cart.length === 0) {
                    alert('Sepetiniz boş!');
                    return;
                }

                $.ajax({
                    url: 'complete_order.php',
                    method: 'POST',
                    data: { cart: JSON.stringify(cart) },
                    success: function (response) {
                        alert('Siparişiniz başarıyla kaydedildi!');
                        cart = []; // Sepeti sıfırla
                        updateCart(); // Sepeti güncelle
                    },
                    error: function () {
                        alert('Sipariş sırasında bir hata oluştu.');
                    }
                });
            });
        });

    </script>
</body>
</html>

<?php $conn->close(); ?>
