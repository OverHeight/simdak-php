<?php
require_once __DIR__ . '/../lib/flash.php';
require_once __DIR__ . '/../lib/helpers.php';

$flash = getFlash();
if ($flash):
    $alertClass = 'alert-info';
    if ($flash['type'] === 'success') $alertClass = 'alert-success';
    if ($flash['type'] === 'danger')  $alertClass = 'alert-danger';
    if ($flash['type'] === 'warning') $alertClass = 'alert-warning';
?>
    <div class="alert <?= e($alertClass) ?>">
        <span><?= e($flash['message']) ?></span>
    </div>
<?php endif; ?>
