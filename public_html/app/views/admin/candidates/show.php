<?php
/** @var array $candidate */
/** @var array $verifications */
/** @var array $stats */
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <!-- Заголовок страницы -->
            <div class="card mb-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white text-capitalize ps-3 mb-0">
                                    Профиль курьера: <?= htmlspecialchars($candidate['full_name']) ?>
                                </h4>
                                <p class="text-white text-sm ps-3 mb-0">ID: <?= htmlspecialchars($candidate['courier_id']) ?></p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="/admin/candidates/<?= $candidate['courier_id'] ?>/edit" class="btn btn-warning btn-sm">
                                    <i class="material-symbols-rounded">edit</i> Редактировать
                                </a>
                                <a href="/admin/candidates" class="btn bg-gradient-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Назад
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Основная информация -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Основная информация</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-dark me-2">person</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">ФИО</h6>
                                            <p class="text-sm mb-0"><?= htmlspecialchars($candidate['full_name']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-dark me-2">badge</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">ID курьера</h6>
                                            <p class="text-sm mb-0"><?= htmlspecialchars($candidate['courier_id']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-dark me-2">phone</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Телефон</h6>
                                            <p class="text-sm mb-0"><?= htmlspecialchars($candidate['phone_number']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-dark me-2">supervisor_account</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Менеджер</h6>
                                            <p class="text-sm mb-0"><?= htmlspecialchars($candidate['manager_name']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-dark me-2">business_center</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Отдел</h6>
                                            <p class="text-sm mb-0"><?= htmlspecialchars($candidate['department']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-dark me-2">location_on</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Город</h6>
                                            <p class="text-sm mb-0"><?= htmlspecialchars($candidate['city']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Статистика -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Статистика работы</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-success me-2">checklist</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Всего сверок</h6>
                                            <p class="text-sm mb-0"><?= $stats['total_verifications'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-info me-2">schedule</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Всего часов</h6>
                                            <p class="text-sm mb-0"><?= $stats['total_hours'] ?> ч</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-warning me-2">inventory_2</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Всего заказов</h6>
                                            <p class="text-sm mb-0"><?= $stats['total_orders'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-success me-2">payments</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Общий доход</h6>
                                            <p class="text-sm mb-0"><?= number_format($stats['total_earnings'], 2, '.', ' ') ?> ₽</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Статус и даты -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Статус и даты</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-dark me-2">status</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Статус</h6>
                                            <span class="badge badge-sm bg-gradient-<?= $candidate['status'] == 'active' ? 'success' : 'secondary' ?>">
                                                <?= $candidate['status'] == 'active' ? 'Активен' : 'Неактивен' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-dark me-2">calendar_today</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Дата регистрации</h6>
                                            <p class="text-sm mb-0"><?= date('d.m.Y H:i', strtotime($candidate['created_at'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-dark me-2">update</i>
                                        <div>
                                            <h6 class="mb-0 text-sm">Последнее обновление</h6>
                                            <p class="text-sm mb-0"><?= date('d.m.Y H:i', strtotime($candidate['updated_at'] ?? $candidate['created_at'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- История сверок -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6>История сверок</h6>
                                </div>
                                <div class="col-md-6 text-end">
                                    <a href="/admin/candidates/<?= $candidate['courier_id'] ?>/verifications" class="btn btn-info btn-sm">
                                        <i class="material-symbols-rounded">list_alt</i> Все сверки
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <?php if (!empty($verifications)): ?>
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Дата</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Часы</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Заказы</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Сумма</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($verifications, 0, 5) as $verification): ?>
                                                <tr>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            <?= date('d.m.Y', strtotime($verification['record_date'])) ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            <?= $verification['worked_hours'] ?> ч
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            <?= $verification['orders_count'] ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            <?= number_format($verification['total_amount'], 2, '.', ' ') ?> ₽
                                                        </p>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <p class="text-muted">Нет данных о сверках</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>