<?php
session_start(); // 啟用 Session
session_destroy(); // 清除所有 Session 資料
header("Location: index.php"); // 返回首頁
exit();
?>
