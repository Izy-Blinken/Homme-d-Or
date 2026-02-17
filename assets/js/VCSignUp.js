(function() {
    'use strict';
    
    const signupVerifyModal = document.getElementById('signupVerifyModal');
    const signupSuccessModal = document.getElementById('signupSuccessModal');
    const registerForm = document.getElementById('registerForm');
    const signupVerifyForm = document.getElementById('signupVerifyForm');
    const closeSignupVerify = document.getElementById('closeSignupVerify');
    const signupCodeInputs = document.querySelectorAll('.signupCodeInput');
    const signupUserEmailSpan = document.getElementById('signupUserEmail');
    const signupResendBtn = document.getElementById('signupResendBtn');
    const signupSuccessBtn = document.getElementById('signupSuccessBtn');

    function showSignupModal(modal) {
        modal.style.display = 'flex';
        modal.classList.remove('closing');
        document.body.style.overflow = 'hidden';
        
        void modal.offsetWidth;
        
        setTimeout(() => {
            modal.classList.add('active');
        }, 10);
    }

    function closeSignupModal(modal) {
        modal.classList.add('closing');
        modal.classList.remove('active');
        
        setTimeout(() => {
            modal.style.display = 'none';
            modal.classList.remove('closing');
            document.body.style.overflow = '';
        }, 300);
    }

    // reg form submission
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('signupEmail').value;
            
            console.log('SIGNUP: Form submitted with email:', email);
            
            if (signupUserEmailSpan) {
                signupUserEmailSpan.textContent = email;
            }
            
            const signupModal = document.getElementById('signupModal');
            if (signupModal) {
                signupModal.style.display = 'none';
                document.body.style.overflow = 'hidden'; 
            }
            
            if (signupVerifyModal) {
                showSignupModal(signupVerifyModal);
            } else {
                console.error('SIGNUP: Verification modal not found!');
            }
            
            signupCodeInputs.forEach(input => {
                input.value = '';
                input.classList.remove('filled');
            });
            
            if (signupCodeInputs[0]) {
                signupCodeInputs[0].focus();
            }
            
        });
    } else {
        console.error('SIGNUP: Register form not found!');
    }

    // code input handling
    signupCodeInputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            if (this.value.length === 1) {
                this.classList.add('filled');
                if (index < signupCodeInputs.length - 1) {
                    signupCodeInputs[index + 1].focus();
                }
            } else {
                this.classList.remove('filled');
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                signupCodeInputs[index - 1].focus();
            }
        });

        input.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
            
            if (pastedData) {
                const digits = pastedData.split('').slice(0, 6);
                digits.forEach((digit, i) => {
                    if (signupCodeInputs[i]) {
                        signupCodeInputs[i].value = digit;
                        signupCodeInputs[i].classList.add('filled');
                    }
                });
                
                const nextIndex = Math.min(digits.length, signupCodeInputs.length - 1);
                signupCodeInputs[nextIndex].focus();
            }
        });
    });

    // code submission
    if (signupVerifyForm) {
        signupVerifyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let code = '';
            signupCodeInputs.forEach(input => {
                code += input.value;
            });
            
            console.log('SIGNUP: Verification code entered:', code);
            
            if (code.length !== 6) {
                signupCodeInputs.forEach(input => {
                    input.classList.add('error');
                });
                
                setTimeout(() => {
                    signupCodeInputs.forEach(input => {
                        input.classList.remove('error');
                    });
                }, 500);
                
                alert('Please enter all 6 digits');
                return;
            }
                        
            closeSignupModal(signupVerifyModal);
            
            setTimeout(() => {
                if (signupSuccessModal) {
                    showSignupModal(signupSuccessModal);
                } else {
                    console.error('SIGNUP: Success modal not found!');
                }
            }, 400);
        });
    } else {
        console.error('SIGNUP: Verify form not found!');
    }

    // Resend code button
    if (signupResendBtn) {
        signupResendBtn.addEventListener('click', function() {
            
            signupCodeInputs.forEach(input => {
                input.value = '';
                input.classList.remove('filled');
                input.classList.remove('error');
            });
            
            if (signupCodeInputs[0]) {
                signupCodeInputs[0].focus();
            }
            
            const email = signupUserEmailSpan ? signupUserEmailSpan.textContent : 'your email';
            alert('Verification code resent to ' + email);
            
            this.disabled = true;
            let countdown = 30;
            const originalText = this.textContent;
            
            const interval = setInterval(() => {
                this.textContent = `Resend in ${countdown}s`;
                countdown--;
                
                if (countdown < 0) {
                    clearInterval(interval);
                    this.textContent = originalText;
                    this.disabled = false;
                }
            }, 1000);
        });
    }

    if (signupSuccessBtn) {
        signupSuccessBtn.addEventListener('click', function() {
            closeSignupModal(signupSuccessModal);
            
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 300);
        });
    }

    if (closeSignupVerify) {
        closeSignupVerify.addEventListener('click', function() {
            closeSignupModal(signupVerifyModal);
        });
    }

    if (signupVerifyModal) {
        signupVerifyModal.addEventListener('click', function(e) {
            if (e.target === signupVerifyModal) {
                closeSignupModal(signupVerifyModal);
            }
        });
    }

    if (signupSuccessModal) {
        signupSuccessModal.addEventListener('click', function(e) {
            if (e.target === signupSuccessModal) {
                closeSignupModal(signupSuccessModal);
            }
        });
    }

    
    console.log('SIGNUP: Sign Up Verification script loaded successfully!');
    console.log('SIGNUP: signupVerifyModal:', signupVerifyModal ? 'FOUND' : 'NOT FOUND');
    console.log('SIGNUP: signupSuccessModal:', signupSuccessModal ? 'FOUND' : 'NOT FOUND');
    console.log('SIGNUP: registerForm:', registerForm ? 'FOUND' : 'NOT FOUND');
    console.log('SIGNUP: signupCodeInputs:', signupCodeInputs.length, 'inputs');

})();