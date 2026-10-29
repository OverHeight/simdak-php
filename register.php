<?php
require_once __DIR__ . "/src/config/database.php";
require_once __DIR__ . "/src/lib/auth.php";
require_once __DIR__ . "/src/lib/csrf.php";
require_once __DIR__ . "/src/lib/flash.php";
require_once __DIR__ . "/src/lib/helpers.php";
require_once __DIR__ . "/src/locales/i18n.php";

requireGuest();

$errorMessage = null;
$username = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";
    if (!verifyCsrfToken($token)) {
        $errorMessage = __("csrf_invalid");
    } else {
        $username = trim($_POST["username"] ?? "");
        $password = trim($_POST["password"] ?? "");
        $confirmPassword = trim($_POST["confirmPassword"] ?? "");

        if ($username === "" || $password === "" || $confirmPassword === "") {
            $errorMessage = __("all_fields_required");
        } elseif (
            strlen($username) < 3 ||
            strlen($username) > 50 ||
            !preg_match('/^[a-zA-Z0-9_]+$/', $username)
        ) {
            $errorMessage = __("username_min_length");
        } elseif (strlen($password) < 6 || strlen($password) > 255) {
            $errorMessage = __("password_min_length");
        } elseif ($password !== $confirmPassword) {
            $errorMessage = __("passwords_do_not_match");
        } else {
            $checkStmt = $pdo->prepare(
                "SELECT id FROM users WHERE username = :username LIMIT 1",
            );
            $checkStmt->execute(["username" => $username]);
            if ($checkStmt->fetch()) {
                $errorMessage = __("username_already_taken");
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $insertStmt = $pdo->prepare(
                    "INSERT INTO users (username, password) VALUES (:username, :password)",
                );
                $insertStmt->execute([
                    "username" => $username,
                    "password" => $hashedPassword,
                ]);

                setFlash("success", __("register_success"));
                redirect("login.php");
            }
        }
    }
}

$pageTitle = __("register_title");
require_once __DIR__ . "/src/components/layout-header.php";
?>

<div class="auth-page">
    <div class="auth-split-card">
        <div class="auth-form-side">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div class="auth-brand">SIMDAK</div>

                <div class="lang-switcher">
                    <a href="?lang=id" class="lang-btn <?= currentLang() ===
                    "id"
                        ? "active"
                        : "" ?>">ID</a>
                    <a href="?lang=en" class="lang-btn <?= currentLang() ===
                    "en"
                        ? "active"
                        : "" ?>">EN</a>
                </div>
            </div>

            <h1 class="auth-title"><?= currentLang() === "id"
                ? "Daftar Akun Baru"
                : "Create an Account" ?></h1>
            <p class="auth-subtitle"><?= currentLang() === "id"
                ? "Buat akun pengelola untuk mengelola direktori karyawan."
                : "Register a new administrator account to access the system." ?></p>

            <?php require_once __DIR__ . "/src/components/alert.php"; ?>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger">
                    <span><?= e($errorMessage) ?></span>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <?= csrfField() ?>

                <div class="form-group">
                    <label for="username" class="form-label"><?= e(
                        __("username"),
                    ) ?> <span style="color: #ef4444;">*</span></label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="<?= currentLang() === "id"
                            ? "Pilih username unik"
                            : "Choose a unique username" ?>"
                        value="<?= e($username) ?>"
                        maxlength="50"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="reg-password" class="form-label"><?= e(
                        __("password"),
                    ) ?> <span style="color: #ef4444;">*</span></label>
                    <div class="input-with-icon">
                        <input
                            type="password"
                            id="reg-password"
                            name="password"
                            class="form-control"
                            placeholder="<?= currentLang() === "id"
                                ? "Minimal 6 karakter"
                                : "At least 6 characters" ?>"
                            maxlength="255"
                            required
                        >
                        <button type="button" class="password-toggle-btn" data-target="reg-password" title="Lihat/Sembunyikan password">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="reg-confirm-password" class="form-label"><?= e(
                        __("confirm_password"),
                    ) ?> <span style="color: #ef4444;">*</span></label>
                    <div class="input-with-icon">
                        <input
                            type="password"
                            id="reg-confirm-password"
                            name="confirmPassword"
                            class="form-control"
                            placeholder="<?= currentLang() === "id"
                                ? "Ulangi password"
                                : "Confirm your password" ?>"
                            maxlength="255"
                            required
                        >
                        <button type="button" class="password-toggle-btn" data-target="reg-confirm-password" title="Lihat/Sembunyikan password">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.95rem; border-radius: var(--radius-md);">
                    <?= currentLang() === "id"
                        ? "Daftar Sekarang"
                        : "Register Account" ?>
                </button>

                <div style="text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">
                    <span><?= e(__("already_have_account")) ?></span>
                    <a href="login.php" style="color: var(--primary); font-weight: 600; text-decoration: none; margin-left: 4px;">
                        <?= e(__("login_here")) ?>
                    </a>
                </div>
            </form>
        </div>

        <?php require_once __DIR__ . "/src/components/auth-art.php"; ?>
    </div>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>
