
<?php
include 'header.php'; // 引入共用頭部

// 資料庫連接
$mysqli = new mysqli('localhost', 'root', '', '期末');
if ($mysqli->connect_error) {
    die('連接失敗：' . $mysqli->connect_error);
}

// 處理回覆請求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'])) {
    $messageId = $_POST['message_id'];
    $reply = $_POST['reply'];

    $stmt = $mysqli->prepare("UPDATE contact_messages SET reply = ?, replied_at = NOW() WHERE id = ?");
    $stmt->bind_param('si', $reply, $messageId);
    $stmt->execute();
    header('Location: contact_response.php'); // 返回回覆頁
    exit;
}

// 查詢所有聯絡訊息
$messagesResult = $mysqli->query("SELECT id, name, email, message, reply, created_at, replied_at FROM contact_messages ORDER BY created_at DESC");
$messages = [];
if ($messagesResult) {
    while ($row = $messagesResult->fetch_assoc()) {
        $row['status'] = empty($row['replied_at']) ? '未回覆' : '已回覆';
        $messages[] = $row;
    }
}
$mysqli->close();
?>

<div style="background: linear-gradient(to right, #6a11cb, #2575fc); min-height: 100vh; padding: 30px 0;">
    <div class="container">
        <h1 class="text-center text-white" style="font-family: 'Roboto', sans-serif; font-weight: bold; animation: fadeIn 1s;">回覆聯絡訊息</h1>

        <!-- 快捷操作區 -->
        <div class="d-flex justify-content-center mt-4" style="animation: fadeIn 1.5s;">
            <a href="aa.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">返回管理者首頁</a>
            <a href="pending_requests.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">查看待審核請求</a>
            <a href="record.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">查看所有紀錄</a>
            <a href="bb.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">帳號管理</a>
            <a href="semester1.php" class="btn btn-light btn-lg me-3" style="border-radius: 15px;">設定學期區間</a>
            <a href="contact_reply.php" class="btn btn-light btn-lg" style="border-radius: 15px;">回覆訊息</a>
        </div>

        <!-- 聯絡訊息列表 -->
        <div class="card shadow-lg mt-4" style="border-radius: 15px; animation: slideInUp 1s;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4" style="color: #6a11cb; font-weight: bold;">訊息列表</h3>
                <?php if (!empty($messages)): ?>
                    <table class="table table-hover text-center" style="animation: fadeIn 2s;">
                        <thead class="table-info">
                            <tr>
                                <th>ID</th>
                                <th>姓名</th>
                                <th>電子郵件</th>
                                <th>訊息內容</th>
                                <th>回覆</th>
                                <th>提交時間</th>
                                <th>狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $message): ?>
                                <tr>
                                    <td><?= htmlspecialchars($message['id']) ?></td>
                                    <td><?= htmlspecialchars($message['name']) ?></td>
                                    <td><?= htmlspecialchars($message['email']) ?></td>
                                    <td><?= htmlspecialchars($message['message']) ?></td>
                                    <td><?= htmlspecialchars($message['reply'] ?? '未回覆') ?></td>
                                    <td><?= htmlspecialchars($message['created_at']) ?></td>
                                    <td><?= htmlspecialchars($message['status']) ?></td>
                                    <td>
                                        <!-- 回覆按鈕觸發模態框 -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#replyModal<?= $message['id'] ?>">回覆</button>
                                    </td>
                                </tr>

                                <!-- 回覆模態框 -->
                                <div class="modal fade" id="replyModal<?= $message['id'] ?>" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="replyModalLabel">回覆訊息</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="contact_response.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="reply" class="form-label">回覆內容</label>
                                                        <textarea id="reply" name="reply" class="form-control" rows="5" required><?= htmlspecialchars($message['reply']) ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                    <button type="submit" name="reply_message" class="btn btn-primary">送出回覆</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted">目前沒有聯絡訊息。</p>
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