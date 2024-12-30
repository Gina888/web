<?php
include 'header.php'; // 引入共用頭部

// 初始化變數
$error = $availableClassrooms = [];

// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 查詢教室可用性
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';

    if ($date && $start_time && $end_time) {
        $stmt = $mysqli->prepare("SELECT classroom_id, name FROM classrooms WHERE classroom_id NOT IN (SELECT classroom_id FROM reservations WHERE date = ? AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?) OR (start_time >= ? AND end_time <= ?)))");
        $stmt->bind_param('sssssss', $date, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $availableClassrooms[] = $row;
        }
        $stmt->close();
    } else {
        $error = "請完整填寫所有欄位。";
    }
}

// 查詢所有教室
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
        <h1 class="text-center text-white" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1s;">查詢教室</h1>
        <p class="text-center text-warning" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1.5s;">查詢教室空閒時間</p>
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


        <!-- 搜尋區塊 -->
        <div class="card shadow-lg mb-4" style="border-radius: 15px; max-width: 600px; margin: 0 auto;">
            <div class="card-body">
                <form method="POST" action="availability_check.php">
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
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px;">查詢</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 結果展示 -->
        <?php if ($error): ?>
            <div class="alert alert-danger text-center" style="border-radius: 10px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($classrooms as $classroom): ?>
                <div class="col-md-3 mb-4">
                    <div class="card shadow" style="border-radius: 10px; <?= in_array($classroom, $availableClassrooms) ? 'border: 2px solid green;' : 'opacity: 0.6;' ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title">教室 <?= htmlspecialchars($classroom['name']) ?></h5>
                            <p class="card-text">這是教室 <?= htmlspecialchars($classroom['name']) ?> 的詳細描述。</p>
                            <?php if (in_array($classroom, $availableClassrooms)): ?>
                                <p class="text-success">可借閱</p>
                            <?php else: ?>
                                <p class="text-danger">已被預約</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
