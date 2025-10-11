<?php
function renderRecentTable($data, $title = 'Последние записи', $emptyText = 'Нет данных для отображения') {
    ?>
    <div class="widget-card">
        <div class="widget-header">
            <h3 class="widget-title"><?= $title ?></h3>
            <a href="/admin/verifications" class="widget-action">Все записи →</a>
        </div>
        
        <?php if (empty($data)): ?>
            <div class="empty-state">
                <p><?= $emptyText ?></p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Курьер</th>
                            <th>Период</th>
                            <th>Часы</th>
                            <th>Заказы</th>
                            <th>Сумма</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $verification): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($verification['courier_id']) ?></strong>
                                    <div class="text-muted">
                                        <?= htmlspecialchars($verification['full_name'] ?? 'Неизвестно') ?>
                                    </div>
                                </td>
                                <td>
                                    <?= date('d.m.Y', strtotime($verification['period_from'])) ?> -<br>
                                    <?= date('d.m.Y', strtotime($verification['period_to'])) ?>
                                </td>
                                <td><?= number_format($verification['worked_hours'], 1) ?> ч</td>
                                <td><?= number_format($verification['orders_count']) ?></td>
                                <td class="text-bold"><?= number_format($verification['total_amount'], 0, '', ' ') ?> ₽</td>
                                <td class="text-muted"><?= date('d.m.Y H:i', strtotime($verification['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>