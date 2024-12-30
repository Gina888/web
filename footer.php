<?php
// header.php: 共用的頁頭導航部分
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>輔大教室預借系統</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <!-- 引入 Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 引入 Google 字體 -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- 引入自訂樣式 -->
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">教室預借系統</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="login.php">登入</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">註冊</a></li>
                </ul>
            </div>
        </div>
    </nav>
<?php
// header.php 結束
?>

<?php
// footer.php: 共用的頁尾部分
?>
<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p>&copy; 2024 教室預借系統 | Designed by Fu Jen Catholic University</p>
    </div>
</footer>
<!-- 引入 Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// footer.php 結束
?>
