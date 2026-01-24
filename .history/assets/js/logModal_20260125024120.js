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
}

