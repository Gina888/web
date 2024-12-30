<?php
// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationId = $_POST['reservation_id'] ?? 0;

    // 確保 reservation_id 有效
    if ($reservationId > 0) {
        $stmt = $mysqli->prepare("UPDATE reservations SET status = '已取消' WHERE reservation_id = ?");
        $stmt->bind_param('i', $reservationId);
        if ($stmt->execute()) {
            $success = "預約已成功取消！";
        } else {
            $error = "取消失敗，請稍後重試。";
        }
        $stmt->close();
    } else {
        $error = "無效的預約 ID。";
    }
}

$mysqli->close();

// 返回上一頁
header('Location: records.php');
exit;
?>
