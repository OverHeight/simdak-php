<?php
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/lib/auth.php';
require_once __DIR__ . '/src/lib/csrf.php';
require_once __DIR__ . '/src/lib/flash.php';
require_once __DIR__ . '/src/lib/helpers.php';
require_once __DIR__ . '/src/locales/i18n.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$token = $_POST['csrf_token'] ?? '';
if (!verifyCsrfToken($token)) {
    setFlash('danger', __('csrf_invalid'));
    redirect('index.php');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$id) {
    setFlash('danger', __('invalid_id'));
    redirect('index.php');
}

$checkStmt = $pdo->prepare("SELECT id FROM employees WHERE id = :id LIMIT 1");
$checkStmt->execute(['id' => $id]);
if (!$checkStmt->fetch()) {
    setFlash('danger', __('employee_not_found'));
    redirect('index.php');
}

$deleteStmt = $pdo->prepare("DELETE FROM employees WHERE id = :id");
$deleteStmt->execute(['id' => $id]);

setFlash('success', __('employee_deleted'));
redirect('index.php');
