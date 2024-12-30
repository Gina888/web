<?php
include 'header.php'; // 引入共用頭部

$success = $error = '';

// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 處理預約表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = 1; // 假設已登入教師的 user_id 是 1
    $classroom_id = $_POST['classroom_id'] ?? '';
    $date = $_POST['date'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $purpose = $_POST['purpose'] ?? '';

    if ($classroom_id && $date && $start_time && $end_time && $purpose) {
        // 檢查是否有時間重疊的預約
        $stmt = $mysqli->prepare("SELECT 1 FROM reservations WHERE classroom_id = ? AND date = ? AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?) OR (start_time >= ? AND end_time <= ?))");
        $stmt->bind_param('isssssss', $classroom_id, $date, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "預約時間與其他預約衝突，請選擇其他時間。";
        } else {
            // 插入新的預約記錄
            $stmt = $mysqli->prepare("INSERT INTO reservations (user_id, classroom_id, date, start_time, end_time, purpose, status) VALUES (?, ?, ?, ?, ?, ?, '待審核')");
            $stmt->bind_param('iissss', $user_id, $classroom_id, $date, $start_time, $end_time, $purpose);
            if ($stmt->execute()) {
                $success = "預約已成功提交，待管理員審核！";
            } else {
                $error = "提交失敗，請稍後再試。";
            }
        }
        $stmt->close();
    } else {
        $error = "所有欄位皆為必填，請重新檢查。";
    }
}

// 查詢教室列表
$classroomsResult = $mysqli->query("SELECT classroom_id, name FROM classrooms");
$classrooms = [];
if ($classroomsResult) {
    while ($row = $classroomsResult->fetch_assoc()) {
        $classrooms[] = $row;
    }
}
$mysqli->close();
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; padding: 30px 0;">
    <div class="container" data-aos="fade-in">
        <h1 class="text-white text-center" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1.5s;">教室預約</h1>
        <p class="text-center text-warning" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 2s;">注意：一旦提交預約後，無法取消，需至學務處處理。</p>

        <!-- 新增超連結 -->
        <div class="text-center mb-4">
            <a href="availability_check.php" class="btn btn-secondary hvr-bounce-to-right mb-3" style="border-radius: 10px; font-size: 1.1em; background: #2575fc;">查詢教室空閒時間</a>
        </div>

        <div class="row text-center mb-4" style="animation: slideInUp 1.8s;">
            <div class="col-md-3">
                <a href="reservation.php" class="btn btn-primary w-100 mb-3 hvr-grow" style="background: #6a11cb; border-radius: 10px;">教室預借</a>
            </div>
            <div class="col-md-3">
                <a href="dd.php" class="btn btn-primary w-100 mb-3 hvr-grow" style="background: #6a11cb; border-radius: 10px;">學期預借</a>
            </div>
            <div class="col-md-3">
                <a href="records.php" class="btn btn-primary w-100 mb-3 hvr-grow" style="background: #6a11cb; border-radius: 10px;">我的預約紀錄</a>
            </div>
            <div class="col-md-3">
                <a href="contact.php" class="btn btn-primary w-100 mb-3 hvr-grow" style="background: #6a11cb; border-radius: 10px;">聯絡我們</a>
            </div>
        </div>

        <!-- 成功或錯誤提示 -->
        <?php if ($success): ?>
            <div class="alert alert-success text-center" style="border-radius: 10px; animation: fadeIn 2s;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger text-center" style="border-radius: 10px; animation: fadeIn 2s;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- 預約表單 -->
        <div class="card shadow-lg" style="border-radius: 15px; max-width: 600px; margin: 0 auto; animation: slideInUp 2.2s;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4" style="color: #6a11cb;">教室預約表單</h3>
                <form method="POST" action="reservation.php">
                    <div class="mb-3">
                        <label for="classroom" class="form-label">教室名稱</label>
                        <select name="classroom_id" id="classroom" class="form-control" required>
                            <option value="">請選擇教室</option>
                            <?php foreach ($classrooms as $classroom): ?>
                                <option value="<?= $classroom['classroom_id'] ?>"><?= htmlspecialchars($classroom['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">日期</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_time" class="form-label">開始時間</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="form-label">結束時間</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="purpose" class="form-label">用途</label>
                        <textarea name="purpose" id="purpose" rows="3" class="form-control" placeholder="請輸入預約用途" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary hvr-bounce-to-right" style="border-radius: 10px; font-size: 1.1em;">提交預約</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 教室介紹區塊 -->
        <div class="mt-5">
            <h2 class="text-white text-center mb-4">教室介紹</h2>
            <div class="row">
                <?php for ($i = 1; $i <= 8; $i++): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow" style="border-radius: 10px;">
                            <div class="card-body text-center">
                                <h5 class="card-title">教室 <?= $i ?></h5>
                                <p class="card-text">這是教室 <?= $i ?> 的簡要描述，提供優良的學習環境和設備。</p>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
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

<?php include 'footer.php'; ?>
