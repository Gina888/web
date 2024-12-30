<?php
include 'header.php'; // 引入共用頭部

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // 資料庫連接
    $mysqli = new mysqli('localhost', 'root', '', '期末');

    // 檢查連接是否成功
    if ($mysqli->connect_error) {
        die('連接失敗：' . $mysqli->connect_error);
    }

    // 修正 SQL 語法並使用 ? 作為參數佔位符
    $stmt = $mysqli->prepare("SELECT role, password FROM users WHERE email = ?");
    $stmt->bind_param('s', $email); // 正確綁定參數
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($role, $hashed_password);
        $stmt->fetch();

        // 驗證密碼（明文比較，若已加密請使用 password_verify）
        if ($password === $hashed_password) {
            if ($role === '管理者') {
                header('Location: aa.php'); // 跳轉到管理者頁面
            } else {
                header('Location: dashboard.php'); // 跳轉到一般使用者頁面
            }
            exit;
        } else {
            $error = '密碼錯誤，請再試一次。';
        }
    } else {
        $error = '帳號不存在，請檢查您的電子郵件。';
    }

    $stmt->close();
    $mysqli->close();
}
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 0; margin: 0;">
    <div class="card" style="width: 100%; max-width: 400px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); border-radius: 15px; margin: 20px; animation: fadeIn 1s ease-in-out;">
        <div class="card-body">
            <h1 class="text-center" style="color: #6a11cb; font-family: 'Roboto', sans-serif; font-weight: bold; animation: slideDown 1s ease-in-out;">登入</h1>
            <p class="text-center" style="color: #555; font-family: 'Roboto', sans-serif;">請輸入您的帳號密碼以登入系統。</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert" style="border-radius: 10px; animation: shake 0.5s;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="mb-4">
                    <label for="email" class="form-label" style="color: #333; font-weight: bold;">電子郵件</label>
                    <input type="email" id="email" name="email" class="form-control" style="border-radius: 10px; animation: fadeInUp 1s;" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label" style="color: #333; font-weight: bold;">密碼</label>
                    <input type="password" id="password" name="password" class="form-control" style="border-radius: 10px; animation: fadeInUp 1.2s;" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100" style="background: #6a11cb; border: none; border-radius: 10px; font-size: 1.2em; animation: fadeInUp 1.4s;">登入</button>
                </div>
            </form>
            <div class="text-center mt-3" style="animation: fadeIn 1.6s;">
                <a href="register.php" style="color: #6a11cb; font-weight: bold; text-decoration: none;">註冊</a>
                <span style="margin: 0 10px; color: #555;">|</span>
                <a href="forgot_password.php" style="color: #6a11cb; font-weight: bold; text-decoration: none;">忘記密碼</a>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25%, 75% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
}
</style>

<?php
include 'footer.php'; // 引入共用頁尾
?>
