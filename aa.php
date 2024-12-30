
<?php
// 管理者首頁 (aa.php)
include 'header.php'; // 引入共用頭部

// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 查詢統計數據
$totalUsers = $mysqli->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$totalReservations = $mysqli->query("SELECT COUNT(*) AS count FROM reservations")->fetch_assoc()['count'];
$totalPendingRequests = $mysqli->query("SELECT COUNT(*) AS count FROM requests WHERE status = '待審核'")->fetch_assoc()['count'];
$totalSemesterRequests = $mysqli->query("SELECT COUNT(*) AS count FROM semester_reservations WHERE id NOT IN (SELECT id FROM reservations)")->fetch_assoc()['count'];
$visitCounts = $mysqli->query("SELECT visits FROM analytics WHERE page = 'admin_home'")->fetch_assoc()['visits'] ?? 0;
$mysqli->close();
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; padding: 30px 0;">
    <div class="container">
        <h1 class="text-center text-white" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1s;">管理者首頁</h1>
        <p class="text-center text-light" style="font-family: 'Roboto', sans-serif;">歡迎回來！查看系統狀態與操作管理功能。</p>

        <!-- 功能卡片區域 -->
        <div class="row g-4 text-center">
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-lg" style="border-radius: 15px; animation: fadeIn 1s;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #6a11cb;">使用者總數</h5>
                        <p class="card-text display-4" style="font-weight: bold; color: #2575fc;">
                            <?= $totalUsers ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-lg" style="border-radius: 15px; animation: fadeIn 1.2s;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #6a11cb;">預約總數</h5>
                        <p class="card-text display-4" style="font-weight: bold; color: #2575fc;">
                            <?= $totalReservations ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-lg" style="border-radius: 15px; animation: fadeIn 1.4s;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #6a11cb;">教室待審核</h5>
                        <p class="card-text display-4" style="font-weight: bold; color: #2575fc;">
                            <?= $totalPendingRequests ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-lg" style="border-radius: 15px; animation: fadeIn 1.6s;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #6a11cb;">學期待審核</h5>
                        <p class="card-text display-4" style="font-weight: bold; color: #2575fc;">
                            <?= $totalSemesterRequests ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 快捷操作區 -->
        <div class="d-flex justify-content-center mt-4">
    <a href="pending_requests.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">查看待審核請求</a>
    <a href="record.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">查看所有紀錄</a>
    <a href="bb.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">帳號管理</a>
    <a href="semester1.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">設定學期區間</a>
    <a href="contact_reply.php" class="btn btn-light btn-lg" style="border-radius: 15px;">回覆訊息</a>
</div>


        <!-- 動態圖表區 -->
        <div class="mt-5">
            <h2 class="text-center text-white" style="font-family: 'Roboto', sans-serif; animation: fadeIn 2s;">網頁瀏覽次數</h2>
            <canvas id="visitChart" style="max-width: 600px; margin: auto; animation: fadeIn 2.5s; background: rgba(255, 255, 255, 0.8); border-radius: 15px; padding: 10px;"></canvas>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('visitChart').getContext('2d');
    const visitData = {
        labels: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
        datasets: [{
            label: '訪問次數',
            data: [12, 19, 3, 5, 2, 3, <?= $visitCounts ?>], // 示例數據
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    const visitChart = new Chart(ctx, {
        type: 'bar',
        data: visitData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>

<?php
include 'footer.php'; // 引入共用頁尾
?>
