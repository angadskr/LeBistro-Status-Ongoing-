<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Please log in to continue']);
        exit;
    }
}
?>