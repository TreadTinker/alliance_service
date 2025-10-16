<?php
// Переменные: $candidate, $verifications
?>
<style>
    .verification-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        text-align: center;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .verification-table {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .table-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .table-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f8fafc;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.875rem;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .data-table tr:hover {
        background: #f8fafc;
    }

    .no-data {
        text-align: center;
        color: #6b7280;
        padding: 2rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
    }

    .back-link:hover {
        color: #4f46e5;
    }
</style>

<div class="admin-header">
    <div class="admin-header-content">
        <h1>Данные сверок</h1>
        <p>История сверок для курьера: <?= htmlspecialchars($candidate['full_name']) ?></p>
    </div>
</div>

<div class="admin-content">
    <a href="/admin/candidates" class="back-link">← Назад к списку кандидатов</a>
    <a href="/admin/candidates/<?= $candidate['courier_id'] ?>" class="back-link" style="margin-left: 1rem;">← Назад к профилю</a>

    <!-- Статистика -->
    <div class="verification-stats">
        <div class="stat-card">
            <div class="stat-value"><?= count($verifications) ?></div>
            <div class="stat-label">Всего сверок</div>
        </div>
        <?php
        $totalHours = array_sum(array_column($verifications, 'worked_hours'));
        $totalOrders = array_sum(array_column($verifications, 'orders_count'));
        $totalEarnings = array_sum(array_column($verifications, 'total_amount'));
        ?>
        <div class="stat-card">
            <div class="stat-value"><?= number_format($totalHours, 1) ?></div>
            <div class="stat-label">Всего часов</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format($totalOrders) ?></div>
            <div class="stat-label">Всего заказов</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format($totalEarnings, 0, '', ' ') ?> ₽</div>
            <div class="stat-label">Общий заработок</div>
        </div>
    </div>

    <!-- Таблица сверок -->
    <div class="verification-table">
        <div class="table-header">
            <h3>История сверок</h3>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Дата записи</th>
                        <th>Отработано часов</th>
                        <th>Количество заказов</th>
                        <th>Сумма</th>
                        <th>Дата создания</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($verifications)): ?>
                        <tr>
                            <td colspan="5" class="no-data">Нет данных о сверках</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($verifications as $verification): ?>
                            <tr>
                                <td><?= htmlspecialchars($verification['record_date']) ?></td>
                                <td><?= number_format($verification['worked_hours'], 1) ?></td>
                                <td><?= number_format($verification['orders_count']) ?></td>
                                <td><?= number_format($verification['total_amount'], 0, '', ' ') ?> ₽</td>
                                <td><?= htmlspecialchars($verification['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>