<?php
session_start();
include '../../backend/db_connect.php';

if (empty($_SESSION['superadmin_id']) && empty($_SESSION['admin_id'])) {
    header('Location: adminLogin.php');
    exit;
}

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$posts = [];
$result = mysqli_query($conn,
    "SELECT bp.*, bc.name AS category_name
     FROM blog_posts bp
     LEFT JOIN blog_categories bc ON bc.category_id = bp.category_id
     ORDER BY bp.created_at DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Blog Management — Homme d'Or Admin</title>
        <link rel="stylesheet" href="../../assets/icons/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
    </head>

    <body>
        <?php include '../../components/adminSideBar.php'; ?>
        <?php include '../../components/adminNavbar.php'; ?>

        <div class="container" style="margin: 0;">

            
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
                <h1 class="page-title" style="margin:0;">Blog Management</h1>
                <a href="admin_blog_form.php" class="add-product-btn" style="text-decoration:none;">+ New Post</a>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (empty($posts)): ?>
                <div class="table-container">
                    <p style="text-align:center; padding:60px; color:#aaa; letter-spacing:2px;">No posts yet. Create your first post.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <div class="responsive-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($post['title']) ?>
                                            <?php if ($post['is_featured']): ?>
                                                <span class="badge badge-low-stock" style="margin-left:8px;">Featured</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($post['category_name'] ?? '—') ?></td>
                                        <td>
                                            <span class="badge <?= $post['is_published'] ? 'badge-in-stock' : 'badge-inactive' ?>">
                                                <?= $post['is_published'] ? 'Published' : 'Draft' ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                                        <td>
                                            <div style="display:flex; gap:8px; align-items:center;">
                                                <a href="admin_blog_form.php?post_id=<?= $post['post_id'] ?>" class="btn-edit">Edit</a>
                                                <button class="btn-view-details"
                                                        onclick="togglePost(<?= $post['post_id'] ?>, 'is_published', this)">
                                                    <?= $post['is_published'] ? 'Unpublish' : 'Publish' ?>
                                                </button>
                                                <button class="btn-view-details" style="color:#d4af37; border-color:#d4af37;"
                                                        onclick="togglePost(<?= $post['post_id'] ?>, 'is_featured', this)">
                                                    <?= $post['is_featured'] ? 'Unfeature' : 'Feature' ?>
                                                </button>
                                                <button class="btn-delete"
                                                        onclick="deletePost(<?= $post['post_id'] ?>)">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <form id="deleteForm" method="POST" action="../../backend/blog/delete_post.php" style="display:none;">
            <input type="hidden" name="post_id" id="deletePostId">
        </form>

        <script>
        function deletePost(postId) {
            if (!confirm('Are you sure you want to delete this post? This cannot be undone.')) return;
            document.getElementById('deletePostId').value = postId;
            document.getElementById('deleteForm').submit();
        }

        function togglePost(postId, field, btn) {
            const formData = new FormData();
            formData.append('post_id', postId);
            formData.append('field', field);

            fetch('../../backend/blog/toggle_post.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;
                    if (field === 'is_published') {
                        btn.textContent = data.value ? 'Unpublish' : 'Publish';
                        const row = btn.closest('tr');
                        const badge = row.querySelector('.badge-in-stock, .badge-inactive');
                        if (badge) {
                            badge.textContent = data.value ? 'Published' : 'Draft';
                            badge.className = 'badge ' + (data.value ? 'badge-in-stock' : 'badge-inactive');
                        }
                    } else {
                        btn.textContent = data.value ? 'Unfeature' : 'Feature';
                        const row = btn.closest('tr');
                        let featBadge = row.querySelector('.badge-low-stock');
                        if (data.value && !featBadge) {
                            const td = row.querySelector('td:first-child');
                            const span = document.createElement('span');
                            span.className = 'badge badge-low-stock';
                            span.style.marginLeft = '8px';
                            span.textContent = 'Featured';
                            td.appendChild(span);
                        } else if (!data.value && featBadge) {
                            featBadge.remove();
                        }
                    }
                });
        }
        </script>
        <script src="../../assets/js/AdminProfile.js" defer></script>
        <script src="../../assets/js/AdminPanel.js" defer></script>
        <script src="../../assets/js/script.js" defer></script>
    </body>
</html>