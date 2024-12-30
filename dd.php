<?php
include 'header.php'; // 引入共用頭部

$success = $error = '';

// 資料庫連接
// 修改資料庫連線
$mysqli = new mysqli('localhost', 'root', '', '期末');

if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 處理學期預借表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = 1; // 假設已登入教師的 user_id
    $classroom = $_POST['classroom'] ?? '';
    $day = $_POST['day'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $purpose = $_POST['purpose'] ?? '';
    $repeat_weekly = isset($_POST['repeat_weekly']) ? 1 : 0;

    if (!empty($classroom) && !empty($day) && !empty($start_time) && !empty($end_time) && !empty($purpose)) {
        $stmt = $mysqli->prepare("INSERT INTO semester_reservations (user_id, classroom, day, start_time, end_time, purpose, repeat_weekly) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssssi', $user_id, $classroom, $day, $start_time, $end_time, $purpose, $repeat_weekly);
        if ($stmt->execute()) {
            $success = "學期預借已成功提交！";
        } else {
            $error = "提交失敗，請稍後再試。";
        }
        $stmt->close();
    } else {
        $error = "所有欄位皆為必填，請重新檢查。";
    }
}

// 查詢學期預借紀錄
$sqlSemesterReservations = "SELECT classroom, day, start_time, end_time, purpose, repeat_weekly 
                            FROM semester_reservations 
                            WHERE user_id = ?";
$stmt = $mysqli->prepare($sqlSemesterReservations);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$semesterReservations = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $semesterReservations[] = $row;
    }
}
$stmt->close();
$mysqli->close();
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; padding: 30px 0;">
    <div class="container" data-aos="fade-in">
        <h1 class="text-center text-white" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1s;">學期預借</h1>
        <p class="text-center text-warning" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1.5s;">注意：一旦提交預約後，無法取消，需至學務處處理。</p>
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
        <?php if ($success): ?>
            <div class="alert alert-success text-center" role="alert" style="border-radius: 10px; animation: fadeIn 2s;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger text-center" role="alert" style="border-radius: 10px; animation: fadeIn 2s;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- 學期預借表單 -->
        <div class="card shadow-lg mb-4" style="border-radius: 15px; max-width: 600px; margin: 0 auto; animation: slideInUp 2.2s;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4" style="color: #6a11cb;">學期預借表單</h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="classroom" class="form-label">教室名稱</label>
                        <select class="form-select" id="classroom" name="classroom" required>
                            <option value="" disabled selected>請選擇教室</option>
                            <option value="LM200">LM200</option>
                            <option value="SL246">SL246</option>
                            <option value="SL201">SL201</option>
                            <option value="SL245">SL245</option>
                            <option value="LM202">LM202</option>
                            <option value="LM202">SL200-1</option>
                            <option value="LM202">SL200-3</option>
                            <option value="LM202">SL471</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="day" class="form-label">星期</label>
                        <select class="form-select" id="day" name="day" required>
                            <option value="" disabled selected>請選擇星期</option>
                            <option value="星期一">星期一</option>
                            <option value="星期二">星期二</option>
                            <option value="星期三">星期三</option>
                            <option value="星期四">星期四</option>
                            <option value="星期五">星期五</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">開始時間</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">結束時間</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="purpose" class="form-label">用途</label>
                        <textarea class="form-control" id="purpose" name="purpose" rows="3" required></textarea>
                    </div>
                  
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary hvr-bounce-to-right" style="border-radius: 10px; font-size: 1.1em;">提交</button>
                    </div>
                </form>
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
