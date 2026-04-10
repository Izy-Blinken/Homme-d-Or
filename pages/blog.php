<?php
include '../backend/db_connect.php';

$featured = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT bp.*, bc.name AS category_name, bc.slug AS category_slug
     FROM blog_posts bp
     LEFT JOIN blog_categories bc ON bc.category_id = bp.category_id
     WHERE bp.is_featured = 1 AND bp.is_published = 1
     LIMIT 1"));

$posts_result = mysqli_query($conn,
    "SELECT bp.*, bc.name AS category_name, bc.slug AS category_slug
     FROM blog_posts bp
     LEFT JOIN blog_categories bc ON bc.category_id = bp.category_id
     WHERE bp.is_published = 1 AND bp.is_featured = 0
     ORDER BY bp.created_at DESC");

$posts = [];
while ($row = mysqli_fetch_assoc($posts_result)) {
    $posts[] = $row;
}

$cats_result = mysqli_query($conn, "SELECT * FROM blog_categories ORDER BY name ASC");
$categories  = [];
while ($row = mysqli_fetch_assoc($cats_result)) {
    $categories[] = $row;
}

$reviews_result = mysqli_query($conn,
    "SELECT pr.rating, pr.comment, pr.created_at,
            CONCAT(u.fname, ' ', u.lname) AS reviewer_name,
            p.product_name
     FROM product_reviews pr
     JOIN users u ON u.user_id = pr.user_id
     JOIN products p ON p.product_id = pr.product_id
     ORDER BY pr.rating DESC, pr.created_at DESC
     LIMIT 5");

$reviews = [];
while ($row = mysqli_fetch_assoc($reviews_result)) {
    $reviews[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog — Homme d'Or</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
    <link rel="stylesheet" href="../assets/css/BlogPageStyle.css">
    <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css">
    <link rel="stylesheet" href="../assets/css/HomepageStyle.css">
</head>
<body>
    <?php include '../components/header.php'; ?>

    <main style="background-image:url('../assets/images/brand_images/bg-image.jpg'); background-size:cover; background-position:center; background-attachment:fixed; background-color:#0e101f; min-height:100vh;">
        <button class="back-btn" onclick="history.back()" title="Go back" style="margin-top: 2rem;"><i class="fas fa-arrow-left"></i> Back</button>

        <?php if ($featured): ?>
        <section class="blog-featured fade-in">
            <div class="blog-featured-inner">
                <?php if ($featured['cover_image']): ?>
                    <img src="../assets/images/blog/<?= htmlspecialchars($featured['cover_image']) ?>"
                         alt="<?= htmlspecialchars($featured['title']) ?>"
                         class="blog-featured-img">
                <?php else: ?>
                    <div class="blog-featured-img" style="background:rgba(212,175,55,0.05);display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-newspaper" style="font-size:4rem;color:rgba(212,175,55,0.2);"></i>
                    </div>
                <?php endif; ?>
                <div class="blog-featured-content">
                    <?php if ($featured['category_name']): ?>
                        <span class="blog-category-badge"><?= htmlspecialchars($featured['category_name']) ?></span>
                    <?php endif; ?>
                    <h2><?= htmlspecialchars($featured['title']) ?></h2>
                    <?php if ($featured['excerpt']): ?>
                        <p><?= htmlspecialchars($featured['excerpt']) ?></p>
                    <?php endif; ?>
                    <div class="blog-featured-meta">
                        <?= date('F d, Y', strtotime($featured['created_at'])) ?>
                    </div>
                    <a href="blog_post.php?id=<?= $featured['post_id'] ?>" class="blog-read-btn">Read More</a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <div class="blog-filter fade-in">
            <button class="filter-pill active" data-category="all">All</button>
            <?php foreach ($categories as $cat): ?>
                <button class="filter-pill" data-category="<?= htmlspecialchars($cat['slug']) ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <section class="blog-grid-section">
            <div class="blog-grid" id="blogGrid">
                <?php if (empty($posts)): ?>
                    <div class="blog-no-posts">No posts yet. Check back soon.</div>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="blog-card fade-in"
                             data-category="<?= htmlspecialchars($post['category_slug'] ?? 'uncategorized') ?>">
                            <?php if ($post['cover_image']): ?>
                                <img src="../assets/images/blog/<?= htmlspecialchars($post['cover_image']) ?>"
                                     alt="<?= htmlspecialchars($post['title']) ?>"
                                     class="blog-card-img">
                            <?php else: ?>
                                <div class="blog-card-img-placeholder">
                                    <i class="fa-solid fa-newspaper"></i>
                                </div>
                            <?php endif; ?>
                            <div class="blog-card-body">
                                <?php if ($post['category_name']): ?>
                                    <span class="blog-category-badge" style="font-size:10px;">
                                        <?= htmlspecialchars($post['category_name']) ?>
                                    </span>
                                <?php endif; ?>
                                <h3><?= htmlspecialchars($post['title']) ?></h3>
                                <?php if ($post['excerpt']): ?>
                                    <p><?= htmlspecialchars($post['excerpt']) ?></p>
                                <?php endif; ?>
                                <div class="blog-card-meta">
                                    <?= date('M d, Y', strtotime($post['created_at'])) ?>
                                </div>
                                <a href="blog_post.php?id=<?= $post['post_id'] ?>" class="blog-card-link">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <?php if (!empty($reviews)): ?>
        <section class="blog-reviews-section fade-in">
            <h2 class="blog-section-title">
                What They're Saying
                <span></span>
            </h2>
            <div class="reviews-grid">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card fade-in">
                        <div class="review-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fa-<?= $i <= $review['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <?php if ($review['comment']): ?>
                            <p class="review-comment">"<?= htmlspecialchars($review['comment']) ?>"</p>
                        <?php endif; ?>
                        <div class="review-meta">
                            <strong><?= htmlspecialchars($review['reviewer_name']) ?></strong>
                            &nbsp;·&nbsp; <?= htmlspecialchars($review['product_name']) ?>
                            &nbsp;·&nbsp; <?= date('M d, Y', strtotime($review['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <script src="../assets/js/HomepageAnimations.js"></script>
    <script>
        document.querySelectorAll('.filter-pill').forEach(pill => {
            pill.addEventListener('click', function () {
                document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');

                const selected = this.dataset.category;
                document.querySelectorAll('.blog-card').forEach(card => {
                    if (selected === 'all' || card.dataset.category === selected) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <?php include '../components/footer.php'; ?>
</body>
</html>