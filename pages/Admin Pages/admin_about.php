<?php
session_start();
include '../../backend/db_connect.php';

if (empty($_SESSION['superadmin_id']) && empty($_SESSION['admin_id'])) {
    header('Location: adminLogin.php');
    exit;
}

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

$values_items = $values && $values['extra_data'] ? json_decode($values['extra_data'], true) : [['icon'=>'','title'=>'','desc'=>'']];
$team_items = $team && $team['extra_data'] ? json_decode($team['extra_data'], true) : [['name'=>'','role'=>'','photo'=>'']];
$milestone_items = $milestones && $milestones['extra_data'] ? json_decode($milestones['extra_data'], true) : [['year'=>'','event'=>'']];

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>About Us Management — Homme d'Or Admin</title>
    <link rel="stylesheet" href="../../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
    <style>
        .section-card { background:rgba(0,0,0,0.6); border:1px solid rgba(212,175,55,0.3); padding:36px; margin-bottom:30px; }
        .section-card h2 { font-size:1rem; font-weight:700; color:#c9a961; margin:0 0 24px; letter-spacing:2px; text-transform:uppercase; padding-bottom:12px; border-bottom:1px solid rgba(212,175,55,0.2); }
        .img-preview { display:none; max-width:200px; margin-top:10px; border:1px solid rgba(212,175,55,0.3); }
        .img-preview.show { display:block; }
        .repeater-row { display:grid; gap:12px; padding:16px; background:rgba(0,0,0,0.3); border:1px solid rgba(212,175,55,0.2); margin-bottom:12px; position:relative; }
        .repeater-row.values-row { grid-template-columns:180px 1fr 2fr 40px; align-items:start; }
        .repeater-row.team-row { grid-template-columns:1fr 1fr 200px 40px; align-items:start; }
        .repeater-row.milestone-row { grid-template-columns:120px 1fr 40px; align-items:start; }
        .repeater-row input { padding:8px 12px; border:1px solid rgba(212,175,55,0.3); background:rgba(0,0,0,0.4); color:#fff; font-size:13px; width:100%; box-sizing:border-box; }
        .repeater-row input:focus { border-color:#c9a961; outline:none; }
        .btn-remove { background:rgba(239,83,80,0.15); color:#ef5350; border:1px solid #ef5350; padding:8px 10px; cursor:pointer; font-size:13px; align-self:start; }
        .btn-remove:hover { background:#ef5350; color:#fff; }
        .btn-add { background:transparent; color:#c9a961; border:1px solid rgba(212,175,55,0.4); padding:9px 20px; font-size:13px; font-weight:600; cursor:pointer; margin-top:4px; transition:all 0.3s; }
        .btn-add:hover { background:rgba(212,175,55,0.1); }
        .repeater-label { display:block; font-size:11px; font-weight:600; color:#888; margin-bottom:4px; text-transform:uppercase; letter-spacing:1px; }
        .team-photo-preview { display:none; max-width:80px; margin-top:6px; border:1px solid rgba(212,175,55,0.3); }
        .team-photo-preview.show { display:block; }
        textarea.about-textarea { width:100%; background:rgba(0,0,0,0.4); border:1px solid rgba(212,175,55,0.3); color:#fff; padding:10px 14px; font-size:14px; resize:vertical; min-height:80px; outline:none; font-family:'Spartan',sans-serif; box-sizing:border-box; }
        textarea.about-textarea:focus { border-color:#c9a961; }
        input[type="file"] { color:#aaa; }
    </style>
</head>
<body>
    <?php include '../../components/adminSideBar.php'; ?>
    <?php include '../../components/adminNavbar.php'; ?>

    <div class="container" style="max-width:1000px;">

         
        <h1 class="page-title">About Us Management</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Hero Section -->
        <div class="section-card">
            <h2>Hero Section</h2>
            <form method="POST" action="../../backend/about/save_section.php" enctype="multipart/form-data">
                <input type="hidden" name="section_key" value="hero">
                <div class="form-group">
                    <label>Headline</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($hero['title'] ?? '') ?>" placeholder="Main heading" style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Subtext</label>
                    <textarea name="body" class="about-textarea" placeholder="Short tagline..."><?= htmlspecialchars($hero['body'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Background Image</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                        onchange="previewImg(this, 'heroPreview')">
                    <?php if ($hero && $hero['image_url']): ?>
                        <img src="../../assets/images/about/<?= htmlspecialchars($hero['image_url']) ?>"
                            class="img-preview show" id="heroPreview" alt="Hero Image">
                    <?php else: ?>
                        <img class="img-preview" id="heroPreview" alt="Preview">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-save">Save Hero</button>
            </form>
        </div>

        <!-- Brand Story -->
        <div class="section-card">
            <h2>Brand Story</h2>
            <form method="POST" action="../../backend/about/save_section.php" enctype="multipart/form-data">
                <input type="hidden" name="section_key" value="story">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($story['title'] ?? '') ?>" placeholder="e.g. Our Story" style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Body</label>
                    <textarea name="body" class="about-textarea" style="min-height:120px;"><?= htmlspecialchars($story['body'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                        onchange="previewImg(this, 'storyPreview')">
                    <?php if ($story && $story['image_url']): ?>
                        <img src="../../assets/images/about/<?= htmlspecialchars($story['image_url']) ?>"
                            class="img-preview show" id="storyPreview" alt="Story Image">
                    <?php else: ?>
                        <img class="img-preview" id="storyPreview" alt="Preview">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-save">Save Story</button>
            </form>
        </div>

        <!-- Our Values -->
        <div class="section-card">
            <h2>Our Values</h2>
            <form method="POST" action="../../backend/about/save_section.php">
                <input type="hidden" name="section_key" value="values">
                <div class="form-group">
                    <label>Section Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($values['title'] ?? 'Our Values') ?>" style="width:100%;">
                </div>
                <div id="valuesRepeater">
                    <?php foreach ($values_items as $v): ?>
                        <div class="repeater-row values-row">
                            <div>
                                <span class="repeater-label">FontAwesome Icon</span>
                                <input type="text" name="icon[]" value="<?= htmlspecialchars($v['icon'] ?? '') ?>" placeholder="e.g. fa-gem">
                            </div>
                            <div>
                                <span class="repeater-label">Title</span>
                                <input type="text" name="vtitle[]" value="<?= htmlspecialchars($v['title'] ?? '') ?>" placeholder="Value title">
                            </div>
                            <div>
                                <span class="repeater-label">Description</span>
                                <input type="text" name="desc[]" value="<?= htmlspecialchars($v['desc'] ?? '') ?>" placeholder="Short description">
                            </div>
                            <button type="button" class="btn-remove" onclick="removeRow(this)">✕</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn-add" onclick="addValueRow()">+ Add Value</button><br>
                <button type="submit" class="btn-save" style="margin-top:16px;">Save Values</button>
            </form>
        </div>

        <!-- Meet the Team -->
        <div class="section-card">
            <h2>Meet the Team</h2>
            <form method="POST" action="../../backend/about/save_section.php" enctype="multipart/form-data">
                <input type="hidden" name="section_key" value="team">
                <div class="form-group">
                    <label>Section Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($team['title'] ?? 'Meet the Team') ?>" style="width:100%;">
                </div>
                <div id="teamRepeater">
                    <?php foreach ($team_items as $i => $m): ?>
                        <div class="repeater-row team-row">
                            <div>
                                <span class="repeater-label">Name</span>
                                <input type="text" name="tname[]" value="<?= htmlspecialchars($m['name'] ?? '') ?>" placeholder="Full name">
                            </div>
                            <div>
                                <span class="repeater-label">Role</span>
                                <input type="text" name="role[]" value="<?= htmlspecialchars($m['role'] ?? '') ?>" placeholder="e.g. Lead Perfumer">
                            </div>
                            <div>
                                <span class="repeater-label">Photo</span>
                                <input type="file" name="photo[<?= $i ?>]" accept="image/jpeg,image/png,image/webp"
                                    onchange="previewTeamPhoto(this)">
                                <input type="hidden" name="existing_photo[]" value="<?= htmlspecialchars($m['photo'] ?? '') ?>">
                                <?php if (!empty($m['photo'])): ?>
                                    <img src="../../assets/images/about/<?= htmlspecialchars($m['photo']) ?>"
                                        class="team-photo-preview show" alt="Photo">
                                <?php else: ?>
                                    <img class="team-photo-preview" alt="Preview">
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn-remove" onclick="removeRow(this)">✕</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn-add" onclick="addTeamRow()">+ Add Member</button><br>
                <button type="submit" class="btn-save" style="margin-top:16px;">Save Team</button>
            </form>
        </div>

        <!-- Milestones -->
        <div class="section-card">
            <h2>Milestones</h2>
            <form method="POST" action="../../backend/about/save_section.php">
                <input type="hidden" name="section_key" value="milestones">
                <div class="form-group">
                    <label>Section Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($milestones['title'] ?? 'Our Journey') ?>" style="width:100%;">
                </div>
                <div id="milestonesRepeater">
                    <?php foreach ($milestone_items as $m): ?>
                        <div class="repeater-row milestone-row">
                            <div>
                                <span class="repeater-label">Year</span>
                                <input type="text" name="year[]" value="<?= htmlspecialchars($m['year'] ?? '') ?>" placeholder="2025">
                            </div>
                            <div>
                                <span class="repeater-label">Event</span>
                                <input type="text" name="event[]" value="<?= htmlspecialchars($m['event'] ?? '') ?>" placeholder="Describe the milestone">
                            </div>
                            <button type="button" class="btn-remove" onclick="removeRow(this)">✕</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn-add" onclick="addMilestoneRow()">+ Add Milestone</button><br>
                <button type="submit" class="btn-save" style="margin-top:16px;">Save Milestones</button>
            </form>
        </div>
    </div>

    <script>
    function removeRow(btn) {
        btn.closest('.repeater-row').remove();
    }

    function previewImg(input, previewId) {
        if (!input.files || !input.files[0]) return;
        const preview = document.getElementById(previewId);
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.add('show'); };
        reader.readAsDataURL(input.files[0]);
    }

    function previewTeamPhoto(input) {
        if (!input.files || !input.files[0]) return;
        const preview = input.closest('.repeater-row').querySelector('.team-photo-preview');
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.add('show'); };
        reader.readAsDataURL(input.files[0]);
    }

    function addValueRow() {
        const repeater = document.getElementById('valuesRepeater');
        const div = document.createElement('div');
        div.className = 'repeater-row values-row';
        div.innerHTML = `
            <div><span class="repeater-label">FontAwesome Icon</span><input type="text" name="icon[]" placeholder="e.g. fa-gem"></div>
            <div><span class="repeater-label">Title</span><input type="text" name="vtitle[]" placeholder="Value title"></div>
            <div><span class="repeater-label">Description</span><input type="text" name="desc[]" placeholder="Short description"></div>
            <button type="button" class="btn-remove" onclick="removeRow(this)">✕</button>
        `;
        repeater.appendChild(div);
    }

    let teamIndex = <?= count($team_items) ?>;
    function addTeamRow() {
        const repeater = document.getElementById('teamRepeater');
        const div = document.createElement('div');
        div.className = 'repeater-row team-row';
        div.innerHTML = `
            <div><span class="repeater-label">Name</span><input type="text" name="tname[]" placeholder="Full name"></div>
            <div><span class="repeater-label">Role</span><input type="text" name="role[]" placeholder="e.g. Lead Perfumer"></div>
            <div>
                <span class="repeater-label">Photo</span>
                <input type="file" name="photo[${teamIndex}]" accept="image/jpeg,image/png,image/webp" onchange="previewTeamPhoto(this)">
                <input type="hidden" name="existing_photo[]" value="">
                <img class="team-photo-preview" alt="Preview">
            </div>
            <button type="button" class="btn-remove" onclick="removeRow(this)">✕</button>
        `;
        repeater.appendChild(div);
        teamIndex++;
    }

    function addMilestoneRow() {
        const repeater = document.getElementById('milestonesRepeater');
        const div = document.createElement('div');
        div.className = 'repeater-row milestone-row';
        div.innerHTML = `
            <div><span class="repeater-label">Year</span><input type="text" name="year[]" placeholder="2025"></div>
            <div><span class="repeater-label">Event</span><input type="text" name="event[]" placeholder="Describe the milestone"></div>
            <button type="button" class="btn-remove" onclick="removeRow(this)">✕</button>
        `;
        repeater.appendChild(div);
    }
    </script>
    <script src="../../assets/js/AdminProfile.js" defer></script>
    <script src="../../assets/js/AdminPanel.js" defer></script>
    <script src="../../assets/js/script.js" defer></script>
</body>
</html>