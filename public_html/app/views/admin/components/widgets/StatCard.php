<?php
function renderStatCard($title, $value, $subtitle = '', $color = 'primary', $icon = '📊') {
    $colors = [
        'primary' => 'linear-gradient(135deg, #667eea, #764ba2)',
        'success' => 'linear-gradient(135deg, #43e97b, #38f9d7)',
        'warning' => 'linear-gradient(135deg, #f093fb, #f5576c)',
        'info' => 'linear-gradient(135deg, #4facfe, #00f2fe)',
        'dark' => 'linear-gradient(135deg, #2c3e50, #34495e)'
    ];
    
    $colorStyle = $colors[$color] ?? $colors['primary'];
    ?>
    <div class="stat-card" style="background: <?= $colorStyle ?>">
        <div class="stat-icon"><?= $icon ?></div>
        <div class="stat-content">
            <div class="stat-value"><?= $value ?></div>
            <div class="stat-title"><?= $title ?></div>
            <?php if (!empty($subtitle)): ?>
                <div class="stat-subtitle"><?= $subtitle ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
?>