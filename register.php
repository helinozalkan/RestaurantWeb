<?php
// Veritabanı Bağlantısı
include 'db_connect.php';

// Form Gönderimi Kontrolü
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $experience_years = $_POST['experience_years'] ?? '';
    $specialty = $_POST['specialty'] ?? '';

    // Alanları doğrula
    if (empty($username) || empty($password)) {
        die('Kullanıcı adı ve şifre gerekli!');
    }

    if ($role === 'customer') {
        // Müşteri Kaydı
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die('Geçerli bir e-posta adresi giriniz!');
        }

        $sql = "INSERT INTO Customers (name, email, phone, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql); // $pdo yerine $conn kullanıyoruz
        $stmt->bind_param('ssss', $username, $email, $phone, $password); // Şifre düz metin olarak kaydediliyor
        $stmt->execute();
        echo 'Müşteri kaydı başarılı!';

    } elseif ($role === 'chef') {
        // Aşçı Kaydı
        if (empty($experience_years) || empty($specialty)) {
            die('Aşçı için tecrübe ve uzmanlık alanı gerekli!');
        }

        $sql = "INSERT INTO Chefs (name, experience_years, specialty, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql); // $pdo yerine $conn kullanıyoruz
        $stmt->bind_param('sissss', $username, $experience_years, $specialty, $email, $phone, $password); // Şifre düz metin olarak kaydediliyor
        $stmt->execute();
        echo 'Aşçı kaydı başarılı!';

    } elseif ($role === 'admin') {
        // Yönetici Kaydı
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die('Geçerli bir e-posta adresi giriniz!');
        }

        $sql = "INSERT INTO Admins (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql); // $pdo yerine $conn kullanıyoruz
        $stmt->bind_param('sss', $username, $email, $password); // Şifre düz metin olarak kaydediliyor
        $stmt->execute();
        echo 'Yönetici kaydı başarılı!';
    } else {
        die('Geçersiz rol seçimi!');
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Sistemi</title>
    <link rel="stylesheet" href="kayıt1.css">
</head>
<body>
    <div class="container">
        <h2>Kayıt Formu</h2>
        <form action="register.php" method="POST">
            <div class="role-selector">
                <label><input type="radio" name="role" value="customer" required> Müşteri</label>
                <label><input type="radio" name="role" value="chef"> Aşçı</label>
                <label><input type="radio" name="role" value="admin"> Yönetici</label>
            </div>

            <div class="form-group">
                <label for="username">Kullanıcı Adı</label>
                <input type="text" id="username" name="username" placeholder="Kullanıcı adınızı giriniz" required>
            </div>

            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" placeholder="Şifrenizi giriniz" required>
                <div class="password-hint">En az 8 karakter kullanınız</div>
            </div>

            <div class="form-group customer-fields">
                <label for="email"> Yonetici/Sef E-posta Adresi</label>
                <input type="email" id="email" name="email" placeholder="E-posta adresinizi giriniz">
                <label for="phone">Telefon Numarası</label>
                <input type="tel" id="phone" name="phone" placeholder="Telefon numaranızı giriniz">
            </div>

            <div class="form-group chef-fields">
                <label for="experience_years">Tecrübe Yılı</label>
                <input type="number" id="experience_years" name="experience_years" placeholder="Tecrübe yılını giriniz">
                <label for="specialty">Uzmanlık Alanı</label>
                <input type="text" id="specialty" name="specialty" placeholder="Uzmanlık alanınızı giriniz">
                <label for="email">Musteri E-posta Adresi</label>
                <input type="email" id="email" name="email" placeholder="E-posta adresinizi giriniz">
                <label for="phone">Musteri Telefon Numarası</label>
                <input type="tel" id="phone" name="phone" placeholder="Telefon numaranızı giriniz">
            </div>

            <button type="submit">Kayıt Ol</button>
        </form>
    </div>
</body>
</html>
