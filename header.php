<?php
session_start(); // 啟用 Session

// 確認是否已登入
$isLoggedIn = isset($_SESSION['user_name']) && !empty($_SESSION['user_name']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;

// 取得目前檔案名稱
$currentFile = basename($_SERVER['PHP_SELF']);

// 定義需要顯示登入/註冊的頁面
$loginPages = ['index.php', 'login.php', 'register.php'];

// 除錯輸出 (測試用，完成後可刪除)
error_log("當前檔案: $currentFile");
error_log("使用者登入狀態: " . ($isLoggedIn ? "已登入" : "未登入"));
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-gradient">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="https://th.bing.com/th?id=OSK.1c539cc5c715ddee40d0d1f15616dca2&w=46&h=46&c=11&rs=1&qlt=80&o=6&dpr=1.4&pid=SANGAM" alt="Logo" height="30" class="me-2">
            教學預約系統
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($isLoggedIn): ?>
                    <!-- 已登入狀態，顯示登出按鈕 -->
                    <li class="nav-item">
                        <span class="nav-link text-white">歡迎, <?= htmlspecialchars($userName) ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link logout-link" href="logout.php">登出 (<?= htmlspecialchars($userName) ?>)</a>
                    </li>
                <?php else: ?>
                    <!-- 未登入狀態 -->
                    <?php if (in_array($currentFile, $loginPages)): ?>
                        <li class="nav-item">
                            <a class="nav-link login-link" href="login.php">登入</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link register-link" href="register.php">註冊</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
