<?php
// Veritabanı bağlantısını dahil et
include 'db_connect.php';

// ingredients tablosundan verileri al
$query = "SELECT name, unit, price_per_unit FROM ingredients";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Durumu</title>
    <link rel="stylesheet" href="stock_status.css">
</head>
<body>
    <!-- Geri Dön Butonu -->
    <div class="back-button">
        <a href="javascript:history.back()">← Geri Dön</a>
    </div>

    <!-- Tablo Konteyner -->
    <div class="table-container">
        <h1>Stok Durumu</h1>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Sıra</th>
                        <th>Malzeme Adı</th>
                        <th>Birim</th>
                        <th>Miktar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $index = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $index++ ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['unit']) ?></td>
                            <td><?= htmlspecialchars($row['price_per_unit']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Hiç stok bilgisi bulunamadı.</p>
        <?php endif; ?>
    </div>

    <?php mysqli_close($conn); // Veritabanı bağlantısını kapat ?>
</body>
</html>
