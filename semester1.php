<?php
// 學期區間管理頁面 (semester1.php)
include 'header.php'; // 引入共用頭部

// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 查詢目前學期資料
$semesterResult = $mysqli->query("SELECT semester_id, start_date, end_date FROM semester ORDER BY semester_id DESC LIMIT 1");
$semester = $semesterResult->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';

    if ($startDate && $endDate) {
        $stmt = $mysqli->prepare("INSERT INTO semester (start_date, end_date) VALUES (?, ?)");
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $stmt->close();

        header('Location: semester1.php'); // 重新加載頁面
        exit;
    } else {
        $error = '請輸入完整的開始與結束日期。';
    }
}

$mysqli->close();
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; padding: 30px 0;">
    <div class="container">
        <h1 class="text-center text-white" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1s;">學期區間管理</h1>

        <!-- 快捷操作區 -->
        <div class="d-flex justify-content-center mt-4" style="animation: fadeIn 1.5s;">
    <a href="aa.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">返回管理者首頁</a>
    <a href="pending_requests.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">查看待審核請求</a>
    <a href="record.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">查看所有紀錄</a>
    <a href="bb.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">帳號管理</a>
    <a href="semester1.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">設定學期區間</a>
    <a href="contact_reply.php" class="btn btn-light btn-lg" style="border-radius: 15px;">回覆訊息</a>
</div>
        <p></p>

        <!-- 目前學期區間 -->
        <div class="card shadow-lg mb-4" style="border-radius: 15px; animation: slideInUp 1s;">
            <div class="card-body">
                <h3 class="card-title text-center" style="color: #6a11cb; font-weight: bold;">目前學期</h3>
                <?php if ($semester): ?>
                    <p class="text-center text-muted">開始日期：<?= htmlspecialchars($semester['start_date']) ?> | 結束日期：<?= htmlspecialchars($semester['end_date']) ?></p>
                <?php else: ?>
                    <p class="text-center text-muted">目前尚未設定學期區間。</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- 新增學期區間 -->
        <div class="card shadow-lg" style="border-radius: 15px; animation: slideInUp 1.2s;">
            <div class="card-body">
                <h3 class="card-title text-center" style="color: #6a11cb; font-weight: bold;">新增學期區間</h3>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center" role="alert" style="animation: fadeIn 2s;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="semester1.php">
                    <div class="mb-3">
                        <label for="start_date" class="form-label" style="color: #333; font-weight: bold;">開始日期</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" style="border-radius: 10px;" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label" style="color: #333; font-weight: bold;">結束日期</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" style="border-radius: 10px;" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100" style="background: #6a11cb; border: none; border-radius: 10px; font-size: 1.2em; animation: fadeIn 2.5s;">新增</button>
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