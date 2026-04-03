<?php

session_start();

if (isset($_SESSION['superadmin_id']) || isset($_SESSION['admin_id'])) {
    header('Location: adminSide.php');
    exit;
}

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

?>

<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <title>Admin Login — Homme D'or</title>
        <link rel="stylesheet" href="../../assets/css/AdminPanelStyle.css">
    </head>

    <body style="display:flex; align-items:center; justify-content:center; min-height:100vh; background:whitesmoke;">

        <div style="background:white; padding:2.5rem; width:100%; max-width:400px; box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            
        <h2 style="margin-bottom:0.25rem;">ADMIN PANEL</h2>
            <p style="color:#888; font-size:0.9rem; margin-bottom:2rem;">Homme D'or</p>

            <?php if ($error): ?>

                <div style="background:#fff0f0; border:1px solid #f5c2c2; color:#c00;
                    padding:0.75rem 1rem; margin-bottom:1.25rem; font-size:0.88rem;">
                    <?= htmlspecialchars($error) ?>
                </div>
                
            <?php endif; ?>

            <form method="POST" action="../../backend/auth/admin_login.php">

                <div class="form-group">
                    <label>USERNAME</label>
                    <input type="text" name="username" required placeholder="Enter username" autofocus>
                </div>

                <div class="form-group">
                    <label>PASSWORD</label>
                    <input type="password" name="password" required placeholder="Enter password">
                </div>

                <button type="submit" class="btn-save" style="width:100%; padding:10px; margin-top:0.5rem;">
                    Login
                </button>

            </form>
        </div>

    </body>
</html>