<?php
// Переменные из контроллера
/** @var array $stats */
/** @var array $user */

$pageTitle = "Таблицы сверок";
$currentSection = "tables";

// Получаем данные сверок из контроллера
$verifications = $stats['recent_verifications'] ?? [];

// Начинаем буферизацию вывода
ob_start();
?>

<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h6 class="text-white text-capitalize ps-3">Таблицы сверок</h6>
                        </div>
                        <div class="col-6 text-end pe-4">
                            <span class="badge badge-lg bg-gradient-info">
                                Всего записей: <?= $stats['total_verifications'] ?? 0 ?>
                            </span>
                            <button>Загрузить</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Курьер</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Период</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Часы</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Заказы</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Сумма</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Дата записи</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($verifications)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="material-icons text-secondary mb-2">inbox</i>
                                            <p class="text-secondary mb-0">Нет данных сверок</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($verifications as $verification): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?= htmlspecialchars($verification['full_name'] ?? 'Неизвестный курьер') ?></h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    ID: <?= htmlspecialchars($verification['courier_id'] ?? 'N/A') ?>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            <?= date('d.m.Y', strtotime($verification['period_from'] ?? '')) ?>
                                        </p>
                                        <p class="text-xs text-secondary mb-0">
                                            по <?= date('d.m.Y', strtotime($verification['period_to'] ?? '')) ?>
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-xs font-weight-bold">
                                            <?= number_format($verification['worked_hours'] ?? 0, 1) ?> ч
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-xs font-weight-bold">
                                            <?= $verification['orders_count'] ?? 0 ?>
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-xs font-weight-bold text-success">
                                            <?= number_format($verification['total_amount'] ?? 0, 2) ?> ₽
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            <?= date('d.m.Y', strtotime($verification['record_date'] ?? '')) ?>
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="javascript:;" 
                                           class="text-secondary font-weight-bold text-xs" 
                                           data-toggle="tooltip" 
                                           data-original-title="Просмотреть детали"
                                           onclick="showVerificationDetails(<?= $verification['id'] ?>)">
                                            Детали
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Пагинация или информация о количестве -->
                <div class="px-4 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-sm text-secondary mb-0">
                            Показано последних <?= count($verifications) ?> записей
                        </p>
                        <a href="/admin/verifications" class="btn btn-sm bg-gradient-dark mb-0">
                            Все записи сверок
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для деталей сверки -->
<div class="modal fade" id="verificationDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Детали сверки</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="verificationDetailsContent">
                <!-- Контент будет загружен через AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>
function showVerificationDetails(verificationId) {
    // Здесь можно добавить AJAX запрос для получения деталей сверки
    fetch('/admin/verification/' + verificationId + '/details')
        .then(response => response.json())
        .then(data => {
            document.getElementById('verificationDetailsContent').innerHTML = `
                <div class="row">
                    <div class="col-6">
                        <strong>Курьер:</strong>
                        <p>${data.full_name || 'Неизвестно'}</p>
                    </div>
                    <div class="col-6">
                        <strong>ID курьера:</strong>
                        <p>${data.courier_id || 'N/A'}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <strong>Отработано часов:</strong>
                        <p>${data.worked_hours || 0} ч</p>
                    </div>
                    <div class="col-4">
                        <strong>Количество заказов:</strong>
                        <p>${data.orders_count || 0}</p>
                    </div>
                    <div class="col-4">
                        <strong>Сумма:</strong>
                        <p class="text-success">${(data.total_amount || 0).toFixed(2)} ₽</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <strong>Период с:</strong>
                        <p>${data.period_from ? new Date(data.period_from).toLocaleDateString() : 'N/A'}</p>
                    </div>
                    <div class="col-6">
                        <strong>Период по:</strong>
                        <p>${data.period_to ? new Date(data.period_to).toLocaleDateString() : 'N/A'}</p>
                    </div>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('verificationDetailsModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('verificationDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    Ошибка при загрузке деталей сверки
                </div>
            `;
            new bootstrap.Modal(document.getElementById('verificationDetailsModal')).show();
        });
}
</script>

<?php
// Получаем содержимое буфера
$content = ob_get_clean();

// Подключаем основной layout
include __DIR__ . '/../../layout/admin.php';