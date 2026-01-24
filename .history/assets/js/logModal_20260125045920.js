const loginModal = document.getElementById('loginModal');
const loginCloseBtn = document.getElementsByClassName('close')[1];

// LOGIN
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

        setTimeout(() => {
            loginModal.style.display = 'none';
            loginModal.classList.remove('closing');
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