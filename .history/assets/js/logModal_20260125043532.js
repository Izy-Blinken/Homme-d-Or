const loginModal = document.getElementById('loginModal');
const loginCloseBtn = document.getElementsByClassName('close')[1];

function openLoginModal() {
    if (loginModal) {
        loginModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    setTimeout(() => {
            logiModal.classList.add('show');
        }, 10);
}

function closeLoginModal() {
    if (loginModal) {
        loginModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    if (loginModal) {
        loginModal.classList.remove('show');
        
        setTimeout(() => {
            loginModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 500);
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