<nav class="admin-sidebar" id="adminSidebar">
    <ul class="sidebar-menu">
        <li class="menu-item <?= $currentSection === 'dashboard' ? 'active' : '' ?>">
            <a href="/admin">
                <span class="icon">📊</span>
                <span class="text">Дашборд</span>
            </a>
        </li>
        <li class="menu-item <?= $currentSection === 'candidates' ? 'active' : '' ?>">
            <a href="/admin/candidates">
                <span class="icon">👥</span>
                <span class="text">Кандидаты</span>
            </a>
        </li>
        <li class="menu-item <?= $currentSection === 'verifications' ? 'active' : '' ?>">
            <a href="/admin/verifications">
                <span class="icon">📋</span>
                <span class="text">Сверки</span>
            </a>
        </li>
        <li class="menu-item <?= $currentSection === 'users' ? 'active' : '' ?>">
            <a href="/admin/users">
                <span class="icon">👤</span>
                <span class="text">Пользователи</span>
            </a>
        </li>
    </ul>
</nav>