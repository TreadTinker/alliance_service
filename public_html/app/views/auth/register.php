<?php
// Переменные, которые передаются из контроллера:
// $errors - массив ошибок
// $formData - данные формы
?>
<div style="max-width: 450px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #333; margin-bottom: 10px;">Регистрация</h2>
        <p style="color: #666;">Создайте новый аккаунт</p>
    </div>

    <?php if (!empty($errors['general'])): ?>
        <div style="
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #fcc;
            font-size: 14px;
        ">
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label for="first_name" style="display: block; margin-bottom: 5px; color: #333; font-weight: 500;">Имя *</label>
                <input type="text" id="first_name" name="first_name" 
                       value="<?= htmlspecialchars($formData['first_name'] ?? '') ?>" 
                       style="
                            width: 100%;
                            padding: 12px;
                            border: 2px solid <?= !empty($errors['first_name']) ? '#e74c3c' : '#e1e1e1' ?>;
                            border-radius: 5px;
                            font-size: 14px;
                       "
                       required>
                <?php if (!empty($errors['first_name'])): ?>
                    <div style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                        <?= htmlspecialchars($errors['first_name']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div style="flex: 1;">
                <label for="last_name" style="display: block; margin-bottom: 5px; color: #333; font-weight: 500;">Фамилия</label>
                <input type="text" id="last_name" name="last_name" 
                       value="<?= htmlspecialchars($formData['last_name'] ?? '') ?>"
                       style="
                            width: 100%;
                            padding: 12px;
                            border: 2px solid #e1e1e1;
                            border-radius: 5px;
                            font-size: 14px;
                       ">
            </div>
        </div>

        <div>
            <label for="email" style="display: block; margin-bottom: 5px; color: #333; font-weight: 500;">Email *</label>
            <input type="email" id="email" name="email" 
                   value="<?= htmlspecialchars($formData['email'] ?? '') ?>" 
                   style="
                        width: 100%;
                        padding: 12px;
                        border: 2px solid <?= !empty($errors['email']) ? '#e74c3c' : '#e1e1e1' ?>;
                        border-radius: 5px;
                        font-size: 14px;
                   "
                   required>
            <?php if (!empty($errors['email'])): ?>
                <div style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                    <?= htmlspecialchars($errors['email']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <label for="phone" style="display: block; margin-bottom: 5px; color: #333; font-weight: 500;">Телефон</label>
            <input type="tel" id="phone" name="phone" 
                   value="<?= htmlspecialchars($formData['phone'] ?? '') ?>"
                   style="
                        width: 100%;
                        padding: 12px;
                        border: 2px solid #e1e1e1;
                        border-radius: 5px;
                        font-size: 14px;
                   ">
        </div>

        <div>
            <label for="password" style="display: block; margin-bottom: 5px; color: #333; font-weight: 500;">Пароль *</label>
            <input type="password" id="password" name="password" 
                   style="
                        width: 100%;
                        padding: 12px;
                        border: 2px solid <?= !empty($errors['password']) ? '#e74c3c' : '#e1e1e1' ?>;
                        border-radius: 5px;
                        font-size: 14px;
                   "
                   required>
            <?php if (!empty($errors['password'])): ?>
                <div style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                    <?= htmlspecialchars($errors['password']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <label for="confirm_password" style="display: block; margin-bottom: 5px; color: #333; font-weight: 500;">Подтверждение пароля *</label>
            <input type="password" id="confirm_password" name="confirm_password" 
                   style="
                        width: 100%;
                        padding: 12px;
                        border: 2px solid <?= !empty($errors['confirm_password']) ? '#e74c3c' : '#e1e1e1' ?>;
                        border-radius: 5px;
                        font-size: 14px;
                   "
                   required>
            <?php if (!empty($errors['confirm_password'])): ?>
                <div style="color: #e74c3c; font-size: 12px; margin-top: 5px;">
                    <?= htmlspecialchars($errors['confirm_password']) ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" style="
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: opacity 0.3s;
        ">Зарегистрироваться</button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <p style="color: #666; margin-bottom: 10px;">Уже есть аккаунт?</p>
        <a href="/login" style="
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        ">Войти в систему</a>
    </div>
</div>