<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<h1>表單提交成功</h1>';
    echo '<pre>';
    print_r($_POST); // 測試打印提交的數據
    echo '</pre>';
    exit;
} else {
    echo '<h1>這是測試用處理檔案</h1>';
}
?>
