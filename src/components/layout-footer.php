<?php
require_once __DIR__ . '/../locales/i18n.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/auth.php';
?>
    <?php if (isLoggedIn()): ?>
        </main>
    </div>
    <?php endif; ?>

    <script src="assets/js/app.js"></script>
</body>
</html>
