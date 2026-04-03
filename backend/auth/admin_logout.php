<?php
session_start();
session_destroy();
header('Location: ../../pages/Admin Pages/adminLogin.php');
exit;