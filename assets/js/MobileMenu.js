
(function () {
    const btn    = document.getElementById('hamburgerBtn');
    const header = document.getElementById('header');
    const menu   = document.getElementById('mobileMenu');
    if (!btn || !header || !menu) return;

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        const isOpen = header.classList.toggle('mobile-menu-open');
        btn.classList.toggle('open', isOpen);
        btn.setAttribute('aria-expanded', String(isOpen));
    });

    menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
            header.classList.remove('mobile-menu-open');
            btn.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        });
    });

    document.addEventListener('click', function (e) {
        if (!header.contains(e.target)) {
            header.classList.remove('mobile-menu-open');
            btn.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }
    });
})();