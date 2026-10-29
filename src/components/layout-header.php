<?php
require_once __DIR__ . '/../locales/i18n.php';
require_once __DIR__ . '/../lib/helpers.php';

$pageTitle = isset($pageTitle) ? $pageTitle . ' — ' . __('app_name') : __('app_name') . ' — ' . __('app_tagline');
?>
<!DOCTYPE html>
<html lang="<?= e(currentLang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
