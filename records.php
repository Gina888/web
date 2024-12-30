<?php
// 教師介面的預借紀錄頁面 (semester_and_reservations_records.php)
include 'header.php'; // 引入共用頭部

// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 模擬登入的使用者 ID (假設為教師角色)
$userId = 1; // 假設目前登入者的使用者 ID

// 查詢教室預借紀錄
$sqlReservations = "SELECT r.reservation_id, c.name AS room, r.date, r.start_time, r.end_time, r.purpose, r.status 
                    FROM reservations r 
                    INNER JOIN classrooms c ON r.classroom_id = c.classroom_id 
                    WHERE r.user_id = ? 
                    ORDER BY r.date DESC";
$stmtReservations = $mysqli->prepare($sqlReservations);
$stmtReservations->bind_param('i', $userId);
$stmtReservations->execute();
$reservationsResult = $stmtReservations->get_result();
$reservations = [];
if ($reservationsResult) {
    while ($row = $reservationsResult->fetch_assoc()) {
        $reservations[] = $row;
    }
}
$stmtReservations->close();

// 查詢學期預借紀錄
$sqlSemesterRecords = "SELECT sr.id, sr.classroom, sr.day, sr.start_time, sr.end_time, sr.purpose, sr.status 
                       FROM semester_reservations sr 
                       WHERE sr.user_id = ? 
                       ORDER BY sr.created_at DESC";
$stmtSemester = $mysqli->prepare($sqlSemesterRecords);
$stmtSemester->bind_param('i', $userId);
$stmtSemester->execute();
$semesterResult = $stmtSemester->get_result();
$semesterRecords = [];
if ($semesterResult) {
    while ($row = $semesterResult->fetch_assoc()) {
        $semesterRecords[] = $row;
    }
}
$stmtSemester->close();
$mysqli->close();
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; padding: 30px 0;">
    <div class="container" data-aos="fade-in">
        <h1 class="text-center text-white" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1s;">預約紀錄</h1>
        <p class="text-center text-light" style="font-family: 'Roboto', sans-serif; animation: fadeIn 1.5s;">以下是您的教室與學期預借申請紀錄</p>
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

        <!-- 教室預借紀錄 -->
        <div class="card shadow-lg mb-4" style="border-radius: 15px; animation: slideInUp 2s;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4" style="color: #6a11cb;">教室預借紀錄</h3>
                <?php if (!empty($reservations)): ?>
                    <table class="table table-hover text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th>教室</th>
                                <th>日期</th>
                                <th>時間</th>
                                <th>用途</th>
                                <th>狀態</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr style="transition: background-color 0.3s;">
                                    <td><?= htmlspecialchars($reservation['room']) ?></td>
                                    <td><?= htmlspecialchars($reservation['date']) ?></td>
                                    <td><?= htmlspecialchars($reservation['start_time']) ?> - <?= htmlspecialchars($reservation['end_time']) ?></td>
                                    <td><?= htmlspecialchars($reservation['purpose']) ?></td>
                                    <td>
                                        <?php if ($reservation['status'] === '待審核'): ?>
                                            <span class="badge bg-warning text-dark">待審核</span>
                                        <?php elseif ($reservation['status'] === '已批准'): ?>
                                            <span class="badge bg-success">已批准</span>
                                        <?php elseif ($reservation['status'] === '已拒絕'): ?>
                                            <span class="badge bg-danger">已拒絕</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">未知</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted">目前沒有教室預借紀錄。</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- 學期預借紀錄 -->
        <div class="card shadow-lg" style="border-radius: 15px; animation: slideInUp 2.2s;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4" style="color: #6a11cb;">學期預借紀錄</h3>
                <?php if (!empty($semesterRecords)): ?>
                    <table class="table table-hover text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th>教室</th>
                                <th>星期</th>
                                <th>時間</th>
                                <th>用途</th>
                                <th>狀態</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($semesterRecords as $record): ?>
                                <tr style="transition: background-color 0.3s;">
                                    <td><?= htmlspecialchars($record['classroom']) ?></td>
                                    <td><?= htmlspecialchars($record['day']) ?></td>
                                    <td><?= htmlspecialchars($record['start_time']) ?> - <?= htmlspecialchars($record['end_time']) ?></td>
                                    <td><?= htmlspecialchars($record['purpose']) ?></td>
                                    <td>
                                        <?php if ($record['status'] === '待審核'): ?>
                                            <span class="badge bg-warning text-dark">待審核</span>
                                        <?php elseif ($record['status'] === '已批准'): ?>
                                            <span class="badge bg-success">已批准</span>
                                        <?php elseif ($record['status'] === '已拒絕'): ?>
                                            <span class="badge bg-danger">已拒絕</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">未知</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted">目前沒有學期預借紀錄。</p>
                <?php endif; ?>
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
