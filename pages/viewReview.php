<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../backend/db_connect.php';

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if (!$product_id) {
    header("Location: index.php");
    exit;
}

// Fetch product info
$stmt = $conn->prepare("
    SELECT p.product_id, p.product_name, p.product_desc,
           pi.image_url
    FROM products p
    LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
    WHERE p.product_id = ?
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: index.php");
    exit;
}

$identity = getCurrentUserId();
$is_logged_in = ($identity['type'] === 'user_id');
$user_id = $is_logged_in ? intval($identity['id']) : 0;

// Check if user already has a review for this product
$existing_review = null;
if ($is_logged_in) {
    $chk = $conn->prepare("SELECT rating, comment FROM product_reviews WHERE user_id = ? AND product_id = ?");
    $chk->bind_param("ii", $user_id, $product_id);
    $chk->execute();
    $existing_review = $chk->get_result()->fetch_assoc();
    $chk->close();
}

$product_image = $product['image_url']
    ? '../assets/images/products/' . htmlspecialchars($product['image_url'])
    : '../assets/images/brand_images/nocturne.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - <?= htmlspecialchars($product['product_name']) ?> | Homme d'Or</title>
    <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
    <link rel="stylesheet" href="../assets/css/ReviewCancelOrderStyle.css">
    <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css">
    <style>
        .vr-helpful-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: flex-end;
            flex-shrink: 0;
        }
        .vr-like-btn {
            width: 2.4rem;
            height: 2.4rem;
            border-radius: 50%;
            border: 1px solid rgba(201, 169, 97, 0.3);
            background: rgba(201, 169, 97, 0.08);
            color: #c9a961;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .vr-like-btn:hover {
            background: rgba(201, 169, 97, 0.18);
            transform: translateY(-1px);
        }
        .vr-helpful-text {
            font-size: 0.85rem;
            color: #94a3b8;
            white-space: nowrap;
        }
        .vr-card-stars {
            margin-bottom: 1rem;
            display: flex;
            gap: 0.25rem;
        }
        .vr-card-stars i {
            color: #c9a961;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <?php include '../components/header.php'; ?>

    <main style="background-image: url('../assets/images/brand_images/bg-image.jpg');">
        <a href="javascript:history.back()" class="vr-back-btn">
            <i class="fa-solid fa-chevron-left"></i> Back
        </a>

        <section class="vr-section">
            <div class="vr-wrapper">

                <!-- Sidebar -->
                <aside class="vr-sidebar">
                    <img src="<?= $product_image ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="vr-sidebar-img">
                    <h2 class="vr-product-name"><?= htmlspecialchars($product['product_name']) ?></h2>
                    <div class="vr-score-wrap">
                        <span class="vr-avg-score" id="vrAvgScore">—</span>
                        <div class="vr-stars" id="vrAvgStars"></div>
                    </div>
                    <p class="vr-review-count" id="vrReviewCount">Loading...</p>
                    <div class="vr-breakdown" id="vrBreakdown"></div>

                    
                </aside>

                <!-- Feed -->
                <div class="vr-feed">
                    <div class="vr-feed-header">
                        <h3>What our clients are saying</h3>
                        <select class="vr-filter" id="vrFilter">
                            <option value="newest">Newest First</option>
                            <option value="highest">Highest Rated</option>
                            <option value="lowest">Lowest Rated</option>
                        </select>
                    </div>

                    <div id="vrReviewList" class="vr-review-list">
                        <p style="color:#aaa; text-align:center; padding:2rem;">
                            Loading reviews...
                        </p>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <!-- Review Modal -->
    <div class="romcomOverlay" id="reviewModal" role="dialog" aria-modal="true">
        <div class="romcomModalContent">
            <div class="romcomHeader">
                <h2><?= $existing_review ? 'Edit Review' : 'Write a Review' ?></h2>
            </div>
            <div class="romcomDivider"></div>
            <div class="romcomBody">
                <p class="modal-description">
                    Reviewing: <strong><?= htmlspecialchars($product['product_name']) ?></strong>
                </p>

                <div class="romcomFormGroup">
                    <label>Your Rating</label>
                    <div class="star-rating" id="starRating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?= ($existing_review && $existing_review['rating'] >= $i) ? 'active' : '' ?>"
                              data-value="<?= $i ?>">
                            <i class="fa-solid fa-star"></i>
                        </span>
                        <?php endfor; ?>
                    </div>
                    <p class="rating-Text" id="ratingText">
                        <?php
                        $labels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
                        echo $existing_review ? $labels[$existing_review['rating']] : 'Select a rating';
                        ?>
                    </p>
                    <input type="hidden" id="selectedRating" value="<?= $existing_review ? $existing_review['rating'] : 0 ?>">
                </div>

                <div class="romcomFormGroup">
                    <label>Your Review <span style="font-weight:400; color:#94a3b8;">(optional)</span></label>
                    <textarea id="reviewComment" placeholder="Share your experience with this product..."><?= $existing_review ? htmlspecialchars($existing_review['comment']) : '' ?></textarea>
                </div>

                <div class="romcomButtonGroup">
                    <button class="romcomBtnClose" id="closeReviewModal">Cancel</button>
                    <button class="romcomBtnSubmit" id="submitReviewBtn"
                        <?= (!$existing_review) ? 'disabled' : '' ?>>
                        <?= $existing_review ? 'Update Review' : 'Submit Review' ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include '../components/footer.php'; ?>

    <script>
    const RATING_LABELS = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

    //  Modal open/close 
    const modal = document.getElementById('reviewModal');
    const openBtn = document.getElementById('openReviewModal');
    const closeBtn = document.getElementById('closeReviewModal');

    function openModal() {
        modal.style.display = 'flex';
        // Force reflow then add class so animation runs
        modal.offsetHeight;
        modal.classList.add('show');
        modal.classList.remove('closing');
    }

    function closeModal() {
        modal.classList.add('closing');
        modal.classList.remove('show');
        modal.addEventListener('animationend', function handler() {
            modal.style.display = 'none';
            modal.classList.remove('closing');
            modal.removeEventListener('animationend', handler);
        });
    }

    if (openBtn) openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    // Close on overlay click (not on modal content)
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) closeModal();
    });

    //  Star rating interaction 
    const stars = document.querySelectorAll('#starRating .star');
    const selectedRatingInput = document.getElementById('selectedRating');
    const ratingText = document.getElementById('ratingText');
    const submitBtn = document.getElementById('submitReviewBtn');

    stars.forEach(star => {
        star.addEventListener('mouseenter', function () {
            const val = parseInt(this.dataset.value);
            stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.value) <= val));
        });

        star.addEventListener('mouseleave', function () {
            const current = parseInt(selectedRatingInput.value);
            stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.value) <= current));
        });

        star.addEventListener('click', function () {
            const val = parseInt(this.dataset.value);
            selectedRatingInput.value = val;
            ratingText.textContent = RATING_LABELS[val];
            stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.value) <= val));
            submitBtn.disabled = false;
        });
    });

    // Submit review (static preview only)
    submitBtn && submitBtn.addEventListener('click', function () {
        alert('Review submission is not available in this static preview.');
    });
    </script>

    <script>
    const PRODUCT_ID = <?= (int)$product_id ?>;
    let allReviews = [];

    function maskName(fname, lname) {
        const mask = s => s.length <= 2
            ? s[0] + '*'
            : s[0] + '*'.repeat(s.length - 2) + s[s.length - 1];
        return mask(fname) + ' ' + mask(lname);
    }

    function renderStars(rating) {
        let html = '';
        for (let i = 1; i <= 5; i++) {
            if (rating >= i) html += '<i class="fa-solid fa-star"></i>';
            else if (rating >= i - 0.5) html += '<i class="fa-solid fa-star-half-stroke"></i>';
            else html += '<i class="fa-regular fa-star"></i>';
        }
        return html;
    }

    function formatDate(dateStr) {
        return new Date(dateStr).toLocaleDateString('en-US', {
            month: 'long', day: 'numeric', year: 'numeric'
        });
    }

    function renderReviews(reviews) {
        const list = document.getElementById('vrReviewList');
        if (!reviews || reviews.length === 0) {
            list.innerHTML = '<p style="color:#aaa; text-align:center; padding:2rem;">No reviews yet.</p>';
            return;
        }
        list.innerHTML = reviews.map(r => {
            const initials = (r.fname[0] + r.lname[0]).toUpperCase();
            const masked = maskName(r.fname, r.lname);
            return `
            <div class="vr-card">
                <div class="vr-card-header">
                    <div class="vr-user-info">
                        <div class="vr-avatar">${initials}</div>
                        <div class="vr-name-date">
                            <h4>${masked} <i class="fa-solid fa-circle-check vr-verified" title="Verified Buyer"></i></h4>
                            <span>${formatDate(r.created_at)}</span>
                        </div>
                    </div>
                    <div class="vr-helpful-row">
                        <button class="vr-like-btn" aria-label="Like review">
                            <i class="fa-regular fa-thumbs-up"></i>
                        </button>
                        <span class="vr-helpful-text">(0) users found this review helpful</span>
                    </div>
                </div>
                <div class="vr-card-stars">${renderStars(r.rating)}</div>
                <p class="vr-card-body">${r.comment || ''}</p>
            </div>`;
        }).join('');
    }

    function renderSidebar(stats) {
        document.getElementById('vrAvgScore').textContent = stats.total > 0 ? stats.average : '—';
        document.getElementById('vrAvgStars').innerHTML = stats.total > 0
            ? renderStars(stats.average)
            : '<i class="fa-regular fa-star"></i>'.repeat(5);
        document.getElementById('vrReviewCount').textContent = stats.total > 0
            ? 'Based on ' + stats.total + ' reviews'
            : 'No reviews yet';

        const breakdown = document.getElementById('vrBreakdown');
        breakdown.innerHTML = [5,4,3,2,1].map(star => {
            const count = stats.breakdown[star] || 0;
            const pct = stats.total > 0 ? Math.round((count / stats.total) * 100) : 0;
            return `
            <div class="vr-bar-row">
                <span class="vr-bar-label">${star} <i class="fa-solid fa-star"></i></span>
                <div class="vr-bar-track">
                    <div class="vr-bar-fill" style="width:${pct}%"></div>
                </div>
                <span class="vr-bar-count">${count}</span>
            </div>`;
        }).join('');
    }

    function sortReviews(reviews, mode) {
        const sorted = [...reviews];
        if (mode === 'highest') sorted.sort((a, b) => b.rating - a.rating);
        else if (mode === 'lowest') sorted.sort((a, b) => a.rating - b.rating);
        else sorted.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        return sorted;
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetch('../backend/products/get_reviews.php?product_id=' + PRODUCT_ID)
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                allReviews = data.reviews;
                renderSidebar(data.stats);
                renderReviews(sortReviews(allReviews, 'newest'));
            })
            .catch(() => {
                document.getElementById('vrReviewList').innerHTML =
                    '<p style="color:#aaa; text-align:center;">Failed to load reviews.</p>';
            });

        document.getElementById('vrFilter').addEventListener('change', function() {
            renderReviews(sortReviews(allReviews, this.value));
        });
    });
    </script>
</body>
</html>