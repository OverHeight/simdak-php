<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../locales/i18n.php';
require_once __DIR__ . '/../lib/helpers.php';

$activeLang = currentLang();
$currentScript = basename($_SERVER['PHP_SELF']);
$userName = currentUsername();
$userInitials = getInitials($userName);
?>
<div class="app-layout">
    <aside class="app-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand-block">
                <span class="sidebar-brand-name">SIMDAK</span>
                <span class="sidebar-brand-tagline"><?= e(__('app_tagline')) ?></span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="index.php" class="sidebar-link <?= ($currentScript === 'index.php') ? 'active' : '' ?>">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span><?= e(__('dashboard')) ?></span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="lang-switcher sidebar-lang" role="group" aria-label="Language">
                <a href="?lang=id" class="lang-btn <?= $activeLang === 'id' ? 'active' : '' ?>">
                    <span class="lang-globe" aria-hidden="true">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                    </span>
                    <span class="lang-label">ID</span>
                </a>
                <a href="?lang=en" class="lang-btn <?= $activeLang === 'en' ? 'active' : '' ?>">
                    <span class="lang-globe" aria-hidden="true">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                    </span>
                    <span class="lang-label">EN</span>
                </a>
            </div>

            <div class="sidebar-user-card">
                <div class="sidebar-user-avatar">
                    <?= e($userInitials) ?>
                </div>
                <div class="sidebar-user-info">
                    <div class="username"><?= e($userName) ?></div>
                    <div class="role">Administrator</div>
                </div>
            </div>

            <a href="logout.php" class="btn btn-secondary btn-sm" style="background: rgba(255, 255, 255, 0.05); color: #f87171; border-color: rgba(255, 255, 255, 0.1); width: 100%;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <?= e(__('logout')) ?>
            </a>
        </div>
    </aside>

    <main class="app-main">
