<?php
// Переменные, которые передаются из контроллера:
// $error - сообщение об ошибке
// $email - введенный email
?>
<!-- <div style="max-width: 400px; margin: 0 auto;">
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
</div> -->
<div class="login-container">
    <div class="login-header">
        <h2>Log in</h2>
    </div>

    <?php if (!empty($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email"
                value="<?= htmlspecialchars($email ?? '') ?>"
                placeholder="example@gmail.com"
                required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                placeholder="**********"
                required>
        </div>

        <div class="form-options">
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin-bottom: 0;">Remember me</label>
            </div>
            <a href="#" class="reset-password">Reset Password?</a>
        </div>

        <button type="submit" class="login-btn">Log In</button>
    </form>

    <div class="signup-link">
        Don't have account yet? <a href="#">New Account</a>
    </div>
</div>
<style>
    .login-container {
        max-width: 400px;
        width: 100%;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }

    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-header h2 {
        color: #333;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .login-header p {
        color: #666;
        font-size: 14px;
    }

    .social-login {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px;
        border: 1px solid #e1e1e1;
        border-radius: 5px;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
    }

    .social-btn:hover {
        background: #f9f9f9;
    }

    .social-btn.google {
        color: #333;
    }

    .social-btn.facebook {
        color: #1877F2;
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 20px 0;
    }

    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e1e1e1;
    }

    .divider span {
        padding: 0 15px;
        color: #666;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-weight: 500;
        font-size: 14px;
    }

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px;
        border: 2px solid #e1e1e1;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #667eea;
        outline: none;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .reset-password {
        color: #667eea;
        text-decoration: none;
    }

    .reset-password:hover {
        text-decoration: underline;
    }

    .login-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: opacity 0.3s;
    }

    .login-btn:hover {
        opacity: 0.9;
    }

    .signup-link {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #666;
    }

    .signup-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
    }

    .signup-link a:hover {
        text-decoration: underline;
    }

    .error-message {
        background: #fee;
        color: #c33;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 20px;
        border: 1px solid #fcc;
        font-size: 14px;
    }
</style>