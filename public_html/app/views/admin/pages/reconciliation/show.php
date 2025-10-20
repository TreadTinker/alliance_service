<?php
$pageTitle = "Файл сверки: " . htmlspecialchars($file['file_name']);
$currentSection = "reconciliation";

ob_start();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Детали файла сверки</h6>
                        <div>
                            <a href="/admin/reconciliation/<?= $file['id'] ?>/download" class="btn btn-dark btn-sm mb-0 me-2">
                                <i class="fas fa-download me-2"></i>Скачать
                            </a>
                            <a href="/admin/reconciliation" class="btn btn-outline-dark btn-sm mb-0">
                                <i class="fas fa-arrow-left me-2"></i>Назад
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Информация о файле -->
                        <div class="col-md-6">
                            <h6 class="text-sm mb-3">Информация о файле</h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Название файла</label>
                                    <p class="text-dark text-sm font-weight-bold"><?= htmlspecialchars($file['file_name']) ?></p>
                                </div>
                                <?php if (!empty($file['description'])): ?>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Описание</label>
                                    <p class="text-dark text-sm"><?= nl2br(htmlspecialchars($file['description'])) ?></p>
                                </div>
                                <?php endif; ?>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Размер файла</label>
                                    <p class="text-dark text-sm"><?= $this->fileModel->formatFileSize($file['file_size']) ?></p>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Количество записей</label>
                                    <p class="text-dark text-sm"><?= $file['records_count'] ?></p>
                                </div>
                                <?php if ($file['period_from'] && $file['period_to']): ?>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Период с</label>
                                    <p class="text-dark text-sm"><?= date('d.m.Y', strtotime($file['period_from'])) ?></p>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Период по</label>
                                    <p class="text-dark text-sm"><?= date('d.m.Y', strtotime($file['period_to'])) ?></p>
                                </div>
                                <?php endif; ?>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Загрузил</label>
                                    <p class="text-dark text-sm"><?= htmlspecialchars($file['first_name'] . ' ' . $file['last_name']) ?></p>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Дата загрузки</label>
                                    <p class="text-dark text-sm"><?= date('d.m.Y H:i', strtotime($file['created_at'])) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Статистика -->
                        <div class="col-md-6">
                            <h6 class="text-sm mb-3">Статистика</h6>
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <div class="card bg-gradient-dark text-white">
                                        <div class="card-body p-3">
                                            <div class="text-center">
                                                <h4 class="mb-0"><?= count($file['candidates']) ?></h4>
                                                <p class="text-xs mb-0">Курьеров в файле</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-4">
                                    <div class="card bg-gradient-info text-white">
                                        <div class="card-body p-3">
                                            <div class="text-center">
                                                <h4 class="mb-0"><?= $file['records_count'] ?></h4>
                                                <p class="text-xs mb-0">Всего записей</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Список курьеров -->
                            <?php if (!empty($file['candidates'])): ?>
                            <div class="mt-4">
                                <h6 class="text-sm mb-3">Курьеры в этом файле</h6>
                                <div class="list-group">
                                    <?php foreach ($file['candidates'] as $candidate): ?>
                                    <div class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm"><?= htmlspecialchars($candidate['full_name']) ?></h6>
                                            <p class="mb-0 text-xs">ID: <?= htmlspecialchars($candidate['courier_id']) ?> | <?= htmlspecialchars($candidate['city']) ?></p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-info text-white mt-4">
                                <i class="fas fa-info-circle me-2"></i>
                                В этом файле не найдено данных о курьерах
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layout/admin.php';