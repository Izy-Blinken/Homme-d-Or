                                                                                                                                                                                                                                                                                                                               const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-btn');
const sidebar = document.getElementById('admin-sidebar');
const overlay = document.getElementById('sidebar-overlay');

function openMenu() {
    sidebar.classList.add('active');
    overlay.classList.add('show');
}

function closeMenu() {
    sidebar.classList.remove('active');
    overlay.classList.remove('show');
}

// Open menu
menuBtn.addEventListener('click', openMenu);
    

// Close menu
closeBtn.addEventListener('click', closeMenu);
overlay.addEventListener('click', closeMenu);
