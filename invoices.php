<?php
// Veritabanı bağlantısını dahil et
include 'db_connect.php';  

// SQL sorgusunu yaz
$query = "SELECT o.order_id, o.customer_id, o.total_price, o.status, o.created_at, 
                 od.menu_id, od.quantity, od.price 
          FROM orders o
          JOIN order_details od ON o.order_id = od.order_id";

// Veritabanından fatura verilerini al
$result = mysqli_query($conn, $query);
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
    </style>
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
                        <th>Order ID</th>
                        <th>Customer ID</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['order_id'] ?></td>
                            <td><?= $row['customer_id'] ?></td>
                            <td><?= $row['total_price'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td><a href="invoice_details.php?order_id=<?= $row['order_id'] ?>">View Details</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Hiç fatura bulunamadı.</p>
        <?php endif; ?>
    </div>

    <?php $conn->close(); // Veritabanı bağlantısını kapat ?>
</body>
</html>
