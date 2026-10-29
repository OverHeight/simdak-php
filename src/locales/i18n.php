<?php
require_once __DIR__ . '/../lib/session.php';

if (isset($_GET['lang']) && in_array($_GET['lang'], ['id', 'en'], true)) {
    $_SESSION['lang'] = $_GET['lang'];

    $params = $_GET;
    unset($params['lang']);
    $queryString = http_build_query($params);
    $script = strtok($_SERVER['REQUEST_URI'], '?');
    $redirectUrl = $script . ($queryString ? '?' . $queryString : '');
    
    header("Location: {$redirectUrl}");
    exit;
}

function currentLang(): string {
    return $_SESSION['lang'] ?? 'id';
}

function getTranslations(): array {
    static $translations = null;
    if ($translations === null) {
        $lang = currentLang();
        $filePath = __DIR__ . "/{$lang}.php";
        if (file_exists($filePath)) {
            $translations = require $filePath;
        } else {
            $translations = require __DIR__ . '/id.php';
        }
    }
    return $translations;
}

function __(string $key, ?string $fallback = null): string {
    $dict = getTranslations();
    return $dict[$key] ?? ($fallback ?? $key);
}
