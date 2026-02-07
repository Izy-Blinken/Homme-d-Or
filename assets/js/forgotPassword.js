const verificationModal = document.getElementById('verificationModal');
const changePasswordModal = document.getElementById('changePasswordModal');
const successModal = document.getElementById('successModal');

const emailForm = document.getElementById('emailForm');
const verificationForm = document.getElementById('verificationForm');
const changePasswordForm = document.getElementById('changePasswordForm');

const closeVerificationBtn = document.getElementById('closeVerificationModal');
const closeChangePasswordBtn = document.getElementById('closeChangePasswordModal');

const codeInputs = document.querySelectorAll('.codeInput');

const userEmailSpan = document.getElementById('userEmail');

const newPasswordInput = document.getElementById('newPassword');
const confirmPasswordInput = document.getElementById('confirmPassword');
const toggleNewPasswordBtn = document.getElementById('toggleNewPassword');
const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');

const strengthBarFill = document.getElementById('strengthBarFill');
const strengthText = document.getElementById('strengthText');
const passwordMatch = document.getElementById('passwordMatch');

const reqLength = document.getElementById('req-length');
const reqUppercase = document.getElementById('req-uppercase');
const reqLowercase = document.getElementById('req-lowercase');
const reqNumber = document.getElementById('req-number');

const resendButton = document.getElementById('resendButton');


// for send email code
emailForm.addEventListener('submit', function(e) {
    e.preventDefault(); 
    
    const email = document.getElementById('email').value;
    
    userEmailSpan.textContent = email;
    
    showModal(verificationModal);
    
    codeInputs.forEach(input => input.value = '');
    codeInputs[0].focus();
    
    console.log('Email submitted:', email);
    console.log('Verification modal should now be visible');
});

// verification code input handling
codeInputs.forEach((input, index) => {
    input.addEventListener('input', function() {
        if (this.value.length === 1) {
            this.classList.add('filled');
            if (index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }
        } else {
            this.classList.remove('filled');
        }
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && this.value === '' && index > 0) {
            codeInputs[index - 1].focus();
        }
    });

    input.addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });
});


//verification code submission
verificationForm.addEventListener('submit', function(e) {
    e.preventDefault(); 
    
    // Get the code
    let code = '';
    codeInputs.forEach(input => {
        code += input.value;
    });
    
    // Validate code (6 digits for simulation onlt)
    if (code.length !== 6) {
        alert('Please enter all 6 digits');
        return;
    }
    
    console.log('Code entered:', code);
    
    closeModal(verificationModal);
    setTimeout(() => {
        showModal(changePasswordModal);
    }, 300);
});


//resend code btn
resendButton.addEventListener('click', function() {
    codeInputs.forEach(input => {
        input.value = '';
        input.classList.remove('filled');
    });
    codeInputs[0].focus();
    
    // lagay timer kapag may actual sending of code na
    alert('Verification code resent!');
});


//pw visibility toggle
toggleNewPasswordBtn.addEventListener('click', function() {
    togglePasswordVisibility(newPasswordInput, this);
});

toggleConfirmPasswordBtn.addEventListener('click', function() {
    togglePasswordVisibility(confirmPasswordInput, this);
});

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
newPasswordInput.addEventListener('input', function() {
    const password = this.value;
    
    // Check requirements
    const hasLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    
    updateRequirement(reqLength, hasLength);
    updateRequirement(reqUppercase, hasUppercase);
    updateRequirement(reqLowercase, hasLowercase);
    updateRequirement(reqNumber, hasNumber);
    
    
    let strength = 0;
    if (hasLength) strength++;
    if (hasUppercase) strength++;
    if (hasLowercase) strength++;
    if (hasNumber) strength++;
    
    // update strengthbar
    strengthBarFill.className = 'strengthBarFill';
    strengthText.className = 'strengthText';
    
    if (strength === 0) {
        strengthBarFill.style.width = '0%';
        strengthText.textContent = '';
    } else if (strength <= 2) {
        strengthBarFill.classList.add('weak');
        strengthText.classList.add('weak');
        strengthText.textContent = 'Weak';
    } else if (strength === 3) {
        strengthBarFill.classList.add('medium');
        strengthText.classList.add('medium');
        strengthText.textContent = 'Medium';
    } else {
        strengthBarFill.classList.add('strong');
        strengthText.classList.add('strong');
        strengthText.textContent = 'Strong';
    }
    
    checkPasswordMatch();
});


// pw match checker
confirmPasswordInput.addEventListener('input', checkPasswordMatch);

function checkPasswordMatch() {
    const password = newPasswordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    
    if (confirmPassword === '') {
        passwordMatch.textContent = '';
        passwordMatch.className = 'passwordMatch';
    } else if (password === confirmPassword) {
        passwordMatch.textContent = 'Passwords match';
        passwordMatch.className = 'passwordMatch match';
    } else {
        passwordMatch.textContent = 'Passwords do not match';
        passwordMatch.className = 'passwordMatch no-match';
    }
}

function updateRequirement(element, met) {
    if (met) {
        element.classList.add('met');
    } else {
        element.classList.remove('met');
    }
}


// Change pw
changePasswordForm.addEventListener('submit', function(e) {
    e.preventDefault(); 
    
    const newPassword = newPasswordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    
    // Validate requirements
    const hasLength = newPassword.length >= 8;
    const hasUppercase = /[A-Z]/.test(newPassword);
    const hasLowercase = /[a-z]/.test(newPassword);
    const hasNumber = /[0-9]/.test(newPassword);
    
    if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber) {
        alert('Password does not meet all requirements');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        alert('Passwords do not match');
        return;
    }
    
    console.log('Password changed successfully!');
    
    // Close change password modal and show success modal
    closeModal(changePasswordModal);
    setTimeout(() => {
        showModal(successModal);
    }, 300);
});


function showModal(modal) {
    modal.classList.remove('closing');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        modal.classList.add('active');
    }, 10);
}

function closeModal(modal) {
    modal.classList.add('closing');
    modal.classList.remove('active');
    
    setTimeout(() => {
        modal.style.display = 'none';
        modal.classList.remove('closing');
        document.body.style.overflow = '';
    }, 300);
}

if (closeVerificationBtn) {
    closeVerificationBtn.addEventListener('click', function() {
        closeModal(verificationModal);
    });
}

if (closeChangePasswordBtn) {
    closeChangePasswordBtn.addEventListener('click', function() {
        closeModal(changePasswordModal);
    });
}


//close when clicked outside
[verificationModal, changePasswordModal].forEach(modal => {
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal(modal);
            }
        });
    }
});


console.log('Forgot Password script loaded successfully!');