<?php
function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void {
    header("Location: {$url}");
    exit;
}

function getInitials(string $name): string {
    $words = preg_split('/\s+/', trim($name));
    if (empty($words) || empty($words[0])) {
        return 'EM';
    }
    
    if (count($words) === 1) {
        return strtoupper(substr($words[0], 0, 2));
    }
    
    return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
}

function getAvatarColor(string $string): array {
    $palettes = [
        ['bg' => '#e2e8f0', 'text' => '#334155'],
        ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
        ['bg' => '#e0f2fe', 'text' => '#0369a1'],
        ['bg' => '#dcfce7', 'text' => '#15803d'],
        ['bg' => '#fef3c7', 'text' => '#b45309'],
        ['bg' => '#fae8ff', 'text' => '#86198f'],
        ['bg' => '#f1f5f9', 'text' => '#475569'],
        ['bg' => '#ffe4e6', 'text' => '#be123c'],
    ];
    $index = abs(crc32($string)) % count($palettes);
    return $palettes[$index];
}
