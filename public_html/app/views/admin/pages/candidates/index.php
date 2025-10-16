<?php
// Переменные из контроллера
/** @var array $candidates */
/** @var array $stats */
/** @var array $user */

$pageTitle = "Таблица кандидатов";
$currentSection = "candidates";

// Начинаем буферизацию вывода
ob_start();
?>

<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Таблица Кандидатов</h6>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Курьер</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Номер телефона</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Менеджера</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Статус</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Дата загрузки</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            foreach ($candidates as $id => $candidate) {
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?= htmlspecialchars($candidate['full_name']); ?></h6>
                                                <p class="text-xs text-secondary mb-0"><?= htmlspecialchars($candidate['courier_id']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($candidate['phone_number']); ?></p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($candidate['manager_name']); ?></p>
                                        <p class="text-xs text-secondary mb-0">Менеджер</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-<?= $candidate["status"] == "active" ? "success" : "secondary";?>"><?= htmlspecialchars($candidate['status']); ?></span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold"><?= date("j/n/Y H:i:s", strtotime($candidate['created_at'])); ?></span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="/admin/candidates/<?= htmlspecialchars($candidate['courier_id']); ?>"
                                            class="text-secondary font-weight-bold text-xs"
                                            data-toggle="tooltip"
                                            data-original-title="Edit user">
                                            Изменить
                                        </a>
                                    </td>
                                </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// Получаем содержимое буфера
$content = ob_get_clean();

// Подключаем основной layout
include __DIR__ . '/../../layout/admin.php';
