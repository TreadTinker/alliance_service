<?php
$pageTitle = "Редактирование сверки #{$verification['id']}";
$currentSection = "verifications";

$formData = $_SESSION['form_data'] ?? $verification;
unset($_SESSION['form_data']);

ob_start();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Редактирование сверки #<?= $verification['id'] ?></h6>
                        <a href="/admin/verifications" class="btn btn-outline-dark btn-sm mb-0">
                            <i class="fas fa-arrow-left me-2"></i>Назад
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/verifications/<?= $verification['id'] ?>/update">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Курьер</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($candidates[$verification['courier_id']] ?? 'Неизвестно') ?></p>
                                    <small class="text-muted">ID: <?= $verification['courier_id'] ?></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="record_date" class="form-control-label">Дата записи *</label>
                                    <input type="date" name="record_date" id="record_date" class="form-control" 
                                           value="<?= htmlspecialchars($formData['record_date'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_from" class="form-control-label">Период с *</label>
                                    <input type="date" name="period_from" id="period_from" class="form-control" 
                                           value="<?= htmlspecialchars($formData['period_from'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_to" class="form-control-label">Период по *</label>
                                    <input type="date" name="period_to" id="period_to" class="form-control" 
                                           value="<?= htmlspecialchars($formData['period_to'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="worked_hours" class="form-control-label">Отработано часов *</label>
                                    <input type="number" step="0.1" name="worked_hours" id="worked_hours" class="form-control" 
                                           value="<?= htmlspecialchars($formData['worked_hours'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="orders_count" class="form-control-label">Количество заказов *</label>
                                    <input type="number" name="orders_count" id="orders_count" class="form-control" 
                                           value="<?= htmlspecialchars($formData['orders_count'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_amount" class="form-control-label">Сумма (руб) *</label>
                                    <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" 
                                           value="<?= htmlspecialchars($formData['total_amount'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="comments" class="form-control-label">Комментарии</label>
                                    <textarea name="comments" id="comments" class="form-control" rows="3"><?= htmlspecialchars($formData['comments'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark">Сохранить изменения</button>
                                <a href="/admin/verifications" class="btn btn-outline-dark">Отмена</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layout/admin.php';