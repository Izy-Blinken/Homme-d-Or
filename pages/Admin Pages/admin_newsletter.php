<?php
session_start();
include '../../backend/db_connect.php';

if (empty($_SESSION['superadmin_id'])) {
    header('Location: adminLogin.php');
    exit;
}

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$subscriber_count = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total FROM newsletter_subscribers WHERE is_active = 1"
))['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Newsletter — Homme d'Or Admin</title>
    <link rel="stylesheet" href="../../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
    <style>
        .newsletter-card { background:rgba(0,0,0,0.6); border:1px solid rgba(212,175,55,0.3); padding:36px; margin-bottom:24px; }
        .subscriber-count { font-size:13px; color:#888; margin-bottom:24px; }
        .subscriber-count span { color:#c9a961; font-weight:700; font-size:16px; }
        .body-textarea { width:100%; background:rgba(0,0,0,0.4); border:1px solid rgba(212,175,55,0.3); color:#fff; padding:10px 14px; resize:vertical; font-family:'Spartan',sans-serif; box-sizing:border-box; outline:none; min-height:200px; }
        .body-textarea:focus { border-color:#c9a961; }
        .send-confirm { display:none; background:rgba(212,175,55,0.08); border:1px solid rgba(212,175,55,0.3); padding:16px 20px; margin-top:16px; font-size:13px; color:#ccc; }
        .send-confirm.show { display:block; }
    </style>
</head>
<body>
    <?php include '../../components/adminSideBar.php'; ?>
    <?php include '../../components/adminNavbar.php'; ?>

    <div class="container" style="max-width:800px;">
        <h1 class="page-title">Newsletter</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="newsletter-card">
            <p class="subscriber-count">Sending to <span><?= $subscriber_count ?></span> active subscriber<?= $subscriber_count !== 1 ? 's' : '' ?></p>

            <form method="POST" action="../../backend/blog/send_newsletter.php" id="newsletterForm">
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" placeholder="e.g. New arrivals from Homme d'Or" required style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" class="body-textarea" placeholder="Write your newsletter content here..." required></textarea>
                </div>

                <div class="send-confirm" id="sendConfirm">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#c9a961; margin-right:8px;"></i>
                    This will send an email to <strong style="color:#c9a961;"><?= $subscriber_count ?> subscriber<?= $subscriber_count !== 1 ? 's' : '' ?></strong>. This cannot be undone.
                    <div style="display:flex; gap:12px; margin-top:16px;">
                        <button type="submit" class="btn-save">Yes, Send Now</button>
                        <button type="button" onclick="cancelSend()" style="background:transparent; border:1px solid rgba(255,255,255,0.2); color:#aaa; padding:10px 20px; cursor:pointer;">Cancel</button>
                    </div>
                </div>

                <div id="sendBtn" style="margin-top:16px;">
                    <button type="button" class="btn-save" onclick="confirmSend()">
                        <i class="fa-solid fa-paper-plane" style="margin-right:8px;"></i>Send Newsletter
                    </button>
                </div>
            </form>
        </div>

        <!-- Subscribers list -->
        <div class="newsletter-card">
            <h2 style="font-size:1rem; font-weight:700; color:#c9a961; letter-spacing:2px; text-transform:uppercase; margin-bottom:20px;">Subscribers</h2>
            <?php
            $subs = mysqli_query($conn, "SELECT email, subscribed_at FROM newsletter_subscribers WHERE is_active = 1 ORDER BY subscribed_at DESC");
            if (mysqli_num_rows($subs) === 0):
            ?>
                <p style="color:#888; font-size:13px;">No subscribers yet.</p>
            <?php else: ?>
                <div class="responsive-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Subscribed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($sub = mysqli_fetch_assoc($subs)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($sub['email']) ?></td>
                                    <td><?= date('M d, Y', strtotime($sub['subscribed_at'])) ?></td>
                                    <td>
                                        <button class="btn-delete" onclick="unsubscribe('<?= htmlspecialchars($sub['email']) ?>', this)">Remove</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function confirmSend() {
        const subject = document.querySelector('input[name="subject"]').value.trim();
        const message = document.querySelector('textarea[name="message"]').value.trim();
        if (!subject || !message) {
            alert('Please fill in both subject and message.');
            return;
        }
        document.getElementById('sendConfirm').classList.add('show');
        document.getElementById('sendBtn').style.display = 'none';
    }

    function cancelSend() {
        document.getElementById('sendConfirm').classList.remove('show');
        document.getElementById('sendBtn').style.display = 'block';
    }

    function unsubscribe(email, btn) {
        if (!confirm('Remove ' + email + ' from subscribers?')) return;
        const formData = new FormData();
        formData.append('email', email);
        fetch('../../backend/blog/unsubscribe_admin.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) btn.closest('tr').remove();
            });
    }
    </script>
    <script src="../../assets/js/AdminProfile.js" defer></script>
    <script src="../../assets/js/AdminPanel.js" defer></script>
    <script src="../../assets/js/script.js" defer></script>
</body>
</html>