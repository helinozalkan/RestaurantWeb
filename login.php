<?php
session_start();
include 'db_connect.php'; // Veritabanı bağlantısını dahil et

// Eğer form gönderildiyse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini alalım ve güvenli hale getirelim
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    // Eğer form alanları boşsa hata mesajı göster
    if (empty($username) || empty($password) || empty($role)) {
        $alertMessage = "Lütfen tüm alanları doldurun.";
    }

    // Role göre tablo ve yönlendirme sayfasını belirleme
    if ($role === 'customer') {
        $table = 'Customers';
        $redirectPage = 'menu.php';
        $idColumn = 'customer_id';
    } elseif ($role === 'chef') {
        $table = 'Chefs';
        $redirectPage = 'chef.php';
        $idColumn = 'chef_id';
    } elseif ($role === 'admin') {
        $table = 'Admins';
        $redirectPage = 'executive.php';
        $idColumn = 'admin_id';
    } else {
        $alertMessage = "Geçersiz rol seçimi!";
    }

    // Veritabanından kullanıcıyı çekme
    if (empty($alertMessage)) {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Eğer kullanıcı bulunduysa
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Şifreyi doğrula
            if ($password === $user['password']) { // Şifre karşılaştırması doğrudan yapılıyor
                // Başarılı giriş, oturum başlat
                $_SESSION['customer_id'] = $user[$idColumn]; // Kullanıcı ID'sini oturuma ekle
                $_SESSION['username'] = $user['name'];
                $_SESSION['role'] = $role;

                // Yönlendirmeyi sağla, giriş başarılı mesajını kaldır
                echo "<script>setTimeout(function(){ window.location.href = '$redirectPage'; }, 1000);</script>";
            } else {
                $alertMessage = "Hatalı şifre!";
            }
        } else {
            $alertMessage = "Kullanıcı bulunamadı!";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Giriş Yap</title>
    <style>
        /* Alert Mesajı Stili */
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }
        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="container">

            <!-- Restoran logosu ekleniyor -->
            <div class="logo-container">
                <img src="menu-img/restaurant-logo.png" alt="Restoran Logo" class="logo">
            </div>

            <!-- Kullanıcıyı kayıt sayfasına yönlendiren buton -->
            <div class="top">
                <button class="account-button" onclick="window.location.href='register.php'">
                    Hesabınız yok mu? Kayıt Ol
                </button>
                <header>Şimdi Giriş Yapın</header>
            </div>

            <!-- Eğer alert mesajı varsa göster -->
            <?php if (!empty($alertMessage)): ?>
                <div class="alert <?php echo (strpos($alertMessage, 'başarılı') !== false) ? 'success' : ''; ?>">
                    <?php echo $alertMessage; ?>
                </div>
            <?php endif; ?>

            <!-- Giriş formu -->
            <form method="POST" action="login.php">
                <div class="input-box">
                    <input type="text" class="input" name="username" placeholder="Kullanıcı Adı" required>
                    <i class="bx bx-user"></i>
                </div>

                <div class="input-box">
                    <input type="password" class="input" name="password" placeholder="Şifre" required>
                    <i class="bx bx-lock-alt"></i>
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
                    <input type="submit" class="submit" value="Giriş Yap">
                </div>

                <div class="two-col">
                    <div class="one">
                    </div>
                    <div class="two">
                        <label for=""><a href="#">Şifremi Unuttum?</a></label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>