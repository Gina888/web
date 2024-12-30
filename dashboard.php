<?php
include 'header.php'; // 引入共用頭部
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; padding: 30px 0;">
    <div class="container">
        <h1 class="text-white text-center" style="font-family: 'Roboto', sans-serif; font-weight: bold;">教師儀表板</h1>
        <p class="text-center text-light" style="font-family: 'Roboto', sans-serif;">歡迎回來，這是您的教學管理中心。</p>

        <!-- 功能快捷入口 -->
         
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

        <!-- 重要通知 -->
        <div class="card shadow-lg" style="border-radius: 15px; animation: fadeIn 1s;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4" style="color: #6a11cb;">重要通知</h3>
                <ul style="list-style: none; padding: 0;">
                    <li class="mb-3" style="font-size: 1.1em;">
                        <span style="color: #6a11cb; font-weight: bold;">⚠ 系統維護通知：</span> 系統將於 2024-12-25 晚上 10:00 至 12:00 進行維護，請提前完成所有操作。
                    </li>
                    <li class="mb-3" style="font-size: 1.1em;">
                        <span style="color: #6a11cb; font-weight: bold;">📢 新功能上線：</span> 學期預借現在支援每週重複功能，提升預約效率！
                    </li>
                    <li class="mb-3" style="font-size: 1.1em;">
                        <span style="color: #6a11cb; font-weight: bold;">📅 預約提醒：</span> 教室 LM200 的預約期限即將到期，請及時確認後續使用計劃。
                    </li>
                    <li style="font-size: 1.1em;">
                        <span style="color: #6a11cb; font-weight: bold;">📞 聯絡我們：</span> 若有問題，請透過「聯絡我們」功能隨時與管理員聯繫。
                    </li>
                </ul>
            </div>
        </div>

        <!-- 資料區 -->
        <div class="row mt-4" style="animation: slideInUp 1.2s;">
            <div class="col-md-6">
                <div class="card shadow-lg" style="border-radius: 15px;">
                    <div class="card-body">
                        <h4 class="card-title text-center" style="color: #6a11cb;">教室預借數據</h4>
                        <canvas id="classroomChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-lg" style="border-radius: 15px;">
                    <div class="card-body">
                        <h4 class="card-title text-center" style="color: #6a11cb;">學期預借數據</h4>
                        <canvas id="semesterChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 教室預借圖表
    const ctx1 = document.getElementById('classroomChart').getContext('2d');
    const classroomChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['LM200', 'SL201', 'SL245'],
            datasets: [{
                label: '預借次數',
                data: [10, 5, 8],
                backgroundColor: ['#6a11cb', '#2575fc', '#34a853']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });

    // 學期預借圖表
    const ctx2 = document.getElementById('semesterChart').getContext('2d');
    const semesterChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['已批准', '待審核', '已拒絕'],
            datasets: [{
                data: [12, 7, 3],
                backgroundColor: ['#6a11cb', '#fbbc05', '#ea4335']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
</script>

<?php
include 'footer.php'; // 引入共用頁尾
?>
