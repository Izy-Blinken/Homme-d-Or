<?php
session_start();
include '../../backend/db_connect.php';

if (empty($_SESSION['superadmin_id']) && empty($_SESSION['admin_id'])) {
    header('Location: adminLogin.php');
    exit;
}

$post_id = intval($_GET['post_id'] ?? 0);
$post = null;

if ($post_id) {
    $post = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM blog_posts WHERE post_id = '$post_id' LIMIT 1"));
    if (!$post) {
        header('Location: admin_blog.php');
        exit;
    }
}

$categories = [];
$result = mysqli_query($conn, "SELECT * FROM blog_categories ORDER BY name ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $post ? 'Edit Post' : 'New Post' ?> — Homme d'Or Admin</title>
    <link rel="stylesheet" href="../../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
    <style>
        .img-preview { display:none; margin-top:12px; max-width:300px; border:1px solid rgba(212,175,55,0.3); }
        .img-preview.show { display:block; }
        .check-group { display:flex; gap:30px; }
        .check-item { display:flex; align-items:center; gap:8px; font-size:14px; color:#ccc; cursor:pointer; }
        .check-item input { width:16px; height:16px; cursor:pointer; accent-color:#c9a961; }
        .form-group small { display:block; margin-top:6px; font-size:12px; color:#888; }
        .body-textarea { width:100%; background:rgba(0,0,0,0.4); border:1px solid rgba(212,175,55,0.3); color:#fff; padding:10px 14px; resize:vertical; font-family:'Spartan',sans-serif; box-sizing:border-box; outline:none; min-height:80px; }
        .body-textarea:focus { border-color:#c9a961; }
        .section-divider { border:none; border-top:1px solid rgba(212,175,55,0.2); margin:24px 0; }
        .field-label-hint { font-size:11px; color:#888; font-weight:300; letter-spacing:0; text-transform:none; margin-left:6px; }
    </style>
</head>
<body>
    <?php include '../../components/adminSideBar.php'; ?>
    <?php include '../../components/adminNavbar.php'; ?>

    <div class="container" style="max-width:900px;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
            <h1 class="page-title" style="margin:0;"><?= $post ? 'Edit Post' : 'New Post' ?></h1>
            <a href="admin_blog.php" style="font-size:13px; color:#aaa; text-decoration:none;">← Back to Blog</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="../../backend/blog/save_post.php" enctype="multipart/form-data">
            <?php if ($post): ?>
                <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
            <?php endif; ?>

            <div class="table-container" style="margin-bottom:24px;">

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="postTitle"
                        value="<?= htmlspecialchars($post['title'] ?? '') ?>"
                        placeholder="Enter post title" required style="width:100%;">
                </div>

                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" id="postSlug"
                        value="<?= htmlspecialchars($post['slug'] ?? '') ?>"
                        placeholder="auto-generated from title" style="width:100%;">
                    <small>Leave blank to auto-generate from title.</small>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" style="width:100%;">
                        <option value="">— No Category —</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>"
                                <?= ($post && $post['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Excerpt <span class="field-label-hint">— shown on blog listing card</span></label>
                    <textarea name="excerpt" class="body-textarea" placeholder="Short description shown on blog listing..."><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Cover Image</label>
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
                        onchange="previewImage(this)" style="color:#ccc;">
                    <?php if ($post && $post['cover_image']): ?>
                        <img src="../../assets/images/blog/<?= htmlspecialchars($post['cover_image']) ?>"
                            class="img-preview show" id="imgPreview" alt="Cover">
                    <?php else: ?>
                        <img class="img-preview" id="imgPreview" alt="Preview">
                    <?php endif; ?>
                </div>

                <hr class="section-divider">

                <div class="form-group">
                    <label>Introduction <span class="field-label-hint">— opening paragraph</span></label>
                    <textarea name="intro" class="body-textarea" style="min-height:100px;"><?= htmlspecialchars($post['intro'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Section 1 — Heading</label>
                    <input type="text" name="sec1_heading" value="<?= htmlspecialchars($post['sec1_heading'] ?? '') ?>" placeholder="e.g. The Art of Layering Scents" style="width:100%;">
                </div>

                <div class="form-group">
                    <label>Section 1 — Paragraph</label>
                    <textarea name="sec1_body" class="body-textarea" style="min-height:100px;"><?= htmlspecialchars($post['sec1_body'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Section 2 — Heading <span class="field-label-hint">(optional)</span></label>
                    <input type="text" name="sec2_heading" value="<?= htmlspecialchars($post['sec2_heading'] ?? '') ?>" placeholder="e.g. Choosing Your Signature Scent" style="width:100%;">
                </div>

                <div class="form-group">
                    <label>Section 2 — Paragraph <span class="field-label-hint">(optional)</span></label>
                    <textarea name="sec2_body" class="body-textarea" style="min-height:100px;"><?= htmlspecialchars($post['sec2_body'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Quote / Highlight <span class="field-label-hint">(optional) — displayed as a styled pullquote</span></label>
                    <input type="text" name="quote" value="<?= htmlspecialchars($post['quote'] ?? '') ?>" placeholder="e.g. A scent is a memory you wear." style="width:100%;">
                </div>

                <div class="form-group">
                    <label>Closing Paragraph <span class="field-label-hint">(optional)</span></label>
                    <textarea name="closing" class="body-textarea" style="min-height:100px;"><?= htmlspecialchars($post['closing'] ?? '') ?></textarea>
                </div>

                <hr class="section-divider">

                <div class="form-group">
                    <div class="check-group">
                        <label class="check-item">
                            <input type="checkbox" name="is_published" value="1"
                                <?= ($post && $post['is_published']) ? 'checked' : '' ?>>
                            Published
                        </label>
                        <label class="check-item">
                            <input type="checkbox" name="is_featured" value="1"
                                <?= ($post && $post['is_featured']) ? 'checked' : '' ?>>
                            Featured
                        </label>
                    </div>
                </div>
            </div>

            <div style="display:flex; gap:12px; align-items:center;">
                <button type="submit" class="btn-save">Save Post</button>
                <a href="admin_blog.php" style="display:inline-block; padding:12px 20px; border:1px solid rgba(255,255,255,0.2); color:#aaa; text-decoration:none;">Cancel</a>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('postTitle').addEventListener('input', function () {
        const slugField = document.getElementById('postSlug');
        if (slugField.dataset.manual === 'true') return;
        slugField.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    });

    document.getElementById('postSlug').addEventListener('input', function () {
        this.dataset.manual = this.value ? 'true' : 'false';
    });

    function previewImage(input) {
        const preview = document.getElementById('imgPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.add('show');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
    <script src="../../assets/js/AdminProfile.js" defer></script>
    <script src="../../assets/js/AdminPanel.js" defer></script>
    <script src="../../assets/js/script.js" defer></script>
</body>
</html>