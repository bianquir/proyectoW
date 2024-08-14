import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Menú desplegable
    const toggles = document.querySelectorAll(".dropdown-toggle");
    const menus = document.querySelectorAll(".dropdown-menu");

    toggles.forEach((toggle, index) => {
        const menu = menus[index];

        toggle.addEventListener("click", function (event) {
            menu.classList.toggle("show");
            tooltipList.forEach(tooltip => tooltip.hide()); // Ocultar todos los tooltips
        });

        document.addEventListener("click", function (event) {
            if (!toggle.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.remove("show");
            }
        });

        const dropdownItems = menu.querySelectorAll(".dropdown-item");
        dropdownItems.forEach(function (item) {
            item.addEventListener("click", function (event) {
                dropdownItems.forEach(function (el) {
                    el.classList.remove("active");
                });
                item.classList.add("active");
            });
        });
    });

    // Ocultar tooltips al hacer clic en el menú
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', function () {
            tooltipList.forEach(tooltip => tooltip.hide());
        });
    });

});


document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('success-alert');
    if (alert) {
        setTimeout(function() {
            alert.style.display = 'none';
        }, 3000);
    }
});