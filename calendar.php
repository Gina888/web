<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: login.php'); // 未登入跳轉至登入頁面
    exit();
}

$user_email = $_SESSION['user_name']; // 獲取目前登入使用者的電子郵件

// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 查詢登入使用者的預約紀錄
$query = "
    SELECT r.reservation_id, c.name AS classroom_name, r.date, r.start_time, r.end_time, r.purpose
    FROM reservations r
    JOIN users u ON r.user_id = u.user_id
    JOIN classrooms c ON r.classroom_id = c.classroom_id
    WHERE u.email = ?
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $user_email);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = [
        'id' => $row['reservation_id'],
        'title' => $row['purpose'] . ' (' . $row['classroom_name'] . ')',
        'start' => $row['date'] . 'T' . $row['start_time'],
        'end' => $row['date'] . 'T' . $row['end_time']
    ];
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>預約紀錄日曆</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            margin: 0;
            padding: 0;
            color: #fff;
        }
        #calendar {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: <?php echo json_encode($reservations); ?>
            });
            calendar.render();
        });
    </script>
</body>
</html>
