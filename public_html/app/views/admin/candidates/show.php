<?php
// Переменные: $candidate, $verifications, $stats
?>
<div class="admin-header">
    <div class="admin-header-content">
        <div style="display: flex; justify-content: between; align-items: start;">
            <div>
                <h1><?= htmlspecialchars($candidate['full_name']) ?></h1>
                <p>Детальная информация по курьеру</p>
            </div>
            <a href="/admin/candidates" class="btn btn-outline">← Назад к списку</a>
        </div>
    </div>
</div>

<div class="admin-content">
    <!-- Основная информация -->
    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-card">
            <div class="stat-icon primary">👤</div>
            <div class="stat-value"><?= htmlspecialchars($candidate['courier_id']) ?></div>
            <div class="stat-label">ID Курьера</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">⏱️</div>
            <div class="stat-value"><?= number_format($stats['total_hours'], 1) ?></div>
            <div class="stat-label">Всего часов</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">📦</div>
            <div class="stat-value"><?= number_format($stats['total_orders']) ?></div>
            <div class="stat-label">Всего заказов</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon danger">💰</div>
            <div class="stat-value"><?= number_format($stats['total_earnings'], 0, '', ' ') ?> ₽</div>
            <div class="stat-label">Общий заработок</div>
        </div>
    </div>

    <div class="sections-grid">
        <!-- Левая колонка - информация о курьере -->
        <div class="section-card">
            <div class="section-header">
                <h3>Информация о курьере</h3>
            </div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <label>ФИО:</label>
                        <span><?= htmlspecialchars($candidate['full_name']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Город:</label>
                        <span><?= htmlspecialchars($candidate['city']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Телефон:</label>
                        <a href="tel:<?= htmlspecialchars($candidate['phone_number']) ?>">
                            <?= htmlspecialchars($candidate['phone_number']) ?>
                        </a>
                    </div>
                    <div class="info-item">
                        <label>Менеджер:</label>
                        <span><?= htmlspecialchars($candidate['manager_name']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Отдел:</label>
                        <span class="badge badge-outline"><?= htmlspecialchars($candidate['department']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Статус:</label>
                        <?php
                        $statusColors = [
                            'active' => 'success',
                            'inactive' => 'warning', 
                            'fired' => 'danger'
                        ];
                        $statusLabels = [
                            'active' => 'Активен',
                            'inactive' => 'Неактивен',
                            'fired' => 'Уволен'
                        ];
                        ?>
                        <span class="badge badge-<?= $statusColors[$candidate['status']] ?>">
                            <?= $statusLabels[$candidate['status']] ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Правая колонка - быстрые действия -->
        <div class="section-card">
            <div class="section-header">
                <h3>Действия</h3>
            </div>
            <div class="section-content">
                <div class="action-buttons-vertical">
                    <a href="/admin/candidates/<?= $candidate['courier_id'] ?>/edit" class="btn btn-outline">
                        ✏️ Редактировать профиль
                    </a>
                    <a href="/admin/candidates/<?= $candidate['courier_id'] ?>/verifications/add" class="btn btn-primary">
                        ➕ Добавить данные сверки
                    </a>
                    <a href="/admin/candidates/<?= $candidate['courier_id'] ?>/export" class="btn btn-outline">
                        📊 Экспорт данных
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- История сверок -->
    <div class="section-card" style="margin-top: 2rem;">
        <div class="section-header">
            <h3>История данных сверок</h3>
            <div class="table-actions">
                <span class="text-muted">Всего записей: <?= count($verifications) ?></span>
            </div>
        </div>
        <div class="section-content">
            <?php if (empty($verifications)): ?>
                <div class="text-center" style="padding: 2rem;">
                    <p>Нет данных сверок</p>
                    <a href="/admin/candidates/<?= $candidate['courier_id'] ?>/verifications/add" class="btn btn-primary">
                        Добавить первую запись
                    </a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Период</th>
                                <th>Отработано часов</th>
                                <th>Заказов</th>
                                <th>Часы по типам вело</th>
                                <th>Сумма</th>
                                <th>Дата добавления</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($verifications as $verification): ?>
                            <tr>
                                <td>
                                    <strong><?= date('d.m.Y', strtotime($verification['period_from'])) ?></strong> - 
                                    <strong><?= date('d.m.Y', strtotime($verification['period_to'])) ?></strong>
                                </td>
                                <td>
                                    <div class="stat-value-small"><?= number_format($verification['worked_hours'], 1) ?> ч</div>
                                </td>
                                <td>
                                    <div class="stat-value-small"><?= number_format($verification['orders_count']) ?></div>
                                </td>
                                <td>
                                    <div class="bike-stats">
                                        <div class="bike-stat">
                                            <span class="bike-label">Свое:</span>
                                            <span class="bike-value"><?= number_format($verification['hours_own_bike'], 1) ?>ч</span>
                                        </div>
                                        <div class="bike-stat">
                                            <span class="bike-label">Электро:</span>
                                            <span class="bike-value"><?= number_format($verification['hours_electric_bike'], 1) ?>ч</span>
                                        </div>
                                        <div class="bike-stat">
                                            <span class="bike-label">Яндекс:</span>
                                            <span class="bike-value"><?= number_format($verification['hours_yandex_bike'], 1) ?>ч</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="stat-value-small"><?= number_format($verification['total_amount'], 0, '', ' ') ?> ₽</div>
                                </td>
                                <td>
                                    <div class="text-muted"><?= date('d.m.Y H:i', strtotime($verification['created_at'])) ?></div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/admin/verifications/<?= $verification['id'] ?>/edit" class="btn-icon" title="Редактировать">
                                            ✏️
                                        </a>
                                        <a href="/admin/verifications/<?= $verification['id'] ?>/delete" class="btn-icon" title="Удалить" onclick="return confirm('Удалить запись?')">
                                            🗑️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .sections-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .section-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .section-content {
        padding: 1.5rem;
    }

    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item label {
        font-weight: 500;
        color: #374151;
    }

    .info-item span {
        color: #6b7280;
    }

    .action-buttons-vertical {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .bike-stats {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        font-size: 0.75rem;
    }

    .bike-stat {
        display: flex;
        justify-content: space-between;
    }

    .bike-label {
        color: #6b7280;
    }

    .bike-value {
        font-weight: 500;
        color: #374151;
    }

    .stat-value-small {
        font-weight: 600;
        color: #1f2937;
    }

    .text-muted {
        color: #6b7280;
        font-size: 0.875rem;
    }

    @media (max-width: 1024px) {
        .sections-grid {
            grid-template-columns: 1fr;
        }
    }
</style>