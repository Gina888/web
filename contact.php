<?php
include 'header.php'; // 引入共用頭部

$success = $error = '';

// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 處理聯絡表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    if (!empty($name) && !empty($email) && !empty($message)) {
        // 儲存聯絡訊息到資料庫
        $stmt = $mysqli->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $message);
        if ($stmt->execute()) {
            echo "<script>alert('提交成功！我們會在非假日時間兩天內回覆至您的信箱。');</script>";
        } else {
            $error = "送出失敗，請稍後再試。";
        }
        $stmt->close();
    } else {
        $error = "請填寫所有欄位。";
    }
}

$mysqli->close();
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; padding: 30px 0;">
    <div class="container" data-aos="fade-in">
        <h1 class="text-center text-white" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1s;">聯絡我們</h1>
        <p class="text-center text-light" style="font-family: 'Roboto', sans-serif; animation: fadeIn 1.5s;">若您有任何問題或建議，請填寫以下表單，我們將盡快與您聯繫。</p>
        <div class="row justify-content-center mb-4" style="animation: slideInUp 1.8s;">
            <div class="col-md-2">
                <a href="reservation.php" class="btn btn-primary btn-md w-100 mb-3 hvr-grow" style="background: #6a11cb; border-radius: 10px; font-size: 1.2em;">教室預借</a>
            </div>
            <div class="col-md-2">
                <a href="dd.php" class="btn btn-primary btn-md w-100 mb-3 hvr-grow" style="background: #6a11cb; border-radius: 10px; font-size: 1.2em;">學期預借</a>
            </div>
            <div class="col-md-2">
                <a href="records.php" class="btn btn-primary btn-md w-100 mb-3 hvr-grow" style="background: #6a11cb; border-radius: 10px; font-size: 1.2em;">我的預約紀錄</a>
            </div>
            <div class="col-md-2">
                <a href="contact.php" class="btn btn-primary btn-md w-100 mb-3 hvr-grow" style="background: #6a11cb; border-radius: 10px; font-size: 1.2em;">聯絡我們</a>
            </div>
            <div class="col-md-2">
                <a href="availability_check.php" class="btn btn-primary btn-md w-100 mb-3 hvr-grow" style="background: #6a11cb; border-radius: 10px; font-size: 1.2em;">查詢教室</a>
            </div>
        </div>
        <!-- 提示訊息 -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success text-center" role="alert" style="border-radius: 10px; animation: fadeIn 2s;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger text-center" role="alert" style="border-radius: 10px; animation: fadeIn 2s;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- 聯絡表單 -->
        <div class="card shadow-lg mb-4" style="border-radius: 15px; max-width: 600px; margin: 0 auto; animation: slideInUp 2.2s;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4" style="color: #6a11cb;">聯絡表單</h3>
                <form method="POST" action="contact.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">姓名</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="請輸入您的姓名" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">電子郵件</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="請輸入您的電子郵件" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">訊息內容</label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="請輸入您的訊息" required style="border-radius: 10px;"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary hvr-bounce-to-right" style="background: #6a11cb; border: none; border-radius: 10px; font-size: 1.1em;">送出</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideInUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
<?php
include 'footer.php'; // 引入共用頁尾
?>
