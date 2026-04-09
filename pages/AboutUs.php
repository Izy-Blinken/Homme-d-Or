<?php
include '../backend/db_connect.php';

$sections = [];
$result = mysqli_query($conn, "SELECT * FROM about_us_sections");
while ($row = mysqli_fetch_assoc($result)) {
    $sections[$row['section_key']] = $row;
}

$hero = $sections['hero'] ?? null;
$story = $sections['story'] ?? null;
$values = $sections['values'] ?? null;
$team = $sections['team'] ?? null;
$milestones = $sections['milestones'] ?? null;

$values_items = $values && $values['extra_data'] ? json_decode($values['extra_data'], true) : [];
$team_items = $team && $team['extra_data'] ? json_decode($team['extra_data'], true) : [];
$milestone_items = $milestones && $milestones['extra_data'] ? json_decode($milestones['extra_data'], true) : [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>About Us — Homme d'Or</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
    <link rel="stylesheet" href="../assets/css/AboutUsPageStyle.css">
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

        <?php if ($hero): ?>
        <div class="about-hero fade-in">
            <?php if ($hero['image_url']): ?>
                <img src="../assets/images/about/<?= htmlspecialchars($hero['image_url']) ?>"
                     alt="Hero" class="about-hero-bg">
            <?php endif; ?>
            <div class="about-hero-overlay"></div>
            <div class="about-hero-content">
                <h1><?= htmlspecialchars($hero['title'] ?? "The Art of Masculine Fragrance") ?></h1>
                <div class="about-hero-line"></div>
                <p><?= htmlspecialchars($hero['body'] ?? '') ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($story): ?>
        <div class="about-story fade-in">
            <div class="about-story-text">
                <div class="gold-line"></div>
                <h2><?= htmlspecialchars($story['title'] ?? 'Our Story') ?></h2>
                <p><?= nl2br(htmlspecialchars($story['body'] ?? '')) ?></p>
            </div>
            <?php if ($story['image_url']): ?>
                <img src="../assets/images/about/<?= htmlspecialchars($story['image_url']) ?>"
                     alt="Our Story" class="about-story-img">
            <?php else: ?>
                <div class="about-story-img-placeholder">
                    <i class="fa-solid fa-image"></i>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($values_items)): ?>
        <div class="about-values fade-in">
            <h2 class="about-section-title"><?= htmlspecialchars($values['title'] ?? 'Our Values') ?></h2>
            <div class="about-section-line"></div>
            <div class="values-grid">
                <?php foreach ($values_items as $v): ?>
                    <div class="value-card fade-in">
                        <i class="fa-solid <?= htmlspecialchars($v['icon'] ?? 'fa-gem') ?>"></i>
                        <h3><?= htmlspecialchars($v['title'] ?? '') ?></h3>
                        <p><?= htmlspecialchars($v['desc'] ?? '') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($team_items)): ?>
        <div class="about-team fade-in">
            <h2 class="about-section-title"><?= htmlspecialchars($team['title'] ?? 'Meet the Team') ?></h2>
            <div class="about-section-line"></div>
            <div class="team-grid">
                <?php foreach ($team_items as $member): ?>
                    <div class="team-card fade-in">
                        <?php if (!empty($member['photo'])): ?>
                            <img src="../assets/images/about/<?= htmlspecialchars($member['photo']) ?>"
                                 alt="<?= htmlspecialchars($member['name']) ?>"
                                 class="team-card-img">
                        <?php else: ?>
                            <div class="team-card-placeholder">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        <?php endif; ?>
                        <div class="team-card-info">
                            <h4><?= htmlspecialchars($member['name'] ?? '') ?></h4>
                            <p><?= htmlspecialchars($member['role'] ?? '') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($milestone_items)): ?>
        <div class="about-milestones fade-in">
            <h2 class="about-section-title"><?= htmlspecialchars($milestones['title'] ?? 'Our Journey') ?></h2>
            <div class="about-section-line"></div>
            <div class="timeline">
                <?php foreach ($milestone_items as $m): ?>
                    <div class="timeline-item fade-in">
                        <div class="timeline-dot"></div>
                        <div class="timeline-year"><?= htmlspecialchars($m['year'] ?? '') ?></div>
                        <div class="timeline-event"><?= htmlspecialchars($m['event'] ?? '') ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </main>

    <script src="../assets/js/HomepageAnimations.js"></script>
    <?php include '../components/footer.php'; ?>
</body>
</html>