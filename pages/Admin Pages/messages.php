<?php
session_start();
include '../../backend/db_connect.php';
include '../../backend/auth/auth_check.php';
checkAdminAccess($conn);

$isSuperadmin = !empty($_SESSION['superadmin_id']);
$current_admin_id = $_SESSION['admin_id'] ?? null;
$current_superadmin_id = $_SESSION['superadmin_id'] ?? null;

$open_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$open_admin_id = isset($_GET['admin_id']) ? intval($_GET['admin_id']) : null;
$open_superadmin_id = isset($_GET['superadmin_id']) ? intval($_GET['superadmin_id']) : null;
$open_session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : null;

$is_archived = false;

$chat_name = null;
$chat_initial = null;
$chat_type = null;
$assigned_admin = null;
$receiver_id = null;
$receiver_type = null;
$receiver_exists = true;

if ($open_user_id) {

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fname, lname, is_blocked FROM users WHERE user_id = '$open_user_id'"));
    $chat_name = $r ? htmlspecialchars($r['fname'] . ' ' . $r['lname']) : 'Unknown';
    $chat_initial = $r ? strtoupper($r['fname'][0]) : '?';
    $chat_type = 'user';
    $receiver_id = $open_user_id;
    $receiver_type = 'user';
    $receiver_exists = $r && !$r['is_blocked'];

} elseif ($open_admin_id) {

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT u.fname, u.lname FROM admins a JOIN users u ON a.user_id = u.user_id WHERE a.admin_id = '$open_admin_id'"));
    $chat_name = $r ? 'Admin ' . htmlspecialchars($r['fname'] . ' ' . $r['lname']) : 'Unknown';
    $chat_initial = $r ? strtoupper($r['fname'][0]) : '?';
    $chat_type = 'admin';
    $receiver_id = $open_admin_id;
    $receiver_type = 'admin';
    $receiver_exists = (bool) $r;

} elseif ($open_superadmin_id) {

    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM superadmins WHERE superadmin_id = '$open_superadmin_id'"));
    $chat_name = $r ? htmlspecialchars($r['username']) : 'Unknown';
    $chat_initial = $r ? strtoupper($r['username'][0]) : '?';
    $chat_type = 'superadmin';
    $receiver_id = $open_superadmin_id;
    $receiver_type = 'superadmin';

} elseif ($open_session_id) {

    $r = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT u.user_id, u.fname, u.lname FROM chatbot_sessions cs
         JOIN users u ON cs.user_id = u.user_id
         WHERE cs.session_id = '$open_session_id'"));

    $chat_name = $r ? htmlspecialchars($r['fname'] . ' ' . $r['lname']) : 'Unknown';
    $chat_initial = $r ? strtoupper($r['fname'][0]) : '?';
    $chat_type = 'escalated';
    $open_user_id = $r['user_id'] ?? null;

    $assigned = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT am.sender_name FROM admin_messages am
         WHERE am.session_id = '$open_session_id'
         ORDER BY am.sent_at ASC LIMIT 1"));

    $assigned_admin = $assigned ? $assigned['sender_name'] : null;
    $receiver_id = $open_user_id;
    $receiver_type = 'user';

    $admins_list = mysqli_query($conn,
        "SELECT a.admin_id, u.fname, u.lname FROM admins a
         JOIN users u ON a.user_id = u.user_id
         ORDER BY u.fname ASC");

}

if ($chat_type) {

    $ab_id = $isSuperadmin ? $current_superadmin_id : $current_admin_id;
    $ab_type = $isSuperadmin ? 'superadmin' : 'admin';
    $ref_id = $open_session_id ?? $open_user_id ?? $open_admin_id ?? $open_superadmin_id;
    $ct = $open_session_id ? 'escalated' : ($open_user_id ? 'user' : ($open_admin_id ? 'admin' : 'superadmin'));

    $arch_check = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT archive_id FROM conversation_archives
         WHERE archived_by_id = '$ab_id' AND archived_by_type = '$ab_type'
           AND convo_type = '$ct' AND convo_ref_id = '$ref_id'"));

    $is_archived = (bool) $arch_check;
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Messages</title>
        <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
         <link rel="stylesheet" href="../../assets/css/style.css">
        <style>
        .main-content {
            height: 100vh;
            overflow: hidden;
        }
        </style>
    </head>

    <body>

        <?php include '../../components/adminSideBar.php'; ?>

        <div class="main-content">

            <header class="navbar">

                <div class="navbar-left">
                    <button class="hamburger" id="menu-btn"><span></span><span></span><span></span></button>
                    <h1 class="navbar-title">ADMIN PANEL</h1>
                </div>

                <div class="navbar-search">

                    <svg width="16" height="16" fill="none" stroke="#888" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>

                    <input type="text" placeholder="Search...">
                </div>

                <div class="navbar-avatar">A</div>

            </header>

            <div class="messages-layout">

                <div class="convo-list">

                    <div class="convo-list-header">Chats</div>

                    <div style="display:flex; border-bottom:1px solid #e8e8e8;">
                        <button class="convo-filter-btn active" data-filter="all" style="flex:1; padding:0.6rem; border:none; background:none; cursor:pointer; font-size:0.82rem; font-weight:700; border-bottom:2px solid transparent;">All</button>
                        <button class="convo-filter-btn" data-filter="user" style="flex:1; padding:0.6rem; border:none; background:none; cursor:pointer; font-size:0.82rem; font-weight:700; border-bottom:2px solid transparent;">Customers</button>
                        <button class="convo-filter-btn" data-filter="admin" style="flex:1; padding:0.6rem; border:none; background:none; cursor:pointer; font-size:0.82rem; font-weight:700; border-bottom:2px solid transparent;">Admins</button>
                        <button class="convo-filter-btn" data-filter="archived" style="flex:1; padding:0.6rem; border:none; background:none; cursor:pointer; font-size:0.82rem; font-weight:700; border-bottom:2px solid transparent;">Archived</button>
                    </div>

                    <div class="convo-items" id="convo-items">
                        <div class="no-convos">Loading...</div>
                    </div>

                </div>

                <div class="chat-area">

                    <?php if ($chat_type): ?>

                        <div class="chat-header">

                            <div class="convo-avatar <?= $chat_type === 'admin' ? 'admin-avatar' : '' ?>">
                                <?= $chat_initial ?>
                            </div>

                            <div style="flex:1;">

                                <div><?= $chat_name ?></div>

                                <?php if ($chat_type === 'escalated' && $assigned_admin): ?>
                                <div style="font-size:0.78rem; color:#888; font-weight:400;">
                                    Assigned to: <?= htmlspecialchars($assigned_admin) ?>
                                </div>

                                <?php elseif ($chat_type === 'escalated' && !$assigned_admin && $isSuperadmin): ?>
                                <div style="display:flex; align-items:center; gap:0.5rem; margin-top:0.25rem;">

                                    <select id="assign-admin-select" style="padding:4px 8px; border:1px solid #ccc; font-size:0.82rem;">
                                        <option value="">Assign to admin...</option>
                                        <?php while ($a = mysqli_fetch_assoc($admins_list)): ?>
                                        <option value="<?= $a['admin_id'] ?>"><?= htmlspecialchars($a['fname'] . ' ' . $a['lname']) ?></option>
                                        <?php endwhile; ?>
                                    </select>

                                    <button class="btn-save" id="assign-btn" style="padding:4px 12px; font-size:0.82rem;">Assign</button>
                                </div>
                                <?php endif; ?>

                            </div>

                            <div style="display:flex; gap:0.5rem; margin-left:auto;">
                                <button id="archive-btn" style="background:none; border:1px solid #ccc; padding:4px 10px; font-size:0.78rem; cursor:pointer;">Archive</button>
                                <?php if ($isSuperadmin): ?>
                                <button id="delete-btn" style="background:#c0392b; color:white; border:none; padding:4px 10px; font-size:0.78rem; cursor:pointer;">Delete</button>
                                <?php endif; ?>
                            </div>

                        </div>

                        <div class="chat-messages" id="chat-messages"></div>

                        <?php if ($chat_type !== 'escalated' || $assigned_admin): ?>

                            <?php if ($is_archived): ?>
                            <div style="padding:1rem 1.5rem; background:white; border-top:1px solid #e8e8e8; color:#aaa; font-size:0.88rem; text-align:center;">
                                This conversation is archived. Unarchive to send messages.
                            </div>

                            <?php elseif ($receiver_exists): ?>

                            <div class="chat-input-area">
                                <textarea class="chat-input" id="chat-input" rows="1" placeholder="Type a message..."></textarea>
                                <button class="send-btn" id="send-btn">Send</button>
                            </div>

                            <?php else: ?>
                            <div style="padding:1rem 1.5rem; background:white; border-top:1px solid #e8e8e8; color:#aaa; font-size:0.88rem; text-align:center;">
                                This admin no longer exists. Conversation is read-only.
                            </div>
                            <?php endif; ?>

                        <?php endif; ?>

                    <?php else: ?>

                        <div class="chat-placeholder">Select a conversation to start messaging.</div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

        <div id="generalToast" class="generalToast"></div>

        <script src="../../assets/js/AdminPanel.js"></script>
        <script src="../../assets/js/script.js"></script>

        <script>
            const OPEN_USER_ID = <?= json_encode($open_user_id) ?>;
            const OPEN_ADMIN_ID = <?= json_encode($open_admin_id) ?>;
            const OPEN_SUPERADMIN_ID = <?= json_encode($open_superadmin_id) ?>;
            const OPEN_SESSION_ID = <?= json_encode($open_session_id) ?>;
            const IS_SUPERADMIN = <?= json_encode($isSuperadmin) ?>;
            const CURRENT_ID = <?= json_encode($isSuperadmin ? $current_superadmin_id : $current_admin_id) ?>;
            const CURRENT_TYPE = <?= json_encode($isSuperadmin ? 'superadmin' : 'admin') ?>;
            const RECEIVER_ID = <?= json_encode($receiver_id) ?>;
            const RECEIVER_TYPE = <?= json_encode($receiver_type) ?>;
            const OPEN_ARCHIVED = <?= json_encode($is_archived) ?>;
        </script>

        <script src="../../assets/js/messages.js"></script>

    </body>
</html>