(function () {
    'use strict';


    const registerForm       = document.getElementById('registerForm');
    const signupModal        = document.getElementById('signupModal');
    const signupVerifyModal  = document.getElementById('signupVerifyModal');
    const signupSuccessModal = document.getElementById('signupSuccessModal');
    const signupVerifyForm   = document.getElementById('signupVerifyForm');
    const signupCodeInputs   = document.querySelectorAll('.signupCodeInput');
    const signupResendBtn    = document.getElementById('signupResendBtn');
    const signupSuccessBtn   = document.getElementById('signupSuccessBtn');
    const closeSignupVerify  = document.getElementById('closeSignupVerify');
    const otpError           = document.getElementById('otpError');

    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const password = document.getElementById('regPassword')?.value ?? '';
            const confirm  = document.getElementById('regConfirmPassword')?.value ?? '';

            if (password !== confirm) {
                e.preventDefault();
                showFieldError('regConfirmPassword', 'Passwords do not match.');
                return;
            }

            if (
                password.length < 8 ||
                !/[A-Z]/.test(password) ||
                !/[a-z]/.test(password) ||
                !/[0-9]/.test(password)
            ) {
                e.preventDefault();
                showFieldError('regPassword',
                    'Password must be 8+ characters with uppercase, lowercase, and a number.');
                return;
            }
            // All valid — POST naturally to backend/loginSignUp/signup.php
        });

        // Re-open signup modal if signup.php bounced back with a session error
        const signupErrorEl = document.getElementById('signupError');
        if (signupErrorEl && signupErrorEl.textContent.trim() !== '' && signupModal) {
            signupModal.style.display = 'flex';
            setTimeout(() => signupModal.classList.add('active'), 10);
            document.body.style.overflow = 'hidden';
        }
    }

    if (signupVerifyModal) {

        signupVerifyModal.style.display = 'flex';
        setTimeout(() => signupVerifyModal.classList.add('active'), 10);
        document.body.style.overflow = 'hidden';

        // ── Helpers ────────────────────────────────────────────
        function showModal(modal) {
            modal.style.display = 'flex';
            modal.classList.remove('closing');
            document.body.style.overflow = 'hidden';
            void modal.offsetWidth;
            setTimeout(() => modal.classList.add('active'), 10);
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

        function showError(msg) {
            if (otpError) {
                otpError.textContent = msg;
                otpError.style.display = 'block';
            }
        }

        function clearError() {
            if (otpError) {
                otpError.style.display = 'none';
                otpError.textContent = '';
            }
        }

        // ── Code inputs ────────────────────────────────────────
        signupCodeInputs.forEach((input, index) => {

            input.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1);
                if (this.value.length === 1) {
                    this.classList.add('filled');
                    if (index < signupCodeInputs.length - 1) {
                        signupCodeInputs[index + 1].focus();
                    }
                } else {
                    this.classList.remove('filled');
                }
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    signupCodeInputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', function (e) {
                e.preventDefault();
                const digits = e.clipboardData
                    .getData('text')
                    .replace(/[^0-9]/g, '')
                    .split('')
                    .slice(0, 6);

                digits.forEach((d, i) => {
                    if (signupCodeInputs[i]) {
                        signupCodeInputs[i].value = d;
                        signupCodeInputs[i].classList.add('filled');
                    }
                });

                const next = Math.min(digits.length, signupCodeInputs.length - 1);
                signupCodeInputs[next].focus();
            });
        });

        // OTP
        if (signupVerifyForm) {
            signupVerifyForm.addEventListener('submit', function (e) {
                e.preventDefault();
                clearError();

                let code = '';
                signupCodeInputs.forEach(input => (code += input.value));

                if (code.length !== 6) {
                    signupCodeInputs.forEach(i => i.classList.add('error'));
                    setTimeout(() => signupCodeInputs.forEach(i => i.classList.remove('error')), 500);
                    showError('Please enter all 6 digits.');
                    return;
                }

                fetch('../backend/auth/email_verification.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ code })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        closeModal(signupVerifyModal);
                        setTimeout(() => showModal(signupSuccessModal), 300);
                    } else {
                        signupCodeInputs.forEach(i => i.classList.add('error'));
                        setTimeout(() => signupCodeInputs.forEach(i => i.classList.remove('error')), 500);
                        showError(data.message || 'Verification failed.');
                    }
                })
                .catch(() => showError('Server error. Please try again.'));
            });
        }

        // ── Resend OTP ─────────────────────────────────────────
        if (signupResendBtn) {
            signupResendBtn.addEventListener('click', function () {
                this.disabled = true;
                clearError();

                fetch('../backend/auth/resend_otp.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'resend' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        signupCodeInputs.forEach(i => {
                            i.value = '';
                            i.classList.remove('filled', 'error');
                        });
                        signupCodeInputs[0].focus();

                        let countdown = 30;
                        const btn = this;
                        const interval = setInterval(() => {
                            btn.textContent = `Resend in ${countdown}s`;
                            countdown--;
                            if (countdown < 0) {
                                clearInterval(interval);
                                btn.textContent = 'Resend Code';
                                btn.disabled = false;
                            }
                        }, 1000);
                    } else {
                        showError(data.message || 'Could not resend code.');
                        this.disabled = false;
                    }
                })
                .catch(() => {
                    showError('Server error. Please try again.');
                    this.disabled = false;
                });
            });
        }

        //Succes
        if (signupSuccessBtn) {
            signupSuccessBtn.addEventListener('click', function () {
                closeModal(signupSuccessModal);
                setTimeout(() => (window.location.href = 'index.php'), 300);
            });
        }

        // Close Modal
        if (closeSignupVerify) {
            closeSignupVerify.addEventListener('click', () => closeModal(signupVerifyModal));
        }

        [signupVerifyModal, signupSuccessModal].forEach(modal => {
            if (modal) {
                modal.addEventListener('click', e => {
                    if (e.target === modal) closeModal(modal);
                });
            }
        });
    }

    // Error
    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        field.classList.add('error');

        let errEl = field.parentElement.querySelector('.fieldError');
        if (!errEl) {
            errEl = document.createElement('p');
            errEl.className = 'fieldError';
            errEl.style.cssText = 'color:red; font-size:12px; margin:4px 0 0;';
            field.parentElement.appendChild(errEl);
        }
        errEl.textContent = message;

        field.addEventListener('input', function () {
            field.classList.remove('error');
            errEl.textContent = '';
        }, { once: true });
    }
})();