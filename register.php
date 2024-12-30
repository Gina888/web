<?php
include 'header.php'; // 引入共用頭部

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    if (!str_ends_with($email, '@example.com')) {
        $error = '僅給本校師生使用，請使用 example.com 的電子郵件進行註冊。';
    } elseif ($password !== $confirmPassword) {
        $error = '密碼與確認密碼不符，請重新輸入。';
    } else {
        // 資料庫連接
        $mysqli = new mysqli('localhost', 'root', '', '期末');

        if ($mysqli->connect_error) {
            die('連接失敗：' . $mysqli->connect_error);
        }

        // 加密密碼
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 新增使用者資料
        $stmt = $mysqli->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, '教師', '啟用')");
        $stmt->bind_param('sss', $name, $email, $hashed_password);

        if ($stmt->execute()) {
            // 註冊成功，跳轉到 login.php
            header('Location: login.php');
            exit;
        } else {
            $error = '註冊失敗，請確認電子郵件是否已被使用。';
        }

        $stmt->close();
        $mysqli->close();
    }
}
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 0; margin: 0;">
    <div class="card" style="width: 100%; max-width: 500px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); border-radius: 15px; margin: 20px; animation: fadeIn 1s ease-in-out;">
        <div class="card-body">
            <h1 class="text-center" style="color: #6a11cb; font-family: 'Roboto', sans-serif; font-weight: bold; animation: slideDown 1s ease-in-out;">註冊</h1>
            <p class="text-center" style="color: #555; font-family: 'Roboto', sans-serif;">請填寫以下資訊以完成註冊。</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert" style="border-radius: 10px; animation: shake 0.5s;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <div class="mb-4">
                    <label for="name" class="form-label" style="color: #333; font-weight: bold;">姓名</label>
                    <input type="text" id="name" name="name" class="form-control" style="border-radius: 10px; animation: fadeInUp 1s;" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label" style="color: #333; font-weight: bold;">電子郵件</label>
                    <input type="email" id="email" name="email" class="form-control" style="border-radius: 10px; animation: fadeInUp 1.2s;" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label" style="color: #333; font-weight: bold;">密碼</label>
                    <input type="password" id="password" name="password" class="form-control" style="border-radius: 10px; animation: fadeInUp 1.4s;" required>
                </div>
                <div class="mb-4">
                    <label for="confirm-password" class="form-label" style="color: #333; font-weight: bold;">確認密碼</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="form-control" style="border-radius: 10px; animation: fadeInUp 1.6s;" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100" style="background: #6a11cb; border: none; border-radius: 10px; font-size: 1.2em; animation: fadeInUp 1.8s;">註冊</button>
                </div>
            </form>
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
