<?php
// Переменная: $message
?>
<div style="text-align: center; padding: 40px 20px;">
    <?php if (!empty($message)): ?>
        <div style="
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            border: 1px solid #fcc;
            max-width: 600px;
            margin: 0 auto 30px;
        ">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <h1 style="color: #333; margin-bottom: 20px; font-size: 2.5em;">
        Добро пожаловать в CCAdmModules
    </h1>
    
    <p style="color: #666; font-size: 1.2em; max-width: 600px; margin: 0 auto 30px;">
        Современный сервис для управления и отслеживания данных. 
        Загружайте, анализируйте и делитесь информацией легко и безопасно.
    </p>

    <div style="margin-top: 30px;">
        <a href="/login" style="
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            margin: 10px;
        ">Войти в систему</a>
    </div>
    
    <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-top: 60px;">
        <div style="flex: 1; min-width: 250px; max-width: 300px;">
            <h3 style="color: #667eea; margin-bottom: 15px;">📊 Загрузка данных</h3>
            <p style="color: #666;">Поддерживаются CSV, Excel, JSON и другие форматы</p>
        </div>
        <div style="flex: 1; min-width: 250px; max-width: 300px;">
            <h3 style="color: #667eea; margin-bottom: 15px;">🔐 Безопасность</h3>
            <p style="color: #666;">Надежная защита и управление доступом</p>
        </div>
        <div style="flex: 1; min-width: 250px; max-width: 300px;">
            <h3 style="color: #667eea; margin-bottom: 15px;">📈 Аналитика</h3>
            <p style="color: #666;">Мощные инструменты для анализа данных</p>
        </div>
    </div>
</div>