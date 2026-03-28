import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('[data-app-header]');

    if (!header) {
        return;
    }

    const syncCompactHeader = () => {
        const shouldCompact = window.scrollY > 120;
        header.classList.toggle('is-compact', shouldCompact);
    };

    syncCompactHeader();
    window.addEventListener('scroll', syncCompactHeader, { passive: true });
});
