document.addEventListener('DOMContentLoaded', () => {
    const searchModal = document.getElementById('searchModal');
    const closeSearchBtn = document.getElementById('closeSearchBtn');
    const searchInput = document.querySelector('.search-form input');
    
    // Select both the mobile and desktop trigger buttons
    const openSearchBtns = document.querySelectorAll('.openSearchBtnMobile, .openSearchBtnDesktop');

    // 1. Open Modal when clicking either search trigger
    openSearchBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            searchModal.classList.add('active');
            // Slight delay to let the animation run before focusing the text box
            setTimeout(() => searchInput.focus(), 100);
        });
    });

    // 2. Close Modal when clicking the 'X'
    if (closeSearchBtn) {
        closeSearchBtn.addEventListener('click', () => {
            searchModal.classList.remove('active');
        });
    }

    // 3. Close Modal when clicking the dark background outside the content
    window.addEventListener('click', (e) => {
        if (e.target === searchModal) {
            searchModal.classList.remove('active');
        }
    });

    // 4. Close Modal when hitting the "Escape" key
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && searchModal.classList.contains('active')) {
            searchModal.classList.remove('active');
        }
    });
});