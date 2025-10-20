<?php
$pageTitle = "Файлы сверок";
$currentSection = "reconciliation";

ob_start();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>История загрузки файлов сверок</h6>
                        <div>
                            <a href="/admin/reconciliation/create" class="btn btn-dark btn-sm mb-0">
                                <i class="fas fa-upload me-2"></i>Загрузить файл
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Статистика -->
                    <div class="row px-4 pt-3">
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Всего файлов</p>
                                                <h5 class="font-weight-bolder"><?= $stats['total_files'] ?? 0 ?></h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                                <i class="fas fa-file text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Общий размер</p>
                                                <h5 class="font-weight-bolder"><?= $this->fileModel->formatFileSize($stats['total_size'] ?? 0) ?></h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                                <i class="fas fa-hdd text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Всего записей</p>
                                                <h5 class="font-weight-bolder"><?= $stats['total_records'] ?? 0 ?></h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                                <i class="fas fa-database text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Последняя загрузка</p>
                                                <h5 class="font-weight-bolder">
                                                    <?= $stats['last_upload'] ? date('d.m.Y', strtotime($stats['last_upload'])) : 'Нет данных' ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                                <i class="fas fa-clock text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Фильтры -->
                    <div class="px-4 pt-3">
                        <form method="GET" class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="Поиск по названию файла или описанию..." value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-dark">Поиск</button>
                                <?php if (!empty($search)): ?>
                                    <a href="/admin/reconciliation" class="btn btn-outline-dark ms-2">Сброс</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <!-- Таблица -->
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Файл</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Период</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Размер</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Записей</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Загрузил</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Дата загрузки</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($files)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="material-icons text-secondary mb-2">inbox</i>
                                                <p class="text-secondary mb-0">Нет загруженных файлов</p>
                                                <a href="/admin/reconciliation/create" class="btn btn-dark btn-sm mt-2">
                                                    Загрузить первый файл
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($files as $file): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <i class="fas fa-file-csv text-gradient text-primary"></i>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center ms-3">
                                                    <h6 class="mb-0 text-sm"><?= htmlspecialchars($file['file_name']) ?></h6>
                                                    <?php if (!empty($file['description'])): ?>
                                                        <p class="text-xs text-secondary mb-0">
                                                            <?= htmlspecialchars($file['description']) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($file['period_from'] && $file['period_to']): ?>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <?= date('d.m.Y', strtotime($file['period_from'])) ?>
                                                </p>
                                                <p class="text-xs text-secondary mb-0">
                                                    по <?= date('d.m.Y', strtotime($file['period_to'])) ?>
                                                </p>
                                            <?php else: ?>
                                                <span class="text-xs text-muted">Не указан</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-xs font-weight-bold">
                                                <?= $this->fileModel->formatFileSize($file['file_size']) ?>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-sm bg-gradient-secondary">
                                                <?= $file['records_count'] ?>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-xs font-weight-bold">
                                                <?= htmlspecialchars($file['first_name'] . ' ' . $file['last_name']) ?>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                <?= date('d.m.Y H:i', strtotime($file['created_at'])) ?>
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group">
                                                <a href="/admin/reconciliation/<?= $file['id'] ?>" 
                                                   class="btn btn-link text-info px-2 mb-0"
                                                   data-toggle="tooltip" title="Просмотреть детали">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="/admin/reconciliation/<?= $file['id'] ?>/download" 
                                                   class="btn btn-link text-success px-2 mb-0"
                                                   data-toggle="tooltip" title="Скачать файл">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <?php if ($this->auth->isAdmin()): ?>
                                                <a href="/admin/reconciliation/<?= $file['id'] ?>/delete" 
                                                   class="btn btn-link text-danger px-2 mb-0"
                                                   data-toggle="tooltip" title="Удалить файл"
                                                   onclick="return confirm('Вы уверены, что хотите удалить этот файл?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Пагинация -->
                    <?php if ($totalPages > 1): ?>
                    <div class="px-4 pt-3">
                        <nav>
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layout/admin.php';