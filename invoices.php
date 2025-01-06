<?php
// Veritabanı bağlantısını dahil et
include 'db_connect.php';  

// SQL sorgusunu yaz
$query = "SELECT m.dish_name, od.quantity, o.total_price, o.status, o.created_at
          FROM orders o
          JOIN order_details od ON o.order_id = od.order_id
          JOIN menu m ON od.menu_id = m.menu_id";

// Veritabanından fatura verilerini al
$result = mysqli_query($conn, $query);

// Toplam tutar değişkeni
$totalAmount = 0;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faturalar</title>
    <style>
        /* Genel Stil */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh; /* Tam ekran yüksekliği */
            display: flex;
            justify-content: center; /* Yatay merkezleme */
            align-items: center; /* Dikey merkezleme */
            background-color: #f4f4f4;
        }

        /* Geri Dön Butonu */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }

        .back-button a {
            display: inline-block;
            background-color: #333;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-button a:hover {
            background-color: #444;
            transform: scale(1.05);
        }

        /* Fatura Konteyneri */
        .invoice-container {
            width: 90%;
            max-width: 800px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        .invoice-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        /* Tablo */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #333;
            color: white;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        table td a {
            color: #007BFF;
            text-decoration: none;
        }

        table td a:hover {
            text-decoration: underline;
        }

        /* Ok Butonu */
        .arrow-button {
            background-image: url('menu-img/download.png');
            background-size: cover;
            width: 20px;
            height: 20px;
            border: none;
            background-color: transparent;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .arrow-button:hover {
            transform: scale(1.2);
        }

        /* Toplam Tutar Alanı */
        .total-amount {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            text-align: right;
        }
    </style>
    <script>
 function createTxtFile(dish_name, quantity, total_price, created_at) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "create_txt.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Veriyi PHP'ye gönderiyoruz
    xhr.send("dish_name=" + encodeURIComponent(dish_name) + 
             "&quantity=" + encodeURIComponent(quantity) + 
             "&total_price=" + encodeURIComponent(total_price) + 
             "&created_at=" + encodeURIComponent(created_at));

    xhr.onload = function() {
        if (xhr.status == 200) {
            var response = xhr.responseText.trim();
            if (response !== "ERROR") {
                window.location.href = 'download.php?file=' + response;
            } else {
                alert("Dosya oluşturulamadı. Lütfen tekrar deneyin.");
            }
        } else {
            alert("Bir hata oluştu.");
        }
    };
}


    </script>
</head>
<body>
    <!-- Geri Dön Butonu -->
    <div class="back-button">
        <a href="javascript:history.back()">← Geri Dön</a>
    </div>

    <!-- Fatura Tablosu -->
    <div class="invoice-container">
        <h1>Faturalar</h1>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Adet</th>
                        <th>Toplam Fiyat</th>
                        <th>Sipariş Tarihi</th>
                        <th>Doküman</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['dish_name'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= $row['total_price'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <!-- Ok butonu, onclick ile JavaScript fonksiyonunu çağırıyoruz -->
                                <button class="arrow-button" onclick="createTxtFile('<?= $row['dish_name'] ?>', '<?= $row['quantity'] ?>', '<?= $row['total_price'] ?>', '<?= $row['created_at'] ?>')" title="İndir"></button>
                            </td>
                        </tr>
                        <?php $totalAmount += $row['total_price']; ?> <!-- Toplam tutarı güncelle -->
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Toplam Tutar Alanı -->
            <div class="total-amount">
                <p>Toplam Tutar: <?= $totalAmount ?> TL</p>
            </div>

        <?php else: ?>
            <p>Hiç fatura bulunamadı.</p>
        <?php endif; ?>
    </div>

    <?php $conn->close(); // Veritabanı bağlantısını kapat ?>
</body>
</html>
