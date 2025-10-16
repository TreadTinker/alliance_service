<?php
// Переменная: $message
?>
<!-- <div style="text-align: center; padding: 40px 20px;">
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
</div> -->

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Автоматизируйте обработку заявок и увеличьте конверсию на 47%</h1>
                <p>Наш сервис автоматически выгружает, подсчитывает и анализирует все ваши заявки из разных источников в единой системе.</p>
                <div class="hero-buttons">
                    <a href="#cta" class="btn">Попробовать бесплатно</a>
                    <a href="#features" class="btn btn-secondary">Узнать больше</a>
                </div>
            </div>
            <div class="hero-image">
                <!-- Здесь будет изображение дашборда -->
                <div style="width: 100%; height: 300px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.7);">
                    Демо дашборда аналитики
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features" id="features">
    <div class="container">
        <div class="section-title">
            <h2>Мощные возможности для вашего бизнеса</h2>
            <p>Автоматизируйте рутинные задачи и получайте ценные инсайты для роста вашего бизнеса</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <!-- Иконка -->
                    <i>📊</i>
                </div>
                <h3>Автоматическая выгрузка</h3>
                <p>Интегрируйтесь с более чем 50+ источниками заявок: сайты, CRM, мессенджеры, соцсети и почта.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i>🔢</i>
                </div>
                <h3>Умный подсчет</h3>
                <p>Автоматически классифицируйте заявки по типам, приоритетам и источникам для точной аналитики.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i>📈</i>
                </div>
                <h3>Глубокая аналитика</h3>
                <p>Получайте детальные отчеты о конверсии, стоимости лида и эффективности каналов привлечения.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i>🤖</i>
                </div>
                <h3>AI-прогнозирование</h3>
                <p>Наш искусственный интеллект предсказывает вероятность конверсии каждой заявки.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i>⏱️</i>
                </div>
                <h3>Экономия времени</h3>
                <p>Сократите время на обработку заявок на 80% и сосредоточьтесь на самых перспективных лидах.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i>💬</i>
                </div>
                <h3>Авто-уведомления</h3>
                <p>Получайте мгновенные оповещения о горячих заявках и важных изменениях в статистике.</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <div class="section-title">
            <h2>Как это работает</h2>
            <p>Всего 3 простых шага к автоматизированной обработке заявок</p>
        </div>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Подключите источники</h3>
                <p>Интегрируйте все каналы поступления заявок: сайт, CRM, телефонию, мессенджеры и соцсети.</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Настройте правила</h3>
                <p>Определите критерии классификации, приоритеты обработки и параметры отчетности.</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Получайте аналитику</h3>
                <p>Просматривайте готовые отчеты и дашборды с ключевыми метриками эффективности.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta" id="cta">
    <div class="container">
        <h2>Начните увеличивать конверсию уже сегодня</h2>
        <p>Присоединяйтесь к 1500+ компаниям, которые уже автоматизировали обработку заявок с помощью нашего сервиса</p>
        <a href="#" class="btn">Начать 14-дневный пробный период</a>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials">
    <div class="container">
        <div class="section-title">
            <h2>Что говорят наши клиенты</h2>
            <p>Убедитесь в эффективности нашего решения на реальных примерах</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-text">
                    "После внедрения AutoLead Analytics мы сократили время обработки заявок на 75% и увеличили конверсию на 43%. Лучшее решение для нашего отдела продаж!"
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar"></div>
                    <div class="author-info">
                        <h4>Анна Петрова</h4>
                        <p>Директор по маркетингу, ООО "ТехноПрофи"</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-text">
                    "Раньше мы тратили часы на сбор данных из разных источников. Теперь вся аналитика доступна в одном месте в реальном времени. Сервис окупился за 2 месяца!"
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar"></div>
                    <div class="author-info">
                        <h4>Иван Сидоров</h4>
                        <p>Владелец, "СтройМастер"</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-text">
                    "AI-прогнозирование помогает нашим менеджерам фокусироваться на самых перспективных лидах. Конверсия в продажи выросла на 28% за первый квартал использования."
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar"></div>
                    <div class="author-info">
                        <h4>Мария Козлова</h4>
                        <p>Руководитель отдела продаж, "ФинТех Групп"</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing -->
<section class="pricing" id="pricing">
    <div class="container">
        <div class="section-title">
            <h2>Доступные тарифы</h2>
            <p>Выберите план, который подходит именно вашему бизнесу</p>
        </div>
        <div class="pricing-grid">
            <div class="pricing-card">
                <div class="pricing-header">
                    <div class="pricing-name">Старт</div>
                    <div class="pricing-price">2990₽<span class="pricing-period">/месяц</span></div>
                </div>
                <ul class="pricing-features">
                    <li>До 500 заявок в месяц</li>
                    <li>5 источников данных</li>
                    <li>Базовая аналитика</li>
                    <li>Email-поддержка</li>
                    <li>Дашборд эффективности</li>
                </ul>
                <a href="#" class="btn">Выбрать тариф</a>
            </div>
            <div class="pricing-card featured">
                <div class="pricing-header">
                    <div class="pricing-name">Профи</div>
                    <div class="pricing-price">6990₽<span class="pricing-period">/месяц</span></div>
                </div>
                <ul class="pricing-features">
                    <li>До 2000 заявок в месяц</li>
                    <li>15 источников данных</li>
                    <li>Расширенная аналитика</li>
                    <li>Приоритетная поддержка</li>
                    <li>AI-прогнозирование</li>
                    <li>Интеграция с CRM</li>
                </ul>
                <a href="#" class="btn">Выбрать тариф</a>
            </div>
            <div class="pricing-card">
                <div class="pricing-header">
                    <div class="pricing-name">Бизнес</div>
                    <div class="pricing-price">14990₽<span class="pricing-period">/месяц</span></div>
                </div>
                <ul class="pricing-features">
                    <li>Неограниченное количество заявок</li>
                    <li>Неограниченное количество источников</li>
                    <li>Полная аналитика с кастомными отчетами</li>
                    <li>Персональный менеджер</li>
                    <li>AI-прогнозирование премиум</li>
                    <li>API доступ</li>
                </ul>
                <a href="#" class="btn">Выбрать тариф</a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="faq" id="faq">
    <div class="container">
        <div class="section-title">
            <h2>Часто задаваемые вопросы</h2>
            <p>Ответы на самые популярные вопросы о нашем сервисе</p>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <div class="faq-question">
                    Сколько времени занимает настройка системы?
                    <span>+</span>
                </div>
                <div class="faq-answer">
                    Большинство наших клиентов полностью настраивают систему за 1-2 рабочих дня. Мы предоставляем пошаговые инструкции и бесплатную помощь при onboarding.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    С какими CRM вы интегрируетесь?
                    <span>+</span>
                </div>
                <div class="faq-answer">
                    Мы поддерживаем интеграцию с более чем 20 популярными CRM, включая Битрикс24, AmoCRM, RetailCRM, Мегаплан и другие. Также доступна возможность кастомной интеграции через API.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Что происходит с данными после окончания пробного периода?
                    <span>+</span>
                </div>
                <div class="faq-answer">
                    Все ваши данные сохраняются в течение 30 дней после окончания пробного периода. Если вы решите продолжить использование сервиса, вы сможете возобновить работу с того места, где остановились.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Предоставляете ли вы обучение для сотрудников?
                    <span>+</span>
                </div>
                <div class="faq-answer">
                    Да, мы предоставляем бесплатное обучение для ваших сотрудников в формате вебинаров, видеоуроков и документации. Для клиентов тарифов "Профи" и "Бизнес" доступны индивидуальные сессии с нашими экспертами.
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    // FAQ accordion
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            answer.classList.toggle('active');

            // Change icon
            const icon = question.querySelector('span');
            icon.textContent = answer.classList.contains('active') ? '−' : '+';
        });
    });
</script>