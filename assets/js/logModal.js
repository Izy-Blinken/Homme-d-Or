if (document.getElementById('loginModal')) {    
    const loginModal = document.getElementById('loginModal');
    const loginCloseBtn = document.querySelector('#loginModal .close');

    function openLoginModal() {
        if (loginModal) {
            loginModal.classList.remove('closing');
            loginModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => {
                loginModal.classList.add('show');
            }, 10);
        }
    }

    function closeLoginModal() {
        if (loginModal) {
            loginModal.classList.remove('show');
            loginModal.classList.add('closing');

            // Restore scroll immediately so the sticky header re-evaluates correctly
            document.body.style.overflow = '';
            window.dispatchEvent(new Event('scroll'));

            setTimeout(() => {
                loginModal.style.display = 'none';
                loginModal.classList.remove('closing');
            }, 1000);
        }
    }

    if(loginCloseBtn) {
        loginCloseBtn.onclick = closeLoginModal;
    }

    window.onclick = function(event) {
        if (event.target == loginModal) {
            closeLoginModal();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLoginModal();
        }
    });
}