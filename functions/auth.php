<?php

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getCurrentUserName() {
    return $_SESSION['user_name'] ?? null;
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 2;
}

function logout() {
    session_unset();
    session_destroy();
}