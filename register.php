<?php
include 'db_connect.php'; // Veritabanı bağlantısını dahil et

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        echo "<p class='error'>Lütfen tüm alanları doldurun.</p>";
        exit;
    }

    if ($role === 'customer') {
        $table = 'Customers';
    } elseif ($role === 'chef') {
        $table = 'Chefs';
    } elseif ($role === 'admin') {
        $table = 'Admins';
    } else {
        echo "<p class='error'>Geçersiz rol seçimi!</p>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO $table (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "<p class='success'>Kayıt başarılı! Giriş yapmak için <a href='login.php'>buraya tıklayın</a>.</p>";
    } else {
        echo "<p class='error'>Hata oluştu: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Kayıt Ol</title>
</head>
<body>
    <div class="box">
        <div class="container">
            <header>Hesap Oluştur</header>
            <form method="POST" action="register.php">
                <div class="input-box">
                    <input type="text" class="input" name="username" placeholder="Kullanıcı Adı" required>
                </div>
                <div class="input-box">
                    <input type="email" class="input" name="email" placeholder="E-posta" required>
                </div>
                <div class="input-box">
                    <input type="password" class="input" name="password" placeholder="Şifre" required>
                </div>
                <div class="input-box">
                    <select name="role" class="input" required>
                        <option value="" disabled selected>Rol Seçiniz</option>
                        <option value="customer">Müşteri</option>
                        <option value="chef">Aşçı</option>
                        <option value="admin">Yönetici</option>
                    </select>
                </div>
                <div class="input-box">
                    <input type="submit" class="submit" value="Kayıt Ol">
                </div>
                <p class="link">
                    Zaten bir hesabınız var mı? <a href="login.php">Giriş Yap</a>.
                </p>
            </form>
        </div>
    </div>
</body>
</html>
