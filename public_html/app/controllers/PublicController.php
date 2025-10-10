<?php

class PublicController
{
    public function index()
    {
        $auth = new Auth();
        $message = '';
        
        // Проверяем параметры URL для сообщений
        if (isset($_GET['error']) && $_GET['error'] === 'access_denied') {
            $message = 'Доступ запрещен. У вас недостаточно прав для просмотра этой страницы.';
        }
        
        if (isset($_GET['welcome']) && $_GET['welcome'] === '1') {
            $message = 'Добро пожаловать! Регистрация прошла успешно.';
        }

        require_once __DIR__ . '/../views/layout/header.php';
        ?>
        
        <div style="text-align: center; padding: 40px 20px;">
            <?php if (!empty($message)): ?>
                <div style="
                    background: <?= strpos($message, 'запрещен') !== false ? '#fee' : '#e8f5e8' ?>;
                    color: <?= strpos($message, 'запрещен') !== false ? '#c33' : '#2d5016' ?>;
                    padding: 15px;
                    border-radius: 5px;
                    margin-bottom: 30px;
                    border: 1px solid <?= strpos($message, 'запрещен') !== false ? '#fcc' : '#c3e6cb' ?>;
                    max-width: 600px;
                    margin: 0 auto 30px;
                ">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <h1 style="color: #333; margin-bottom: 20px; font-size: 2.5em;">
                Добро пожаловать в CCAdmModules
            </h1>
            
            <?php if ($auth->isLoggedIn()): ?>
                <div style="margin-bottom: 30px;">
                    <p style="color: #666; font-size: 1.2em; margin-bottom: 20px;">
                        Рады видеть вас снова, <strong><?= htmlspecialchars($auth->getUser()['email']) ?></strong>!
                    </p>
                    <?php if ($auth->isAdmin() || $auth->isModerator()): ?>
                        <a href="/admin" style="
                            display: inline-block;
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            color: white;
                            padding: 12px 30px;
                            border-radius: 5px;
                            text-decoration: none;
                            font-weight: 500;
                            margin: 10px;
                        ">Перейти в админ-панель</a>
                    <?php endif; ?>
                    <a href="/logout" style="
                        display: inline-block;
                        background: #6c757d;
                        color: white;
                        padding: 12px 30px;
                        border-radius: 5px;
                        text-decoration: none;
                        font-weight: 500;
                        margin: 10px;
                    ">Выйти</a>
                </div>
            <?php else: ?>
                <p style="color: #666; font-size: 1.2em; max-width: 600px; margin: 0 auto 30px;">
                    Современный сервис для управления и отслеживания данных. 
                    Загружайте, анализируйте и делитесь информацией легко и безопасно.
                </p>
                <div style="margin-top: 30px;">
                    <a href="/login" style="
                        display: inline-block;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 12px 30px;
                        border-radius: 5px;
                        text-decoration: none;
                        font-weight: 500;
                        margin: 10px;
                    ">Войти в систему</a>
                    <a href="/register" style="
                        display: inline-block;
                        background: white;
                        color: #667eea;
                        padding: 12px 30px;
                        border-radius: 5px;
                        text-decoration: none;
                        font-weight: 500;
                        margin: 10px;
                        border: 2px solid #667eea;
                    ">Зарегистрироваться</a>
                </div>
            <?php endif; ?>
            
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-top: 40px;">
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

        <?php
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}