<?php
include 'db_connect.php'; // Veritabanı bağlantısı

// SQL sorgusu: Tedarikçi bilgilerini al
$query = "SELECT supplier_id, name, contact_info FROM suppliers";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tedarikçiler</title>
    <link rel="stylesheet" href="suppliers.css">
</head>
<body>
    <!-- Geri Dön Butonu -->
    <div class="back-button">
        <a href="javascript:history.back()">← Geri Dön</a>
    </div>

    <!-- Tedarikçi Tablosu -->
    <div class="table-container">
        <h1>Tedarikçi Listesi</h1>
        <table>
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Name</th>
                    <th>Contact Info</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    // Veritabanındaki her tedarikçi için satır ekle
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['supplier_id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['contact_info'] . "</td>";
                        echo "<td>
                                <a href='edit_supplier.php?supplier_id=" . $row['supplier_id'] . "'>Düzenle</a> | 
                                <a href='copy_supplier.php?supplier_id=" . $row['supplier_id'] . "'>Kopyala</a> | 
                                <a href='delete_supplier.php?supplier_id=" . $row['supplier_id'] . "'>Sil</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Hiç tedarikçi bulunamadı.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    // Veritabanı bağlantısını kapat
    $conn->close();
    ?>
</body>
</html>
