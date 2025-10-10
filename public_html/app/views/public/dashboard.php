<?php
// Переменные: 
// $user - данные пользователя
// $message - сообщение
// $isAdmin - является ли админом
// $isModerator - является ли модератором
?>
<div style="text-align: center; padding: 40px 20px;">
    <?php if (!empty($message)): ?>
        <div style="
            background: #e8f5e8;
            color: #2d5016;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            border: 1px solid #c3e6cb;
            max-width: 600px;
            margin: 0 auto 30px;
        ">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <h1 style="color: #333; margin-bottom: 20px; font-size: 2.5em;">
        Добро пожаловать, <?= htmlspecialchars($user['first_name'] ?: $user['email']) ?>!
    </h1>
    
    <p style="color: #666; font-size: 1.2em; max-width: 600px; margin: 0 auto 30px;">
        Рады видеть вас в системе CCAdmModules.
    </p>

    <div style="margin-bottom: 30px;">
        <div style="
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
        ">
            Ваша роль: <?= htmlspecialchars($user['role']) ?>
        </div>
    </div>

    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-top: 40px;">
        <?php if ($isAdmin || $isModerator): ?>
            <a href="/admin" style="
                display: inline-block;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 15px 30px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 500;
                font-size: 16px;
            ">📊 Панель управления</a>
        <?php endif; ?>
        
        <a href="/profile" style="
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        ">👤 Мой профиль</a>
        
        <a href="/upload" style="
            display: inline-block;
            background: #17a2b8;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        ">📁 Загрузить данные</a>
        
        <a href="/logout" style="
            display: inline-block;
            background: #6c757d;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        ">🚪 Выйти</a>
    </div>
</div>