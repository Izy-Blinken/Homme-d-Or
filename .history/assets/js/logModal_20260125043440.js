const loginModal = document.getElementById('loginModal');
const loginCloseBtn = document.getElementsByClassName('close')[1];

function openLoginModal() {
    if (loginModal) {
        loginModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeLoginModal() {
    if (loginModal) {
        loginModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    if (signupModal) {
        signupModal.classList.remove('show');
        
        setTimeout(() => {
            signupModal.style.display = 'none';
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