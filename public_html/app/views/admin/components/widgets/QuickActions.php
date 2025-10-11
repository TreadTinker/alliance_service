<?php
function renderQuickActions($actions = []) {
    $defaultActions = [
        ['title' => 'Управление кандидатами', 'url' => '/admin/candidates', 'icon' => '👥'],
        ['title' => 'Активные курьеры', 'url' => '/admin/candidates?status=active', 'icon' => '✅'],
        ['title' => 'Данные сверок', 'url' => '/admin/verifications', 'icon' => '📊'],
        ['title' => 'Добавить курьера', 'url' => '/admin/candidates/add', 'icon' => '➕'],
        ['title' => 'Новая сверка', 'url' => '/admin/verifications/add', 'icon' => '📝']
    ];
    
    $actions = empty($actions) ? $defaultActions : $actions;
    ?>
    <div class="widget-card">
        <h3 class="widget-title">Быстрые действия</h3>
        <div class="actions-grid">
            <?php foreach ($actions as $action): ?>
                <a href="<?= $action['url'] ?>" class="action-card">
                    <div class="action-icon"><?= $action['icon'] ?></div>
                    <div class="action-title"><?= $action['title'] ?></div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}
?>