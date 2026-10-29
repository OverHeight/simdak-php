<?php
require_once __DIR__ . "/src/config/database.php";
require_once __DIR__ . "/src/lib/auth.php";
require_once __DIR__ . "/src/lib/csrf.php";
require_once __DIR__ . "/src/lib/flash.php";
require_once __DIR__ . "/src/lib/helpers.php";
require_once __DIR__ . "/src/locales/i18n.php";

requireAuth();

$errorMessage = null;

$name = "";
$email = "";
$position = "";
$department = "";
$status = "Active";
$employmentType = "Full-Time";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";
    if (!verifyCsrfToken($token)) {
        $errorMessage = __("csrf_invalid");
    } else {
        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $position = trim($_POST["position"] ?? "");
        $department = trim($_POST["department"] ?? "");
        $status = trim($_POST["status"] ?? "Active");
        $employmentType = trim($_POST["employmentType"] ?? "Full-Time");

        $allowedStatuses = ["Active", "Probation", "Inactive"];
        $allowedTypes = ["Full-Time", "Contract", "Internship"];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = "Active";
        }
        if (!in_array($employmentType, $allowedTypes, true)) {
            $employmentType = "Full-Time";
        }

        if (
            $name === "" ||
            $email === "" ||
            $position === "" ||
            $department === ""
        ) {
            $errorMessage = __("all_fields_required");
        } elseif (
            mb_strlen($name) > 100 ||
            mb_strlen($email) > 100 ||
            mb_strlen($position) > 100 ||
            mb_strlen($department) > 100
        ) {
            $errorMessage = __("field_too_long");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = __("invalid_email");
        } else {
            $checkStmt = $pdo->prepare(
                "SELECT id FROM employees WHERE email = :email LIMIT 1",
            );
            $checkStmt->execute(["email" => $email]);
            if ($checkStmt->fetch()) {
                $errorMessage = __("email_already_used");
            } else {
                $insertStmt = $pdo->prepare("
                    INSERT INTO employees (name, email, position, department, status, employmentType)
                    VALUES (:name, :email, :position, :department, :status, :employmentType)
                ");

                $insertStmt->execute([
                    "name" => $name,
                    "email" => $email,
                    "position" => $position,
                    "department" => $department,
                    "status" => $status,
                    "employmentType" => $employmentType,
                ]);

                setFlash("success", __("employee_created"));
                redirect("index.php");
            }
        }
    }
}

$pageTitle = __("add_employee");
require_once __DIR__ . "/src/components/layout-header.php";
require_once __DIR__ . "/src/components/sidebar.php";
?>

<header class="main-content-header">
    <div class="page-headline">
        <h1><?= e(__("add_employee")) ?></h1>
        <p><?= currentLang() === "id"
            ? "Tambahkan karyawan baru ke dalam direktori perusahaan."
            : "Add a new team member to your organization directory." ?></p>
    </div>

    <a href="index.php" class="btn btn-secondary btn-sm">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span><?= e(__("back")) ?></span>
    </a>
</header>

<div class="content-body">
    <div class="form-card">
        <?php require_once __DIR__ . "/src/components/alert.php"; ?>

        <?php if ($errorMessage): ?>
            <div class="alert alert-danger">
                <span><?= e($errorMessage) ?></span>
            </div>
        <?php endif; ?>

        <form action="create.php" method="POST">
            <?= csrfField() ?>

            <div class="form-group">
                <label for="name" class="form-label"><?= e(
                    __("name"),
                ) ?> <span style="color: #ef4444;">*</span></label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="form-control"
                    placeholder="Contoh: Sultan Fadil"
                    value="<?= e($name) ?>"
                    maxlength="100"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="email" class="form-label"><?= e(
                    __("email"),
                ) ?> <span style="color: #ef4444;">*</span></label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control"
                    placeholder="Contoh: nama@company.com"
                    value="<?= e($email) ?>"
                    maxlength="100"
                    required
                >
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="position" class="form-label"><?= e(
                        __("position"),
                    ) ?> <span style="color: #ef4444;">*</span></label>
                    <input
                        type="text"
                        name="position"
                        id="position"
                        class="form-control"
                        placeholder="Contoh: Senior Software Engineer"
                        value="<?= e($position) ?>"
                        maxlength="100"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="department" class="form-label"><?= e(
                        __("department"),
                    ) ?> <span style="color: #ef4444;">*</span></label>
                    <input
                        type="text"
                        name="department"
                        id="department"
                        class="form-control"
                        placeholder="Contoh: Engineering"
                        value="<?= e($department) ?>"
                        maxlength="100"
                        required
                    >
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="employmentType" class="form-label"><?= e(
                        __("employment_type"),
                    ) ?></label>
                    <select name="employmentType" id="employmentType" class="form-control">
                        <option value="Full-Time" <?= $employmentType ===
                        "Full-Time"
                            ? "selected"
                            : "" ?>><?= e(__("type_full_time")) ?></option>
                        <option value="Contract" <?= $employmentType ===
                        "Contract"
                            ? "selected"
                            : "" ?>><?= e(__("type_contract")) ?></option>
                        <option value="Internship" <?= $employmentType ===
                        "Internship"
                            ? "selected"
                            : "" ?>><?= e(__("type_internship")) ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label"><?= e(
                        __("status"),
                    ) ?></label>
                    <select name="status" id="status" class="form-control">
                        <option value="Active" <?= $status === "Active"
                            ? "selected"
                            : "" ?>><?= e(__("status_active")) ?></option>
                        <option value="Probation" <?= $status === "Probation"
                            ? "selected"
                            : "" ?>><?= e(__("status_probation")) ?></option>
                        <option value="Inactive" <?= $status === "Inactive"
                            ? "selected"
                            : "" ?>><?= e(__("status_inactive")) ?></option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--border-color);">
                <a href="index.php" class="btn btn-secondary">
                    <?= e(__("cancel")) ?>
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.5rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span><?= e(__("save")) ?></span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . "/src/components/layout-footer.php"; ?>
