<?php
// Переменные из контроллера
/** @var array $stats */
/** @var array $user */

$pageTitle = "Дашборд";
$currentSection = "dashboard";

// Подключаем компоненты
require_once __DIR__ . '/../components/widgets/StatCard.php';
require_once __DIR__ . '/../components/widgets/RecentTable.php';
require_once __DIR__ . '/../components/widgets/QuickActions.php';

// Начинаем буферизацию вывода
ob_start();
?>

<div class="dashboard-header">
    <h1>Панель управления</h1>
    <p class="dashboard-subtitle">Добро пожаловать, <?= htmlspecialchars($user['first_name']) ?></p>
</div>

<!-- Основная статистика -->
<div class="widgets-grid">
    <div class="widget-full">
        <div class="stats-grid">
            <?php
            renderStatCard(
                'Кандидаты', 
                $stats['total_candidates'], 
                'Всего курьеров', 
                'primary', 
                '👥'
            );
            
            renderStatCard(
                'Активные курьеры', 
                $stats['active_candidates'], 
                'Работают сейчас', 
                'success', 
                '✅'
            );
            
            renderStatCard(
                'Сверки', 
                $stats['total_verifications'], 
                'Всего записей', 
                'warning', 
                '📋'
            );
            
            renderStatCard(
                'Общий заработок', 
                number_format($stats['total_earnings'], 0, '', ' ') . ' ₽', 
                'За все время', 
                'info', 
                '💰'
            );
            ?>
        </div>
    </div>

    <!-- Дополнительная статистика -->
    <div class="widget-half">
        <div class="stats-grid compact">
            <?php
            renderStatCard(
                'Всего часов', 
                number_format($stats['total_hours'], 1), 
                'Отработано', 
                'dark', 
                '⏱️'
            );
            
            renderStatCard(
                'Всего заказов', 
                number_format($stats['total_orders']), 
                'Выполнено', 
                'dark', 
                '📦'
            );
            
            renderStatCard(
                'Уникальные курьеры', 
                $stats['unique_couriers'], 
                'Всего', 
                'dark', 
                '👤'
            );
            
            renderStatCard(
                'За последнюю неделю', 
                $stats['recent_verifications_count'], 
                'Сверок', 
                'dark', 
                '📈'
            );
            ?>
        </div>
    </div>

    <!-- Быстрые действия -->
    <div class="widget-half">
        <?php renderQuickActions(); ?>
    </div>

    <!-- Последние сверки -->
    <div class="widget-full">
        <?php renderRecentTable(
            $stats['recent_verifications'], 
            'Последние записи сверок',
            'Нет данных о сверках'
        ); ?>
    </div>
</div>

<?php
// Получаем содержимое буфера
$content = ob_get_clean();

// Подключаем основной layout
include __DIR__ . '/../layout/admin.php';