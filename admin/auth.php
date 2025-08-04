<?php
session_start();

// Check if user is logged in
function checkAuth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
    
    // Check session timeout (24 hours)
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 86400) {
        session_destroy();
        header('Location: login.php?timeout=1');
        exit;
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
}

// Logout function
function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Handle logout request
if (isset($_GET['logout'])) {
    logout();
}
?>