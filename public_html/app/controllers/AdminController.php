<?php

class AdminController
{
    private $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    public function dashboard()
    {
        // Проверяем авторизацию
        if (!$this->auth->isLoggedIn()) {
            header('Location: /login?redirect=admin');
            exit;
        }

        $user = $this->auth->getUser();

        // Проверяем роль пользователя
        if (!$this->auth->isAdmin() && !$this->auth->isModerator()) {
            // Если пользователь не админ и не модератор - перенаправляем на главную
            header('Location: /?error=access_denied');
            exit;
        }

        // Получаем статистику (заглушка - потом заменим реальными данными)
        $stats = $this->getAdminStats();

        // Отображаем админ панель
        require_once __DIR__ . '/../views/layout/header.php';
        ?>
        
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="margin-bottom: 30px;">
                <h1 style="color: #333; margin-bottom: 10px;">Панель управления</h1>
                <p style="color: #666;">Добро пожаловать в админ-панель, <?= htmlspecialchars($user['email']) ?></p>
                <div style="
                    display: inline-block;
                    background: #667eea;
                    color: white;
                    padding: 5px 15px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: 500;
                    margin-top: 5px;
                ">
                    Роль: <?= htmlspecialchars($user['role']) ?>
                </div>
            </div>

            <!-- Статистика -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
                <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 25px; border-radius: 10px;">
                    <h3 style="margin-bottom: 10px; font-size: 14px; opacity: 0.9;">Пользователи</h3>
                    <div style="font-size: 32px; font-weight: bold;"><?= $stats['total_users'] ?></div>
                    <div style="font-size: 12px; opacity: 0.8;">Всего зарегистрировано</div>
                </div>

                <div style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white; padding: 25px; border-radius: 10px;">
                    <h3 style="margin-bottom: 10px; font-size: 14px; opacity: 0.9;">Файлы</h3>
                    <div style="font-size: 32px; font-weight: bold;"><?= $stats['total_files'] ?></div>
                    <div style="font-size: 12px; opacity: 0.8;">Всего загружено</div>
                </div>

                <div style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; padding: 25px; border-radius: 10px;">
                    <h3 style="margin-bottom: 10px; font-size: 14px; opacity: 0.9;">Отслеживания</h3>
                    <div style="font-size: 32px; font-weight: bold;"><?= $stats['total_tracking'] ?></div>
                    <div style="font-size: 12px; opacity: 0.8;">Активных кодов</div>
                </div>

                <div style="background: linear-gradient(135deg, #43e97b, #38f9d7); color: white; padding: 25px; border-radius: 10px;">
                    <h3 style="margin-bottom: 10px; font-size: 14px; opacity: 0.9;">Посещения</h3>
                    <div style="font-size: 32px; font-weight: bold;"><?= $stats['total_views'] ?></div>
                    <div style="font-size: 12px; opacity: 0.8;">За сегодня</div>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div style="background: white; padding: 30px; border-radius: 10px; margin-bottom: 30px;">
                <h2 style="color: #333; margin-bottom: 20px;">Быстрые действия</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <a href="/admin/users" style="
                        display: block;
                        background: #f8f9fa;
                        padding: 20px;
                        border-radius: 8px;
                        text-decoration: none;
                        color: #333;
                        text-align: center;
                        border: 2px solid #e9ecef;
                        transition: all 0.3s;
                    " onmouseover="this.style.background='#667eea'; this.style.color='white'; this.style.borderColor='#667eea'" 
                       onmouseout="this.style.background='#f8f9fa'; this.style.color='#333'; this.style.borderColor='#e9ecef'">
                        <div style="font-size: 24px; margin-bottom: 10px;">👥</div>
                        <div style="font-weight: 500;">Управление пользователями</div>
                    </a>

                    <a href="/admin/files" style="
                        display: block;
                        background: #f8f9fa;
                        padding: 20px;
                        border-radius: 8px;
                        text-decoration: none;
                        color: #333;
                        text-align: center;
                        border: 2px solid #e9ecef;
                        transition: all 0.3s;
                    " onmouseover="this.style.background='#667eea'; this.style.color='white'; this.style.borderColor='#667eea'" 
                       onmouseout="this.style.background='#f8f9fa'; this.style.color='#333'; this.style.borderColor='#e9ecef'">
                        <div style="font-size: 24px; margin-bottom: 10px;">📁</div>
                        <div style="font-weight: 500;">Загруженные файлы</div>
                    </a>

                    <a href="/admin/tracking" style="
                        display: block;
                        background: #f8f9fa;
                        padding: 20px;
                        border-radius: 8px;
                        text-decoration: none;
                        color: #333;
                        text-align: center;
                        border: 2px solid #e9ecef;
                        transition: all 0.3s;
                    " onmouseover="this.style.background='#667eea'; this.style.color='white'; this.style.borderColor='#667eea'" 
                       onmouseout="this.style.background='#f8f9fa'; this.style.color='#333'; this.style.borderColor='#e9ecef'">
                        <div style="font-size: 24px; margin-bottom: 10px;">🔍</div>
                        <div style="font-weight: 500;">Коды отслеживания</div>
                    </a>

                    <a href="/admin/settings" style="
                        display: block;
                        background: #f8f9fa;
                        padding: 20px;
                        border-radius: 8px;
                        text-decoration: none;
                        color: #333;
                        text-align: center;
                        border: 2px solid #e9ecef;
                        transition: all 0.3s;
                    " onmouseover="this.style.background='#667eea'; this.style.color='white'; this.style.borderColor='#667eea'" 
                       onmouseout="this.style.background='#f8f9fa'; this.style.color='#333'; this.style.borderColor='#e9ecef'">
                        <div style="font-size: 24px; margin-bottom: 10px;">⚙️</div>
                        <div style="font-weight: 500;">Настройки системы</div>
                    </a>
                </div>
            </div>

            <!-- Последние действия -->
            <div style="background: white; padding: 30px; border-radius: 10px;">
                <h2 style="color: #333; margin-bottom: 20px;">Последние действия</h2>
                <div style="border: 1px solid #e9ecef; border-radius: 8px; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e9ecef;">Пользователь</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e9ecef;">Действие</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e9ecef;">Время</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['recent_actions'] as $action): ?>
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td style="padding: 12px;"><?= htmlspecialchars($action['user']) ?></td>
                                    <td style="padding: 12px;"><?= htmlspecialchars($action['action']) ?></td>
                                    <td style="padding: 12px; color: #666;"><?= htmlspecialchars($action['time']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Получить статистику для админ-панели (заглушка)
     */
    private function getAdminStats()
    {
        // Временные данные - потом заменим на реальные запросы к БД
        return [
            'total_users' => 154,
            'total_files' => 892,
            'total_tracking' => 67,
            'total_views' => 1243,
            'recent_actions' => [
                [
                    'user' => 'admin@example.com',
                    'action' => 'Загрузил новый файл данных',
                    'time' => '5 минут назад'
                ],
                [
                    'user' => 'user@example.com',
                    'action' => 'Создал код отслеживания',
                    'time' => '15 минут назад'
                ],
                [
                    'user' => 'moderator@example.com',
                    'action' => 'Обновил настройки системы',
                    'time' => '1 час назад'
                ],
                [
                    'user' => 'test@example.com',
                    'action' => 'Зарегистрировался в системе',
                    'time' => '2 часа назад'
                ]
            ]
        ];
    }
}