<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2 ps" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href="/admin/dashboard">
            <img src="/app/views/admin/assets/img/logo.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
            <span class="ms-1 text-sm text-dark">Alliance Service</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto ps ps--active-x" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?= isActiveSection('dashboard') ?> " href="/admin/dashboard">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Панель управления</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActiveSection('tables') ?> " href="/admin/tables">
                    <i class="material-symbols-rounded opacity-5">table_view</i>
                    <span class="nav-link-text ms-1">Таблица сверок</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActiveSection('candidates') ?> " href="/admin/candidates">
                    <i class="material-symbols-rounded opacity-5">table_view</i>
                    <span class="nav-link-text ms-1">Таблица кандидатов</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActiveSection('billing') ?> " href="/admin/billing">
                    <i class="material-symbols-rounded opacity-5">receipt_long</i>
                    <span class="nav-link-text ms-1">Счета на оплату</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/admin//notifications">
                    <i class="material-symbols-rounded opacity-5">notifications</i>
                    <span class="nav-link-text ms-1">Уведомления</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/admin/profile">
                    <i class="material-symbols-rounded opacity-5">person</i>
                    <span class="nav-link-text ms-1">Профиль</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/admin/sign-in">
                    <i class="material-symbols-rounded opacity-5">login</i>
                    <span class="nav-link-text ms-1">Вход</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="/admin/sign-up">
                    <i class="material-symbols-rounded opacity-5">assignment</i>
                    <span class="nav-link-text ms-1">Регистрация</span>
                </a>
            </li>
        </ul>
        <div class="ps__rail-x" style="width: 222px; left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 220px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
        </div>
    </div>
    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    </div>
    <div class="ps__rail-y" style="top: 0px; right: 0px;">
        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
    </div>
</aside>