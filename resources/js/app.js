import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

/**
 * ===========================================================
 *  SISTEMA DE NOTIFICACIONES GLOBAL (window.notify)
 * ===========================================================
 *
 * Soporta: success, error, warning, info.
 * Autocierre, animación, clic para cerrar.
 * Compatible con Blade: <x-notify /> o notify.blade.php
 * ===========================================================
 */

window.notify = (function () {
    let timer = null;

    const el         = () => document.getElementById('notify');
    const card       = () => document.getElementById('notify-card');
    const icon       = () => document.getElementById('notify-icon');
    const iconWrap   = () => document.getElementById('notify-icon-wrap');
    const message    = () => document.getElementById('notify-message');
    const closeBtn   = () => document.getElementById('notify-close');

    // Configuración de colores / iconos
    const types = {
        success: {
            border: '#16a34a', // verde
            accent: '#16a34a',
            svg: 'M5 13l4 4L19 7',
        },
        error: {
            border: '#dc2626', // rojo
            accent: '#dc2626',
            svg: 'M6 18L18 6M6 6l12 12',
        },
        warning: {
            border: '#f59e0b', // amarillo
            accent: '#f59e0b',
            svg: 'M12 9v4m0 4h.01',
        },
        info: {
            border: '#2563eb', // azul
            accent: '#2563eb',
            svg: 'M13 16h-1v-4h-1m1-4h.01',
        },
    };

    /** Construye el ícono SVG */
    function setIcon(pathD) {
        icon().innerHTML = '';

        const p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        p.setAttribute('stroke-linecap', 'round');
        p.setAttribute('stroke-linejoin', 'round');
        p.setAttribute('stroke-width', '2');
        p.setAttribute('d', pathD);

        icon().appendChild(p);
    }

    /** Muestra la notificación */
    function show(msg, type = 'info', timeout = 5000) {
        const t = types[type] || types.info;
        const n = el();

        if (!n) return; // por si la vista no tiene el componente

        message().textContent = msg;

        // Colores dinámicos
        card().style.borderLeftColor = t.border;
        icon().style.color = t.accent;
        iconWrap().style.backgroundColor = `${t.border}22`;

        // Icono
        setIcon(t.svg);

        // Reiniciar animación
        n.classList.remove('hidden');
        requestAnimationFrame(() => {
            n.classList.remove('opacity-0', '-translate-y-2');
            n.classList.add('opacity-100', 'translate-y-0');
        });

        // Autocierre
        if (timer) clearTimeout(timer);
        if (timeout > 0) timer = setTimeout(hide, timeout);
    }

    /** Ocultar la notificación con animación */
    function hide() {
        const n = el();
        if (!n) return;

        n.classList.add('opacity-0', '-translate-y-2');
        n.classList.remove('opacity-100', 'translate-y-0');

        if (timer) clearTimeout(timer);
        timer = setTimeout(() => n.classList.add('hidden'), 250);
    }

    /** Botón cerrar (la X) */
    document.addEventListener('DOMContentLoaded', () => {
        const btn = closeBtn();
        if (btn) btn.addEventListener('click', hide);
    });

    return { show, hide };
})();
