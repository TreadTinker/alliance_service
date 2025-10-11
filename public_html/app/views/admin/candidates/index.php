<?php
// Переменные: $candidates, $search, $department, $city, $status, $departments, $cities
?>
<style>
    /* Основные стили админки */
    .admin-header {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .admin-header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .admin-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .admin-header p {
        color: #6b7280;
        font-size: 1.125rem;
    }

    .admin-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    /* Сетка статистики */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-icon.primary { background: #e0e7ff; color: #6366f1; }
    .stat-icon.success { background: #d1fae5; color: #10b981; }
    .stat-icon.warning { background: #fef3c7; color: #f59e0b; }
    .stat-icon.danger { background: #fee2e2; color: #ef4444; }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
        line-height: 1;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Карточка таблицы */
    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .table-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .table-actions {
        display: flex;
        gap: 0.75rem;
    }

    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        min-width: 1000px; /* Минимальная ширина таблицы */
        width: 100%;
    }

    .data-table th {
        background: #f8fafc;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
    }

    .data-table tr:hover {
        background: #f8fafc;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    /* Бейджи */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-align: center;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-outline {
        background: transparent;
        border: 1px solid #d1d5db;
        color: #374151;
    }

    /* Кнопки */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #6366f1;
        color: white;
    }

    .btn-primary:hover {
        background: #4f46e5;
    }

    .btn-outline {
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-outline:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    /* Фильтры */
    .filters-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .filters-form {
        width: 100%;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group label {
        font-weight: 500;
        font-size: 0.875rem;
        color: #374151;
    }

    .filter-group input,
    .filter-group select {
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background: white;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Мини-статистика в таблице */
    .stats-mini {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        font-size: 0.75rem;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.7rem;
    }

    .stat-value {
        font-weight: 500;
        color: #374151;
        font-size: 0.7rem;
    }

    /* Кнопки действий */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        padding: 0.375rem;
        border-radius: 0.375rem;
        background: #f3f4f6;
        text-decoration: none;
        color: #374151;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
    }

    .btn-icon:hover {
        background: #e5e7eb;
        transform: scale(1.05);
    }

    /* Текст по центру */
    .text-center {
        text-align: center;
        color: #6b7280;
        padding: 2rem;
    }

    /* Информация о пользователе */
    .user-info {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 500;
        color: #1f2937;
    }

    /* Адаптивность */
    @media (max-width: 1024px) {
        .filters-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .table-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .table-actions {
            justify-content: flex-start;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .admin-header {
            padding: 1rem 0;
        }

        .admin-header h1 {
            font-size: 1.5rem;
        }

        .table-card {
            margin: 0 -1rem;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }
        
        .data-table {
            font-size: 0.8rem;
        }
        
        .data-table th,
        .data-table td {
            padding: 0.5rem 0.25rem;
        }
    }
</style>

<div class="admin-header">
    <div class="admin-header-content">
        <h1>Управление кандидатами</h1>
        <p>Список всех курьеров и их статистика</p>
    </div>
</div>

<div class="admin-content">
    <!-- Фильтры и поиск -->
    <div class="filters-card">
        <form method="GET" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <label>Поиск</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                        placeholder="ID курьера или ФИО...">
                </div>

                <div class="filter-group">
                    <label>Отдел</label>
                    <select name="department">
                        <option value="">Все отделы</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept) ?>"
                                <?= $department === $dept ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Город</label>
                    <select name="city">
                        <option value="">Все города</option>
                        <?php foreach ($cities as $cityItem): ?>
                            <option value="<?= htmlspecialchars($cityItem) ?>"
                                <?= $city === $cityItem ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cityItem) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Статус</label>
                    <select name="status">
                        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Активные</option>
                        <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Неактивные</option>
                        <option value="fired" <?= $status === 'fired' ? 'selected' : '' ?>>Уволенные</option>
                        <option value="">Все</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Применить</button>
                    <a href="/admin/candidates" class="btn btn-outline">Сбросить</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Статистика -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">👥</div>
            <div class="stat-value"><?= count($candidates) ?></div>
            <div class="stat-label">Всего кандидатов</div>
        </div>

        <?php
        $totalHours = array_sum(array_column(array_column($candidates, 'stats'), 'total_hours'));
        $totalOrders = array_sum(array_column(array_column($candidates, 'stats'), 'total_orders'));
        $totalEarnings = array_sum(array_column(array_column($candidates, 'stats'), 'total_earnings'));
        ?>

        <div class="stat-card">
            <div class="stat-icon success">⏱️</div>
            <div class="stat-value"><?= number_format($totalHours, 1) ?></div>
            <div class="stat-label">Всего часов</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">📦</div>
            <div class="stat-value"><?= number_format($totalOrders) ?></div>
            <div class="stat-label">Всего заказов</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon danger">💰</div>
            <div class="stat-value"><?= number_format($totalEarnings, 0, '', ' ') ?> ₽</div>
            <div class="stat-label">Общий заработок</div>
        </div>
    </div>

    <!-- Таблица кандидатов -->
    <div class="table-card">
        <div class="table-header">
            <h3>Список кандидатов</h3>
            <div class="table-actions">
                <a href="/admin/candidates/export" class="btn btn-outline">📊 Экспорт</a>
                <a href="/admin/candidates/add" class="btn btn-primary">➕ Добавить курьера</a>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Курьера</th>
                        <th>ФИО</th>
                        <th>Город</th>
                        <th>Телефон</th>
                        <th>Менеджер</th>
                        <th>Отдел</th>
                        <th>Статистика</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($candidates)): ?>
                        <tr>
                            <td colspan="9" class="text-center">Кандидаты не найдены</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($candidates as $candidate): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($candidate['courier_id']) ?></strong>
                                </td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-name"><?= htmlspecialchars($candidate['full_name']) ?></div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($candidate['city']) ?></td>
                                <td>
                                    <a href="tel:<?= htmlspecialchars($candidate['phone_number']) ?>">
                                        <?= htmlspecialchars($candidate['phone_number']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($candidate['manager_name']) ?></td>
                                <td>
                                    <span class="badge badge-outline"><?= htmlspecialchars($candidate['department']) ?></span>
                                </td>
                                <td>
                                    <div class="stats-mini">
                                        <div class="stat-item">
                                            <span class="stat-label">Часы:</span>
                                            <span class="stat-value"><?= number_format($candidate['stats']['total_hours'], 1) ?></span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Заказы:</span>
                                            <span class="stat-value"><?= number_format($candidate['stats']['total_orders']) ?></span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Сумма:</span>
                                            <span class="stat-value"><?= number_format($candidate['stats']['total_earnings'], 0, '', ' ') ?> ₽</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/admin/candidates/<?= $candidate['courier_id'] ?>"
                                            class="btn-icon" title="Просмотр">
                                            👁️
                                        </a>
                                        <a href="/admin/candidates/<?= $candidate['courier_id'] ?>/edit"
                                            class="btn-icon" title="Редактировать">
                                            ✏️
                                        </a>
                                        <a href="/admin/candidates/<?= $candidate['courier_id'] ?>/verifications"
                                            class="btn-icon" title="Данные сверок">
                                            📊
                                        </a>
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