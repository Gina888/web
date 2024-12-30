<?php
// 帳號管理頁面 (bb.php)
include 'header.php'; // 引入共用頭部

// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 處理編輯請求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $mysqli->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE user_id = ?");
    $stmt->bind_param('sssi', $name, $email, $role, $userId);
    $stmt->execute();
    header('Location: bb.php'); // 返回帳號管理頁
    exit;
}

// 處理刪除請求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];

    $stmt = $mysqli->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    header('Location: bb.php'); // 返回帳號管理頁
    exit;
}

// 查詢所有使用者資料
$usersResult = $mysqli->query("SELECT user_id, name, email, role FROM users ORDER BY role DESC, name ASC");
$users = [];
if ($usersResult) {
    while ($row = $usersResult->fetch_assoc()) {
        $users[] = $row;
    }
}
$mysqli->close();
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; padding: 30px 0;">
    <div class="container">
        <h1 class="text-center text-white" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1s;">帳號管理</h1>

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

        <!-- 使用者列表 -->
        <div class="card shadow-lg" style="border-radius: 15px; animation: slideInUp 1s;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4" style="color: #6a11cb; font-weight: bold;">使用者列表</h3>
                <?php if (!empty($users)): ?>
                    <table class="table table-hover text-center" style="animation: fadeIn 2s;">
                        <thead class="table-info">
                            <tr>
                                <th>使用者 ID</th>
                                <th>姓名</th>
                                <th>電子郵件</th>
                                <th>身份</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['role']) ?></td>
                                    <td>
                                        <!-- 編輯按鈕觸發模態框 -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $user['user_id'] ?>">編輯</button>
                                        <!-- 刪除按鈕 -->
                                        <form method="POST" action="bb.php" style="display: inline-block;">
                                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm">刪除</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- 編輯模態框 -->
                                <div class="modal fade" id="editModal<?= $user['user_id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">編輯使用者</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="bb.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">姓名</label>
                                                        <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">電子郵件</label>
                                                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="role" class="form-label">身份</label>
                                                        <select id="role" name="role" class="form-select">
                                                            <option value="教師" <?= $user['role'] === '教師' ? 'selected' : '' ?>>教師</option>
                                                            <option value="管理者" <?= $user['role'] === '管理者' ? 'selected' : '' ?>>管理者</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                    <button type="submit" name="edit_user" class="btn btn-primary">儲存變更</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted">目前沒有使用者資料。</p>
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
