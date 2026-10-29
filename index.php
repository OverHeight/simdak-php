<?php
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/lib/auth.php';
require_once __DIR__ . '/src/lib/csrf.php';
require_once __DIR__ . '/src/lib/helpers.php';
require_once __DIR__ . '/src/locales/i18n.php';

requireAuth();

$deptStmt = $pdo->query("SELECT DISTINCT department FROM employees WHERE department IS NOT NULL AND department != '' ORDER BY department ASC");
$availableDepartments = $deptStmt->fetchAll(PDO::FETCH_COLUMN);

$search = trim($_GET['q'] ?? '');
$filterDept = trim($_GET['department'] ?? '');
$filterStatus = trim($_GET['status'] ?? '');

$sql = "SELECT * FROM employees WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (name LIKE :search_name OR email LIKE :search_email OR position LIKE :search_pos)";
    $searchWildcard = "%{$search}%";
    $params['search_name'] = $searchWildcard;
    $params['search_email'] = $searchWildcard;
    $params['search_pos'] = $searchWildcard;
}

if ($filterDept !== '') {
    $sql .= " AND department = :dept";
    $params['dept'] = $filterDept;
}

if ($filterStatus !== '') {
    $sql .= " AND status = :status";
    $params['status'] = $filterStatus;
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$employees = $stmt->fetchAll();

$totalStmt = $pdo->query("SELECT COUNT(*) FROM employees");
$totalEmployees = (int) $totalStmt->fetchColumn();
$isFiltered = ($search !== '' || $filterDept !== '' || $filterStatus !== '');

$pageTitle = __('dashboard');
require_once __DIR__ . '/src/components/layout-header.php';
require_once __DIR__ . '/src/components/sidebar.php';
?>

<header class="main-content-header">
    <div class="page-headline">
        <h1><?= currentLang() === 'id' ? 'Daftar Karyawan' : 'Employees' ?></h1>
        <p><?= e(__('showing')) ?> <strong><?= count($employees) ?></strong> <?= e(__('of')) ?> <strong><?= $totalEmployees ?></strong> <?= e(__('employees_count')) ?></p>
    </div>

    <!-- Create Button (+ Create in purple) -->
    <a href="create.php" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-size: 0.9rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        <span><?= currentLang() === 'id' ? 'Tambah Karyawan' : 'Create' ?></span>
    </a>
</header>

<div class="content-body">
    <?php require_once __DIR__ . '/src/components/alert.php'; ?>

    <!-- Toolbar: Search Bar & Filters (Ref Image 2) -->
    <div class="toolbar-container">
        <form action="index.php" method="GET" class="toolbar-top">
            <!-- Search Box with Search Icon -->
            <div class="search-field-wrapper">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input 
                    type="text" 
                    name="q" 
                    id="search-input"
                    placeholder="<?= currentLang() === 'id' ? 'Cari berdasarkan nama, email, jabatan...' : 'Search by name, email, position...' ?>" 
                    value="<?= e($search) ?>"
                >
                <span class="kbd">/</span>
            </div>

            <!-- Filters Dropdowns -->
            <div class="toolbar-filters">
                <select name="department" class="filter-select auto-submit-filter">
                    <option value=""><?= e(__('filter_department')) ?></option>
                    <?php foreach ($availableDepartments as $dept): ?>
                        <option value="<?= e($dept) ?>" <?= $filterDept === $dept ? 'selected' : '' ?>>
                            <?= e($dept) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="status" class="filter-select auto-submit-filter">
                    <option value=""><?= e(__('filter_status')) ?></option>
                    <option value="Active" <?= $filterStatus === 'Active' ? 'selected' : '' ?>><?= e(__('status_active')) ?></option>
                    <option value="Probation" <?= $filterStatus === 'Probation' ? 'selected' : '' ?>><?= e(__('status_probation')) ?></option>
                    <option value="Inactive" <?= $filterStatus === 'Inactive' ? 'selected' : '' ?>><?= e(__('status_inactive')) ?></option>
                </select>

                <?php if ($isFiltered): ?>
                    <a href="index.php" class="btn btn-secondary btn-sm" style="color: var(--danger);">
                        <?= e(__('reset_filter')) ?>
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Active Filter Chips (Ref Image 2: Filters: Position: CMO x, Interest: AI x) -->
        <?php if ($isFiltered): ?>
            <div class="active-chips-bar">
                <span>Filters:</span>
                <?php if ($search !== ''): ?>
                    <div class="filter-chip">
                        <span>Keyword: "<?= e($search) ?>"</span>
                        <a href="index.php?department=<?= urlencode($filterDept) ?>&status=<?= urlencode($filterStatus) ?>">×</a>
                    </div>
                <?php endif; ?>

                <?php if ($filterDept !== ''): ?>
                    <div class="filter-chip">
                        <span>Dept: <?= e($filterDept) ?></span>
                        <a href="index.php?q=<?= urlencode($search) ?>&status=<?= urlencode($filterStatus) ?>">×</a>
                    </div>
                <?php endif; ?>

                <?php if ($filterStatus !== ''): ?>
                    <div class="filter-chip">
                        <span>Status: <?= e($filterStatus) ?></span>
                        <a href="index.php?q=<?= urlencode($search) ?>&department=<?= urlencode($filterDept) ?>">×</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Table Card (Ref Image 2) -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="saas-table">
                <thead>
                    <tr>
                        <th><?= e(__('name')) ?></th>
                        <th><?= e(__('department')) ?></th>
                        <th><?= e(__('employment_type')) ?></th>
                        <th><?= e(__('status')) ?></th>
                        <th style="text-align: right;"><?= e(__('actions')) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($employees)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 4rem 1rem; color: var(--text-muted);">
                                <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">🔍</div>
                                <p style="font-size: 1rem; font-weight: 500;"><?= e(__('no_data_found')) ?></p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($employees as $emp): 
                            $avatarColor = getAvatarColor($emp['name']);
                            $initials = getInitials($emp['name']);
                            
                            $statusCatClass = 'inactive-cat';
                            if ($emp['status'] === 'Active') $statusCatClass = 'active-cat';
                            if ($emp['status'] === 'Probation') $statusCatClass = 'probation-cat';
                        ?>
                            <tr>
                                <!-- Name Column: Avatar Circle with Verified Checkmark + Name & Position -->
                                <td>
                                    <div class="employee-profile-box">
                                        <div class="avatar-wrapper">
                                            <div class="avatar-circle" style="background-color: <?= $avatarColor['bg'] ?>; color: <?= $avatarColor['text'] ?>;">
                                                <?= e($initials) ?>
                                            </div>
                                            <!-- Blue Verified Badge Checkmark (Ref Image 2) -->
                                            <div class="verified-dot" title="Verified Employee">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="name"><?= e($emp['name']) ?></div>
                                            <div class="subtitle"><?= e($emp['position']) ?> • <?= e($emp['email']) ?></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Department Column -->
                                <td>
                                    <span style="font-weight: 500; color: #374151;"><?= e($emp['department']) ?></span>
                                </td>

                                <!-- Employment Type Column (Ref Image 2: Blue Uppercase like CUSTOMER / PROSPECT) -->
                                <td>
                                    <span class="type-pill">
                                        <?= e($emp['employmentType'] ?? 'Full-Time') ?>
                                    </span>
                                </td>

                                <!-- Status Column (Ref Image 2: Clean Category Pill with colored vertical bar) -->
                                <td>
                                    <div class="chip-group">
                                        <span class="category-chip <?= $statusCatClass ?>">
                                            <span class="bar"></span>
                                            <?php
                                                if ($emp['status'] === 'Active') echo e(__('status_active'));
                                                elseif ($emp['status'] === 'Probation') echo e(__('status_probation'));
                                                else echo e(__('status_inactive'));
                                            ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Actions Column -->
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; align-items: center; gap: 0.4rem;">
                                        <a href="edit.php?id=<?= (int)$emp['id'] ?>" class="btn btn-secondary btn-sm" title="<?= e(__('edit_employee')) ?>">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            <span><?= e(__('actions')) ? currentLang() === 'id' ? 'Edit' : 'Edit' : 'Edit' ?></span>
                                        </a>

                                        <button 
                                            type="button" 
                                            class="btn btn-danger-outline btn-sm btn-trigger-delete" 
                                            data-id="<?= (int)$emp['id'] ?>" 
                                            data-name="<?= e($emp['name']) ?>"
                                            title="<?= e(__('delete_employee')) ?>"
                                        >
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal Dialog -->
<div class="modal-overlay" id="delete-modal">
    <div class="modal-box">
        <div class="modal-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3><?= e(__('delete_employee')) ?></h3>
        <p>
            <?= e(__('confirm_delete')) ?><br>
            <strong id="delete-employee-name" style="color: var(--text-primary); font-size: 1rem;"></strong>
        </p>

        <form action="delete.php" method="POST" id="delete-form">
            <?= csrfField() ?>
            <input type="hidden" name="id" id="delete-employee-id" value="">

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" id="cancel-delete-btn">
                    <?= e(__('cancel')) ?>
                </button>
                <button type="submit" class="btn btn-danger">
                    <?= e(__('delete_employee')) ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/src/components/layout-footer.php'; ?>
