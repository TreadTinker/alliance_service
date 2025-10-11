<?php
// Переменные: $candidate, $departments, $cities, $errors
?>
<style>
    /* Стили такие же как в add.php */
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .form-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .form-header h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .form-content {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        margin-top: 1.5rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .alert-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #dc2626;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>

<div class="admin-header">
    <div class="admin-header-content">
        <h1>Редактирование курьера</h1>
        <p>Изменение информации о курьере: <?= htmlspecialchars($candidate['full_name']) ?></p>
    </div>
</div>

<div class="admin-content">
    <div class="form-container">
        <div class="form-header">
            <h1>Основная информация</h1>
        </div>

        <div class="form-content">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="courier_id">ID Курьера *</label>
                        <input type="text" id="courier_id" name="courier_id" required
                            value="<?= htmlspecialchars($_POST['courier_id'] ?? $candidate['courier_id']) ?>"
                            readonly style="background: #f3f4f6;">
                    </div>

                    <div class="form-group">
                        <label for="full_name">ФИО *</label>
                        <input type="text" id="full_name" name="full_name" required
                            value="<?= htmlspecialchars($_POST['full_name'] ?? $candidate['full_name']) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_number">Телефон *</label>
                        <input type="tel" id="phone_number" name="phone_number" required
                            value="<?= htmlspecialchars($_POST['phone_number'] ?? $candidate['phone_number']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="city">Город *</label>
                        <select id="city" name="city" required>
                            <option value="">Выберите город</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?= htmlspecialchars($city) ?>"
                                    <?= ($_POST['city'] ?? $candidate['city']) === $city ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($city) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="manager_name">Менеджер *</label>
                        <input type="text" id="manager_name" name="manager_name" required
                            value="<?= htmlspecialchars($_POST['manager_name'] ?? $candidate['manager_name']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="department">Отдел *</label>
                        <select id="department" name="department" required>
                            <option value="">Выберите отдел</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= htmlspecialchars($department) ?>"
                                    <?= ($_POST['department'] ?? $candidate['department']) === $department ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($department) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Статус</label>
                    <select id="status" name="status">
                        <option value="active" <?= ($_POST['status'] ?? $candidate['status']) === 'active' ? 'selected' : '' ?>>Активен</option>
                        <option value="inactive" <?= ($_POST['status'] ?? $candidate['status']) === 'inactive' ? 'selected' : '' ?>>Неактивен</option>
                        <option value="fired" <?= ($_POST['status'] ?? $candidate['status']) === 'fired' ? 'selected' : '' ?>>Уволен</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes">Примечания</label>
                    <textarea id="notes" name="notes" rows="3"><?= htmlspecialchars($_POST['notes'] ?? $candidate['notes'] ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <a href="/admin/candidates" class="btn btn-outline">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
</div>