<?php
include 'db_connect.php'; // Veritabanı bağlantısı
session_start();


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
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
                <img src="menu-img/logo.jpg" alt="Logo">
                <span>Mrs. Kumsal's House</span>
            </div>
        </header>

        <!-- Geri Dön Butonu -->
        <div class="back-button-container">
            <a href="index.php" class="back-button">
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT name, unit FROM ingredients";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['unit']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>Hiçbir malzeme bulunamadı.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
