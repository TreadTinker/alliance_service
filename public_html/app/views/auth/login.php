<?php
// Переменные, которые передаются из контроллера:
// $error - сообщение об ошибке
// $email - введенный email
?>
<div style="max-width: 400px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #333; margin-bottom: 10px;">Вход в систему</h2>
        <p style="color: #666;">Введите ваши учетные данные</p>
    </div>

    <?php if (!empty($error)): ?>
        <div style="
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #fcc;
            font-size: 14px;
        ">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="display: flex; flex-direction: column; gap: 20px;">
        <div>
            <label for="email" style="display: block; margin-bottom: 5px; color: #333; font-weight: 500;">Email:</label>
            <input type="email" id="email" name="email" 
                   value="<?= htmlspecialchars($email ?? '') ?>" 
                   style="
                        width: 100%;
                        padding: 12px;
                        border: 2px solid #e1e1e1;
                        border-radius: 5px;
                        font-size: 14px;
                   "
                   required>
        </div>

        <div>
            <label for="password" style="display: block; margin-bottom: 5px; color: #333; font-weight: 500;">Пароль:</label>
            <input type="password" id="password" name="password" 
                   style="
                        width: 100%;
                        padding: 12px;
                        border: 2px solid #e1e1e1;
                        border-radius: 5px;
                        font-size: 14px;
                   "
                   required>
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
        ">Войти</button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <p style="color: #666; margin-bottom: 10px;">Нет аккаунта?</p>
        <a href="/register" style="
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        ">Создать аккаунт</a>
    </div>
</div>