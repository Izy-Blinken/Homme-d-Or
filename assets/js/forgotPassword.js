document.addEventListener('DOMContentLoaded', function () {

    const emailForm = document.getElementById('emailForm');
    if (!emailForm) return; // Not on forgot password page, stop here

    // ── Element references ────────────────────────────────
    const verificationModal    = document.getElementById('verificationModal');
    const changePasswordModal  = document.getElementById('changePasswordModal');
    const successModal         = document.getElementById('successModal');

    const verificationForm     = document.getElementById('verificationForm');
    const changePasswordForm   = document.getElementById('changePasswordForm');

    const closeVerificationBtn   = document.getElementById('closeVerificationModal');
    const closeChangePasswordBtn = document.getElementById('closeChangePasswordModal');

    const codeInputs             = document.querySelectorAll('.codeInput');
    const userEmailSpan          = document.getElementById('userEmail');
    const newPasswordInput       = document.getElementById('newPassword');
    const confirmPasswordInput   = document.getElementById('confirmPassword');
    const toggleNewPasswordBtn   = document.getElementById('toggleNewPassword');
    const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');
    const strengthBarFill        = document.getElementById('strengthBarFill');
    const strengthText           = document.getElementById('strengthText');
    const passwordMatch          = document.getElementById('passwordMatch');
    const reqLength              = document.getElementById('req-length');
    const reqUppercase           = document.getElementById('req-uppercase');
    const reqLowercase           = document.getElementById('req-lowercase');
    const reqNumber              = document.getElementById('req-number');
    const resendButton           = document.getElementById('resendButton');

    let resendCooldown = null;

    // ── Helpers ───────────────────────────────────────────
    function showModal(modal) {
        modal.classList.remove('closing');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
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

    function showError(elementId, msg) {
        const el = document.getElementById(elementId);
        if (el) { el.textContent = msg; el.style.display = 'block'; }
    }

    function clearError(elementId) {
        const el = document.getElementById(elementId);
        if (el) { el.textContent = ''; el.style.display = 'none'; }
    }

    function startResendCooldown(seconds) {
        if (resendCooldown) clearInterval(resendCooldown);
        let countdown = seconds;
        resendButton.disabled = true;
        resendButton.textContent = `Resend in ${countdown}s`;

        resendCooldown = setInterval(() => {
            countdown--;
            resendButton.textContent = `Resend in ${countdown}s`;
            if (countdown <= 0) {
                clearInterval(resendCooldown);
                resendCooldown = null;
                resendButton.textContent = 'Resend Code';
                resendButton.disabled = false;
            }
        }, 1000);
    }

    function togglePasswordVisibility(input, button) {
        const icon = button.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function updateRequirement(element, met) {
        element.classList.toggle('met', met);
    }

    function checkPasswordMatch() {
        const pw  = newPasswordInput.value;
        const cpw = confirmPasswordInput.value;
        if (cpw === '') {
            passwordMatch.textContent = '';
            passwordMatch.className   = 'passwordMatch';
        } else if (pw === cpw) {
            passwordMatch.textContent = 'Passwords match';
            passwordMatch.className   = 'passwordMatch match';
        } else {
            passwordMatch.textContent = 'Passwords do not match';
            passwordMatch.className   = 'passwordMatch no-match';
        }
    }

    // ── Send OTP ──────────────────────────────────────────
    emailForm.addEventListener('submit', function (e) {
        e.preventDefault();
        clearError('emailError');

        const email     = document.getElementById('email').value.trim();
        const submitBtn = document.getElementById('sendVC');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';

        fetch('../backend/forgot_pass/forgot_Password.php', {
            method: 'POST',
            body: new URLSearchParams({ email })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                userEmailSpan.textContent = email;
                codeInputs.forEach(i => { i.value = ''; i.classList.remove('filled'); });
                showModal(verificationModal);
                codeInputs[0].focus();
                startResendCooldown(60);
            } else {
                showError('emailError', data.message || 'Something went wrong.');
            }
        })
        .catch(() => showError('emailError', 'Server error. Please try again.'))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Send Verification Code';
        });
    });

    // ── Code inputs ───────────────────────────────────────
    codeInputs.forEach((input, index) => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1);
            if (this.value.length === 1) {
                this.classList.add('filled');
                if (index < codeInputs.length - 1) codeInputs[index + 1].focus();
            } else {
                this.classList.remove('filled');
            }
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                codeInputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const digits = e.clipboardData.getData('text')
                .replace(/[^0-9]/g, '').split('').slice(0, 6);
            digits.forEach((d, i) => {
                if (codeInputs[i]) { codeInputs[i].value = d; codeInputs[i].classList.add('filled'); }
            });
            const next = Math.min(digits.length, codeInputs.length - 1);
            codeInputs[next].focus();
        });
    });

    // ── Verify OTP ────────────────────────────────────────
    verificationForm.addEventListener('submit', function (e) {
        e.preventDefault();
        clearError('otpError');

        let code = '';
        codeInputs.forEach(i => (code += i.value));

        if (code.length !== 6) {
            codeInputs.forEach(i => i.classList.add('error'));
            setTimeout(() => codeInputs.forEach(i => i.classList.remove('error')), 500);
            showError('otpError', 'Please enter all 6 digits.');
            return;
        }

        fetch('../backend/auth/verify_reset_otp.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ code })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeModal(verificationModal);
                setTimeout(() => showModal(changePasswordModal), 300);
            } else {
                codeInputs.forEach(i => i.classList.add('error'));
                setTimeout(() => codeInputs.forEach(i => i.classList.remove('error')), 500);
                showError('otpError', data.message || 'Verification failed.');
            }
        })
        .catch(() => showError('otpError', 'Server error. Please try again.'));
    });

    // ── Resend OTP ────────────────────────────────────────
    resendButton.addEventListener('click', function () {
        this.disabled = true;
        clearError('otpError');

        const email = userEmailSpan.textContent;

        fetch('../backend/forgot_pass/forgot_Password.php', {
            method: 'POST',
            body: new URLSearchParams({ email })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                codeInputs.forEach(i => { i.value = ''; i.classList.remove('filled', 'error'); });
                codeInputs[0].focus();
                startResendCooldown(60);
            } else {
                showError('otpError', data.message || 'Could not resend code.');
                this.disabled = false;
            }
        })
        .catch(() => {
            showError('otpError', 'Server error. Please try again.');
            this.disabled = false;
        });
    });

    // ── Password visibility ───────────────────────────────
    toggleNewPasswordBtn.addEventListener('click', function () {
        togglePasswordVisibility(newPasswordInput, this);
    });
    toggleConfirmPasswordBtn.addEventListener('click', function () {
        togglePasswordVisibility(confirmPasswordInput, this);
    });

    // ── Password strength ─────────────────────────────────
    newPasswordInput.addEventListener('input', function () {
        const password     = this.value;
        const hasLength    = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber    = /[0-9]/.test(password);

        updateRequirement(reqLength,    hasLength);
        updateRequirement(reqUppercase, hasUppercase);
        updateRequirement(reqLowercase, hasLowercase);
        updateRequirement(reqNumber,    hasNumber);

        let strength = [hasLength, hasUppercase, hasLowercase, hasNumber].filter(Boolean).length;

        strengthBarFill.className = 'strengthBarFill';
        strengthText.className    = 'strengthText';

        if (strength === 0) {
            strengthBarFill.style.width = '0%';
            strengthText.textContent    = '';
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

    confirmPasswordInput.addEventListener('input', checkPasswordMatch);

    // ── Reset password ────────────────────────────────────
    changePasswordForm.addEventListener('submit', function (e) {
        e.preventDefault();
        clearError('resetError');

        const password = newPasswordInput.value;
        const confirm  = confirmPasswordInput.value;

        if (
            password.length < 8 ||
            !/[A-Z]/.test(password) ||
            !/[a-z]/.test(password) ||
            !/[0-9]/.test(password)
        ) {
            showError('resetError', 'Password does not meet all requirements.');
            return;
        }

        if (password !== confirm) {
            showError('resetError', 'Passwords do not match.');
            return;
        }

        fetch('../backend/auth/reset_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ password, confirm_password: confirm })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeModal(changePasswordModal);
                setTimeout(() => showModal(successModal), 300);
            } else {
                showError('resetError', data.message || 'Failed to reset password.');
            }
        })
        .catch(() => showError('resetError', 'Server error. Please try again.'));
    });

    // ── Close buttons ─────────────────────────────────────
    if (closeVerificationBtn) closeVerificationBtn.addEventListener('click', () => closeModal(verificationModal));
    if (closeChangePasswordBtn) closeChangePasswordBtn.addEventListener('click', () => closeModal(changePasswordModal));

    [verificationModal, changePasswordModal].forEach(modal => {
        if (modal) modal.addEventListener('click', e => { if (e.target === modal) closeModal(modal); });
    });

}); 