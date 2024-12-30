<?php
include 'header.php'; // 引入共用頭部

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    // 資料庫連接
    $mysqli = new mysqli('localhost', 'root', '', '期末');

    // 檢查連接是否成功
    if ($mysqli->connect_error) {
        die('連接失敗：' . $mysqli->connect_error);
    }

    // 檢查是否存在該電子郵件
    $stmt = $mysqli->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // 發送重置密碼的鏈接（模擬）
        $reset_link = "http://example.com/reset_password.php?email=" . urlencode($email);
        // 在這裡可以整合郵件 API 發送該鏈接
        $success = "密碼重置鏈接已發送到您的電子郵件，請檢查收件箱。";
    } else {
        $error = "該電子郵件尚未註冊，請確認後重試。";
    }

    $stmt->close();
    $mysqli->close();
}
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 0; margin: 0;">
    <div class="card" style="width: 100%; max-width: 400px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); border-radius: 15px; margin: 20px; animation: fadeIn 1s ease-in-out;">
        <div class="card-body">
            <h1 class="text-center" style="color: #6a11cb; font-family: 'Roboto', sans-serif; font-weight: bold; animation: slideDown 1s ease-in-out;">忘記密碼</h1>
            <p class="text-center" style="color: #555; font-family: 'Roboto', sans-serif;">請輸入您的電子郵件以接收密碼重置鏈接。</p>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert" style="border-radius: 10px; animation: fadeIn 1.2s;">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php elseif (!empty($error)): ?>
                <div class="alert alert-danger" role="alert" style="border-radius: 10px; animation: shake 0.5s;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="forgot_password.php">
                <div class="mb-4">
                    <label for="email" class="form-label" style="color: #333; font-weight: bold;">電子郵件</label>
                    <input type="email" id="email" name="email" class="form-control" style="border-radius: 10px; animation: fadeInUp 1s;" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100" style="background: #6a11cb; border: none; border-radius: 10px; font-size: 1.2em; animation: fadeInUp 1.2s;">發送重置鏈接</button>
                </div>
            </form>
            <div class="text-center mt-3" style="animation: fadeIn 1.4s;">
                <a href="login.php" style="color: #6a11cb; font-weight: bold; text-decoration: none;">返回登入</a>
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
