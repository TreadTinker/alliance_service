"use strict";

// Основная функция для обновления активного пункта меню
function updateActiveNavItem() {
    const currentPath = window.location.pathname;
    console.log('Current path:', currentPath);
    
    // Удаляем активный класс у всех пунктов
    const allNavLinks = document.querySelectorAll('.sidenav .nav-link');
    allNavLinks.forEach(link => {
        link.classList.remove('active', 'bg-gradient-dark', 'text-white');
        link.classList.add('text-dark');
    });
    
    // Добавляем активный класс к текущему пункту
    const activeLink = document.querySelector(`.sidenav .nav-link[href="${currentPath}"]`);
    if (activeLink) {
        activeLink.classList.add('active', 'bg-gradient-dark', 'text-white');
        activeLink.classList.remove('text-dark');
    } else {
        console.log('No matching link found for path:', currentPath);
    }
}

// Инициализация Perfect Scrollbar для Windows
function initPerfectScrollbar() {
    var isWindows = navigator.platform.indexOf('Win') > -1;
    
    if (isWindows && typeof PerfectScrollbar !== 'undefined') {
        if (document.querySelector('.main-content')) {
            var mainpanel = document.querySelector('.main-content');
            new PerfectScrollbar(mainpanel);
        }

        if (document.querySelector('.sidenav')) {
            var sidebar = document.querySelector('.sidenav');
            new PerfectScrollbar(sidebar);
        }
    }
}

// Инициализация тултипов Bootstrap
function initTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Функции для стилизации полей ввода
function focused(el) {
    if (el.parentElement.classList.contains('input-group')) {
        el.parentElement.classList.add('focused');
    }
}

function defocused(el) {
    if (el.parentElement.classList.contains('input-group')) {
        el.parentElement.classList.remove('focused');
    }
}

// Переключение сайдбара
const iconNavbarSidenav = document.getElementById('iconNavbarSidenav');
const iconSidenav = document.getElementById('iconSidenav');
const sidenav = document.getElementById('sidenav-main');

function toggleSidenav() {
    const body = document.body;
    const className = 'g-sidenav-pinned';
    
    if (body.classList.contains(className)) {
        body.classList.remove(className);
        setTimeout(function() {
            sidenav.classList.remove('bg-white');
        }, 100);
        sidenav.classList.remove('bg-transparent');
    } else {
        body.classList.add(className);
        sidenav.classList.add('bg-white');
        sidenav.classList.remove('bg-transparent');
        if (iconSidenav) iconSidenav.classList.remove('d-none');
    }
}

// Основная инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 Initializing admin panel...');
    
    // Обновляем активный пункт меню
    updateActiveNavItem();
    
    // Инициализация Perfect Scrollbar
    initPerfectScrollbar();
    
    // Инициализация тултипов
    if (typeof bootstrap !== 'undefined') {
        initTooltips();
    }
    
    // Обработчики кликов по меню
    const navLinks = document.querySelectorAll('.sidenav .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            setTimeout(updateActiveNavItem, 100);
        });
    });
    
    // Обработчики для переключения сайдбара
    if (iconNavbarSidenav) {
        iconNavbarSidenav.addEventListener("click", toggleSidenav);
    }
    if (iconSidenav) {
        iconSidenav.addEventListener("click", toggleSidenav);
    }
    
    // Инициализация полей ввода
    if (document.querySelectorAll('.input-group').length != 0) {
        var allInputs = document.querySelectorAll('input.form-control');
        allInputs.forEach(el => {
            el.setAttribute("onfocus", "focused(this)");
            el.setAttribute("onfocusout", "defocused(this)");
        });
    }
});

// Обновляем меню при изменении истории браузера
window.addEventListener('popstate', updateActiveNavItem);