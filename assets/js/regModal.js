const signupModal = document.getElementById('signupModal');
const closeBtn = document.getElementsByClassName('close')[0];

const regPasswordInput = document.getElementById('regPassword');
const regConfirmPasswordInput = document.getElementById('regConfirmPassword');
const toggleRegPasswordBtn = document.getElementById('toggleRegPassword');
const toggleRegConfirmPasswordBtn = document.getElementById('toggleRegConfirmPassword');

const regStrengthBarFill = document.getElementById('regStrengthBarFill');
const regStrengthText = document.getElementById('regStrengthText');
const regPasswordMatch = document.getElementById('regPasswordMatch');

const regReqLength = document.getElementById('reg-req-length');
const regReqUppercase = document.getElementById('reg-req-uppercase');
const regReqLowercase = document.getElementById('reg-req-lowercase');
const regReqNumber = document.getElementById('reg-req-number');

const registerForm = document.getElementById('registerForm');

function openSignupModal() {
    if (signupModal) {
        signupModal.classList.remove('closing');
        signupModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            signupModal.classList.add('show');
        }, 10);
    }
}

function closeSignupModal() {
    if (signupModal) {
        signupModal.classList.remove('show');
        signupModal.classList.add('closing');

        setTimeout(() => {
            signupModal.style.display = 'none';
            signupModal.classList.remove('closing');
            document.body.style.overflow = 'auto';
        }, 1000);
    }
}

if (closeBtn) {
    closeBtn.onclick = closeSignupModal;
}

window.addEventListener('click', function(event) {
    if (event.target == signupModal) {
        closeSignupModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeSignupModal();
    }
});


// toggle visibility
if (toggleRegPasswordBtn) {
    toggleRegPasswordBtn.addEventListener('click', function() {
        togglePasswordVisibility(regPasswordInput, this);
    });
}

if (toggleRegConfirmPasswordBtn) {
    toggleRegConfirmPasswordBtn.addEventListener('click', function() {
        togglePasswordVisibility(regConfirmPasswordInput, this);
    });
}

function togglePasswordVisibility(input, button) {
    const icon = button.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}


// pw strength checker
if (regPasswordInput) {
    regPasswordInput.addEventListener('input', function() {
        const password = this.value;
        
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        
        updateRequirement(regReqLength, hasLength);
        updateRequirement(regReqUppercase, hasUppercase);
        updateRequirement(regReqLowercase, hasLowercase);
        updateRequirement(regReqNumber, hasNumber);
        
        let strength = 0;
        if (hasLength) strength++;
        if (hasUppercase) strength++;
        if (hasLowercase) strength++;
        if (hasNumber) strength++;
        
        regStrengthBarFill.className = 'strengthBarFillReg';
        regStrengthText.className = 'strengthTextReg';
        
        if (strength === 0) {
            regStrengthBarFill.style.width = '0%';
            regStrengthText.textContent = '';
        } else if (strength <= 2) {
            regStrengthBarFill.classList.add('weak');
            regStrengthText.classList.add('weak');
            regStrengthText.textContent = 'WEAK';
        } else if (strength === 3) {
            regStrengthBarFill.classList.add('medium');
            regStrengthText.classList.add('medium');
            regStrengthText.textContent = 'MEDIUM';
        } else {
            regStrengthBarFill.classList.add('strong');
            regStrengthText.classList.add('strong');
            regStrengthText.textContent = 'STRONG';
        }
        
        checkPasswordMatch();
    });
}


// pw match checker
if (regConfirmPasswordInput) {
    regConfirmPasswordInput.addEventListener('input', checkPasswordMatch);
}

function checkPasswordMatch() {
    const password = regPasswordInput.value;
    const confirmPassword = regConfirmPasswordInput.value;
    
    if (confirmPassword === '') {
        regPasswordMatch.textContent = '';
        regPasswordMatch.className = 'passwordMatchReg';
    } else if (password === confirmPassword) {
        regPasswordMatch.textContent = 'PASSWORDS MATCH';
        regPasswordMatch.className = 'passwordMatchReg match';
    } else {
        regPasswordMatch.textContent = 'PASSWORDS DO NOT MATCH';
        regPasswordMatch.className = 'passwordMatchReg no-match';
    }
}

function updateRequirement(element, met) {
    if (met) {
        element.classList.add('met');
    } else {
        element.classList.remove('met');
    }
}


// Form submission
if (registerForm) {
    registerForm.addEventListener('submit', function(e) {

        const password = regPasswordInput.value;
        const confirmPassword = regConfirmPasswordInput.value;
        
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        
        if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber) {
            e.preventDefault();
            alert('Password does not meet all requirements!');
            return false;
        }
        
        if (password !== confirmPassword) {
            e.preventDefault(); 
            alert('Passwords do not match!');
            return false;
        }
        
        console.log('Registration form validated successfully!');
    });
}


console.log('Register Modal with Password Validation loaded successfully!');