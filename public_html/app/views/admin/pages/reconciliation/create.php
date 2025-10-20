<?php
$pageTitle = "Загрузка файла сверки";
$currentSection = "reconciliation";

// Восстанавливаем данные формы если были ошибки
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

ob_start();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Загрузка нового файла сверки</h6>
                        <a href="/admin/reconciliation" class="btn btn-outline-dark btn-sm mb-0">
                            <i class="fas fa-arrow-left me-2"></i>Назад
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/reconciliation/store" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reconciliation_file" class="form-control-label">Файл сверки *</label>
                                    <input type="file" name="reconciliation_file" id="reconciliation_file" 
                                           class="form-control" accept=".csv,.xlsx,.xls" required>
                                    <small class="form-text text-muted">
                                        Поддерживаемые форматы: CSV, Excel (.xlsx, .xls)
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Описание файла</label>
                                    <textarea name="description" id="description" class="form-control" rows="3"
                                              placeholder="Необязательное описание файла..."><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_from" class="form-control-label">Период данных с</label>
                                    <input type="date" name="period_from" id="period_from" class="form-control" 
                                           value="<?= htmlspecialchars($formData['period_from'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_to" class="form-control-label">Период данных по</label>
                                    <input type="date" name="period_to" id="period_to" class="form-control" 
                                           value="<?= htmlspecialchars($formData['period_to'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-upload me-2"></i>Загрузить файл
                                </button>
                                <a href="/admin/reconciliation" class="btn btn-outline-dark">Отмена</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Информация о формате файла -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6>Требования к файлу</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-sm">Формат CSV:</h6>
                            <ul class="text-sm">
                                <li>Разделитель: запятая</li>
                                <li>Кодировка: UTF-8</li>
                                <li>Первая строка - заголовки</li>
                                <li>Обязательная колонка: courier_id</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-sm">Пример структуры:</h6>
                            <pre class="bg-light p-3 rounded text-sm">
courier_id,worked_hours,orders_count,total_amount,period_from,period_to
COURIER_001,120.5,85,45250.00,2024-01-01,2024-01-14
COURIER_002,95.0,67,32150.00,2024-01-01,2024-01-14
                            </pre>
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