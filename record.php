<?php
// 紀錄頁面 (record.php)
include 'header.php'; // 引入共用頭部

// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 查詢教室預借紀錄
$classroomRecords = $mysqli->query("SELECT r.reservation_id, u.name AS applicant, c.name AS room, r.date, r.start_time, r.end_time, r.purpose, r.status FROM reservations r INNER JOIN users u ON r.user_id = u.user_id INNER JOIN classrooms c ON r.classroom_id = c.classroom_id ORDER BY r.date DESC");

// 查詢學期預借待審核請求
$pendingSemesterRecords = $mysqli->query("SELECT sr.id, u.name AS applicant, sr.classroom, sr.day, sr.start_time, sr.end_time, sr.purpose, sr.status FROM semester_reservations sr INNER JOIN users u ON sr.user_id = u.user_id WHERE sr.status = '待審核' ORDER BY sr.day DESC");

// 查詢學期預借紀錄
$semesterRecords = $mysqli->query("SELECT sr.id, u.name AS applicant, sr.classroom, sr.day, sr.start_time, sr.end_time, sr.purpose, sr.status FROM semester_reservations sr INNER JOIN users u ON sr.user_id = u.user_id ORDER BY sr.day DESC");

$mysqli->close();
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; padding: 30px 0;">
    <div class="container">
        <h1 class="text-center text-white" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1s;">所有紀錄</h1>

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
        <!-- 教室預借紀錄 -->
        <div class="card shadow-lg mb-4" style="border-radius: 15px; animation: slideInUp 1s;">
            <div class="card-body">
                <h3 class="card-title text-center" style="color: #6a11cb;">教室預借紀錄</h3>
                <?php if ($classroomRecords->num_rows > 0): ?>
                    <table class="table table-hover text-center" style="animation: fadeIn 2s;">
                        <thead class="table-primary">
                            <tr>
                                <th>申請者</th>
                                <th>教室</th>
                                <th>日期</th>
                                <th>時間</th>
                                <th>用途</th>
                                <th>狀態</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $classroomRecords->fetch_assoc()): ?>
                                <tr style="transition: background-color 0.3s;">
                                    <td><?= htmlspecialchars($row['applicant']) ?></td>
                                    <td><?= htmlspecialchars($row['room']) ?></td>
                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                    <td><?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></td>
                                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                                    <td><?= htmlspecialchars($row['status']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted" style="animation: fadeIn 2s;">目前沒有教室預借紀錄。</p>
                <?php endif; ?>
            </div>
        </div>

    

        <!-- 學期預借紀錄 -->
        <div class="card shadow-lg" style="border-radius: 15px; animation: slideInUp 1.4s;">
            <div class="card-body">
                <h3 class="card-title text-center" style="color: #6a11cb;">學期預借紀錄</h3>
                <?php if ($semesterRecords->num_rows > 0): ?>
                    <table class="table table-hover text-center" style="animation: fadeIn 2.4s;">
                        <thead class="table-info">
                            <tr>
                                <th>申請者</th>
                                <th>教室</th>
                                <th>星期</th>
                                <th>時間</th>
                                <th>用途</th>
                                <th>狀態</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $semesterRecords->fetch_assoc()): ?>
                                <tr style="transition: background-color 0.3s;">
                                    <td><?= htmlspecialchars($row['applicant']) ?></td>
                                    <td><?= htmlspecialchars($row['classroom']) ?></td>
                                    <td><?= htmlspecialchars($row['day']) ?></td>
                                    <td><?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></td>
                                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                                    <td><?= htmlspecialchars($row['status']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted" style="animation: fadeIn 2.4s;">目前沒有學期預借紀錄。</p>
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
