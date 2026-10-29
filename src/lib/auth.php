<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../locales/i18n.php';

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function currentUsername(): string {
    return $_SESSION['username'] ?? 'Admin';
}

function requireAuth(): void {
    if (!isLoggedIn()) {
        setFlash('warning', __('login_required'));
        redirect('login.php');
    }
}

function requireGuest(): void {
    if (isLoggedIn()) {
        redirect('index.php');
    }
}

function loginUser(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
}

function logoutUser(): void {
    $currentLang = $_SESSION['lang'] ?? 'id';
    
    unset($_SESSION['user_id'], $_SESSION['username']);
    $_SESSION['lang'] = $currentLang;

    setFlash('success', __('logout_success'));
    redirect('login.php');
}
