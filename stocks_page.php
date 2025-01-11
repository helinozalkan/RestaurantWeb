<?php
include 'db_connect.php'; // Veritabanı bağlantısı
session_start();

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}

// Miktar güncelleme işlemi
if (isset($_POST['ingredient_id']) && isset($_POST['quantity'])) {
    $ingredient_id = $_POST['ingredient_id'];
    $quantity = $_POST['quantity'];

    // Quantity değeri 1'den küçük olamaz
    if ($quantity < 1) {
        $quantity = 1;
    }

    // Veritabanında güncelleme işlemi
    $sql = "UPDATE stock SET quantity = ? WHERE ingredient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $ingredient_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Güncel Stok</title>
    <link rel="stylesheet" href="improved_stocks_style.css">
</head>
<body>
    <div class="container">
        <!-- Header: Logo -->
        <header class="header">
            <div class="logo">
                <img src="menu-img/logo.png" alt="Logo">
                <span>Mrs. Kumsal's House</span>
            </div>
            <input type="text" class="search-bar" placeholder="Malzeme ara...">
        </header>

        <!-- Geri Dön Butonu -->
        <div class="back-button-container">
            <a href="chef.php" class="back-button">
                <i class="fa fa-arrow-left"></i> Geri Dön
            </a>
        </div>

        <!-- Malzemeler Başlığı -->
        <div class="title-container">
            <h1>Malzemeler</h1>
        </div>

        <!-- Tablo -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Malzeme Adı</th>
                        <th>Birim</th>
                        <th>Miktar</th> <!-- Yeni sütun -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // ingredient ve stock tablosunu birleştiriyoruz
                    $sql = "
                        SELECT i.name, i.unit, s.quantity, i.ingredient_id
                        FROM ingredients i
                        JOIN stock s ON i.ingredient_id = s.ingredient_id
                    ";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['unit']}</td>
                                <td>
                                    <button class='decrease' data-ingredient-id='{$row['ingredient_id']}'>-</button>
                                    <span class='quantity'>{$row['quantity']}</span>
                                    <button class='increase' data-ingredient-id='{$row['ingredient_id']}'>+</button>
                                </td> <!-- Miktar verisi -->
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Hiçbir malzeme bulunamadı.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Miktar artırma
        $('.increase').click(function () {
            const ingredientId = $(this).data('ingredient-id');
            const quantityElement = $(this).siblings('.quantity');
            let quantity = parseInt(quantityElement.text());
            quantityElement.text(++quantity);

            // Veritabanını güncelle
            updateQuantity(ingredientId, quantity);
        });

        // Miktar azaltma
        $('.decrease').click(function () {
            const ingredientId = $(this).data('ingredient-id');
            const quantityElement = $(this).siblings('.quantity');
            let quantity = parseInt(quantityElement.text());
            if (quantity > 1) {
                quantityElement.text(--quantity);

                // Veritabanını güncelle
                updateQuantity(ingredientId, quantity);
            }
        });

        // Veritabanını güncelleyen fonksiyon
        function updateQuantity(ingredientId, quantity) {
            $.post('index.php', {
                ingredient_id: ingredientId,
                quantity: quantity
            }, function(response) {
                if (response === 'success') {
                    console.log('Miktar başarıyla güncellendi.');
                } else {
                    console.log('Hata: Miktar güncellenemedi.');
                }
            });
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
