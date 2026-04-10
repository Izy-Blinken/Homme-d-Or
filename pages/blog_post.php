<?php
include '../backend/db_connect.php';

$post_id = intval($_GET['id'] ?? 0);

if (!$post_id) {
    header('Location: blog.php');
    exit;
}

$post = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT bp.*, bc.name AS category_name
     FROM blog_posts bp
     LEFT JOIN blog_categories bc ON bc.category_id = bp.category_id
     WHERE bp.post_id = '$post_id' AND bp.is_published = 1
     LIMIT 1"));

if (!$post) {
    header('Location: blog.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($post['title']) ?> — Homme d'Or</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
    <link rel="stylesheet" href="../assets/css/BlogPageStyle.css">
    <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css">
    <link rel="stylesheet" href="../assets/css/HomepageStyle.css">
    <style>
        body {
            background: #f5f5f5;
            color: #333;
        }
    </style>
    
</head>
<body>
    <?php include '../components/header.php'; ?>

    <main style="background-image:url('../assets/images/brand_images/bg-image.jpg'); background-size:cover; background-position:center; background-attachment:fixed; background-color:#0e101f; min-height:100vh;">

        

        <div class="post-container fade-in">
            <div class="post-meta">
                <?php if ($post['category_name']): ?>
                    <span class="post-category-badge"><?= htmlspecialchars($post['category_name']) ?></span>
                <?php endif; ?>
                <span class="post-date"><?= date('F d, Y', strtotime($post['created_at'])) ?></span>
            </div>

            <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
            <div class="post-divider"></div>

            <div class="post-body">
                <?= $post['body'] ?>
            </div>

            <a href="blog.php" class="post-back">← Back to Blog</a>
        </div>

    </main>

    <script src="../assets/js/HomepageAnimations.js"></script>
    <?php include '../components/footer.php'; ?>
</body>
</html>