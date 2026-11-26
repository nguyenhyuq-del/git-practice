<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
function ttchat_admin_id($value): int {
    return AdminChatController::chat_ttchat_admin_id($value);
}
function ttchat_admin_label(array $row): string {
    return AdminChatController::chat_ttchat_admin_label($row);
}
function ttchat_admin_list(array $rows, int $selectedId): string {
    return AdminChatController::chat_ttchat_admin_list($rows, $selectedId);
}
function ttchat_admin_messages(mysqli_result $messages, bool $legacy = false): string {
    return AdminChatController::chat_ttchat_admin_messages($messages, $legacy);
}
extract((new AdminChatController())->chat(), EXTR_OVERWRITE);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chat khách hàng - TT SHOP</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .admin-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background: #15985d;
            color: white;
            padding: 25px 20px;
        }

        .sidebar h2 {
            font-size: 30px;
            margin-bottom: 35px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 14px 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,.18);
        }

        .main {
            padding: 30px;
            min-width: 0;
        }

        .topbar {
            background: white;
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 25px;
            box-shadow: 0 4px 14px rgba(0,0,0,.08);
        }

        .topbar h1 {
            margin: 0;
        }

        .chat-wrapper {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 18px;
        }

        .chat-list,
        .chat-panel {
            background: white;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,.08);
            overflow: hidden;
        }

        .chat-list-title {
            background: #15985d;
            color: white;
            padding: 16px;
            font-weight: bold;
        }

        .customer-item {
            display: block;
            padding: 16px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        .customer-item:hover,
        .customer-item.active {
            background: #e9f7ef;
            border-left: 5px solid #15985d;
        }

        .customer-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .customer-time {
            font-size: 13px;
            color: #777;
        }

        .chat-panel-header {
            background: #15985d;
            color: white;
            padding: 16px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-body {
            height: 520px;
            overflow-y: auto;
            background: #fafafa;
            padding: 18px;
        }

        .msg {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 16px;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .user-msg {
            background: #e9e9e9;
            color: #333;
        }

        .admin-msg {
            background: #15985d;
            color: white;
            margin-left: auto;
        }

        .time {
            font-size: 12px;
            opacity: .75;
            margin-top: 5px;
        }

        .chat-form {
            display: flex;
            gap: 12px;
            padding: 16px;
            border-top: 1px solid #eee;
        }

        .chat-form input {
            flex: 1;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 999px;
            outline: none;
        }

        .chat-form button {
            border: 0;
            background: #15985d;
            color: white;
            border-radius: 999px;
            padding: 0 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .empty-chat {
            text-align: center;
            color: #777;
            padding: 80px 20px;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<div class="admin-layout">

<?php include "sidebar.php"; ?>

    <main class="main">

        <div class="topbar">
            <h1>Chat khách hàng</h1>
        </div>

        <div class="chat-wrapper">

            <div class="chat-list">
                <div class="chat-list-title">
                    Danh sách khách chat
                </div>

                <a class="customer-item <?php echo $archive ? 'active' : ''; ?>" href="chat.php?archive=1">📁 Lịch sử chat cũ (chỉ xem)</a>
                <div id="ttchatAdminList"><?php echo $listHtml; ?></div>
            </div>

            <div class="chat-panel">

                <?php if ($archive) { ?>
                    <div class="chat-panel-header">Lịch sử chat cũ – chưa xác minh chủ sở hữu</div>
                    <div class="chat-body" id="chatBody">
                        <p>Chỉ Admin xem. Tin nhắn cũ có thể bị gộp theo tên; vai trò người gửi cũ chưa được xác thực.</p>
                        <?php echo $archiveHtml; ?>
                    </div>
                <?php } elseif ($selected) { ?>
                    <div class="chat-panel-header"><span>Chat với: <?php echo ChatService::escape(ttchat_admin_label($selected)); ?></span></div>
                    <div class="chat-body" id="chatBody"><?php echo $messagesHtml; ?></div>
                    <form method="POST" class="chat-form" action="chat.php">
                        <input type="hidden" name="conversation_id" value="<?php echo (int)$selected['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo ChatService::escape($token); ?>">
                        <input type="text" name="message" maxlength="2000" placeholder="Nhập tin nhắn trả lời khách..." required>
                        <button type="submit">Gửi</button>
                    </form>
                    <div id="ttchatAdminStatus" role="status" style="padding:0 16px;color:#a52a2a"></div>
                <?php } else { ?>
                    <div class="empty-chat">Chọn một khách ở cột trái để bắt đầu chat.</div>
                <?php } ?>
            </div>

        </div>

    </main>

</div>

<script>
const chatBody = document.getElementById('chatBody');
if (chatBody) chatBody.scrollTop = chatBody.scrollHeight;
<?php if (!$archive) { ?>
let ttchatAdminBusy = false, ttchatAdminStopped = false;
const ttchatSelectedId = <?php echo (int)($selected['id'] ?? 0); ?>;
const ttchatAdminToken = <?php echo json_encode($token, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
let ttchatAdminLast = chatBody ? chatBody.innerHTML : '';
setInterval(async function() {
    if (ttchatAdminBusy || ttchatAdminStopped) return;
    ttchatAdminBusy = true;
    try {
        const response = await fetch('chat.php?poll=1&conversation_id=' + ttchatSelectedId, {
            credentials:'same-origin', cache:'no-store', headers:{'X-Chat-CSRF':ttchatAdminToken}
        });
        if (response.status === 403 || response.redirected) {
            ttchatAdminStopped = true;
            if (chatBody) chatBody.textContent = 'Phiên Admin đã hết hạn. Vui lòng đăng nhập lại.';
            document.getElementById('ttchatAdminList').textContent = '';
            const form = document.querySelector('.chat-form'); if (form) form.remove();
            return;
        }
        if (!response.ok) throw new Error('Không thể cập nhật chat.');
        const data = await response.json();
        document.getElementById('ttchatAdminList').innerHTML = data.list_html;
        if (chatBody && data.messages_html !== ttchatAdminLast) {
            const atBottom = chatBody.scrollHeight - chatBody.scrollTop - chatBody.clientHeight < 60;
            chatBody.innerHTML = data.messages_html;
            ttchatAdminLast = data.messages_html;
            if (atBottom) chatBody.scrollTop = chatBody.scrollHeight;
        }
        const status = document.getElementById('ttchatAdminStatus'); if (status) status.textContent = '';
    } catch (_) {
        const status = document.getElementById('ttchatAdminStatus'); if (status) status.textContent = 'Không thể cập nhật chat. Sẽ thử lại.';
    } finally { ttchatAdminBusy = false; }
}, 4000);
<?php } ?>
</script>

</body>
</html>