<?php
$navStore = mysqli_fetch_assoc(mysqli_query($conn, "SELECT brand_name, logo FROM store_settings WHERE id = 1"));
$navBrandName = $navStore['brand_name'] ?? 'H';
$navLogo = $navStore['logo'] ?? null;

$words = array_filter(explode(' ', $navBrandName));
$initials = '';
foreach ($words as $w) {
    $initials .= strtoupper($w[0]);
    if (strlen($initials) >= 2) break;
}
if (empty($initials)) $initials = 'H';
?>

<header class="navbar">

    <div class="navbar-left">
        <button class="hamburger" id="menu-btn">
            <span></span><span></span><span></span>
        </button>
        <h1 class="navbar-title">ADMIN PANEL</h1>
    </div>

    <div class="navbar-search" style="position:relative;">
        <svg width="16" height="16" fill="none" stroke="#888" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" id="navbar-search-input" placeholder="Search customers or products..." autocomplete="off">

        <!-- Dropdown results -->
        <div id="navbar-search-results" style="
            display:none;
            position:absolute;
            top:calc(100% + 6px);
            left:0;
            right:0;
            background:#fff;
            border:1px solid #ddd;
            border-radius:8px;
            box-shadow:0 4px 16px rgba(0,0,0,0.12);
            z-index:9999;
            max-height:400px;
            overflow-y:auto;
        "></div>
    </div>

    <div class="navbar-avatar">
        <?php if ($navLogo): ?>
            <img src="../../assets/images/store_images/<?= htmlspecialchars($navLogo) ?>"
                 alt="<?= htmlspecialchars($navBrandName) ?>"
                 style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
        <?php else: ?>
            <?= htmlspecialchars($initials) ?>
        <?php endif; ?>
    </div>

    <script src="../../assets/js/script.js"></script>

</header>


