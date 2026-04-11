const signupModal = document.getElementById('signupModal');
const closeBtn = document.querySelector('#signupModal .close');

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

const signupEmailInput = document.getElementById('signupEmail');
const registerForm = document.getElementById('registerForm');
const captchaCanvas = document.getElementById('captchaCanvas');
const captchaInput = document.getElementById('captchaInput');

// ✅ No separate verifyBtn — captcha is checked on form submit
let captchaCode = '';
let lastErrorEmail = '';

if (document.getElementById('signupModal')) {

    // ─────────────────────────────────────────────
    // MODAL OPEN / CLOSE
    // ─────────────────────────────────────────────
    function openSignupModal() {
        if (signupModal) {
            signupModal.classList.remove('closing');
            signupModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => signupModal.classList.add('show'), 10);
            generateCaptcha();
        }
    }

    function closeSignupModal() {
        if (signupModal) {
            signupModal.classList.remove('show');
            signupModal.classList.add('closing');

            // Restore scroll immediately so the sticky header re-evaluates correctly
            document.body.style.overflow = '';
            window.dispatchEvent(new Event('scroll'));

            setTimeout(() => {
                signupModal.style.display = 'none';
                signupModal.classList.remove('closing');
            }, 1000);
        }
    }

    if (closeBtn) closeBtn.onclick = closeSignupModal;

    window.addEventListener('click', function (event) {
        if (event.target == signupModal) closeSignupModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeSignupModal();
    });

    // ─────────────────────────────────────────────
    // PASSWORD VISIBILITY TOGGLE
    // ─────────────────────────────────────────────
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

    if (toggleRegPasswordBtn) {
        toggleRegPasswordBtn.addEventListener('click', function () {
            togglePasswordVisibility(regPasswordInput, this);
        });
    }

    if (toggleRegConfirmPasswordBtn) {
        toggleRegConfirmPasswordBtn.addEventListener('click', function () {
            togglePasswordVisibility(regConfirmPasswordInput, this);
        });
    }

    // ─────────────────────────────────────────────
    // PASSWORD STRENGTH CHECKER
    // ─────────────────────────────────────────────
    if (regPasswordInput) {
        regPasswordInput.addEventListener('input', function () {
            const password = this.value;

            const hasLength    = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber    = /[0-9]/.test(password);

            updateRequirement(regReqLength,    hasLength);
            updateRequirement(regReqUppercase, hasUppercase);
            updateRequirement(regReqLowercase, hasLowercase);
            updateRequirement(regReqNumber,    hasNumber);

            let strength = 0;
            if (hasLength)    strength++;
            if (hasUppercase) strength++;
            if (hasLowercase) strength++;
            if (hasNumber)    strength++;

            if (regStrengthBarFill) regStrengthBarFill.className = 'strengthBarFillReg';
            if (regStrengthText)    regStrengthText.className    = 'strengthTextReg';

            if (strength === 0) {
                if (regStrengthBarFill) regStrengthBarFill.style.width = '0%';
                if (regStrengthText)    regStrengthText.textContent    = '';
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

    // ─────────────────────────────────────────────
    // PASSWORD MATCH CHECKER
    // ─────────────────────────────────────────────
    if (regConfirmPasswordInput) {
        regConfirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }

    function checkPasswordMatch() {
        if (!regPasswordInput || !regConfirmPasswordInput) return;
        const password        = regPasswordInput.value;
        const confirmPassword = regConfirmPasswordInput.value;

        if (confirmPassword === '') {
            regPasswordMatch.textContent = '';
            regPasswordMatch.className   = 'passwordMatchReg';
        } else if (password === confirmPassword) {
            regPasswordMatch.textContent = 'PASSWORDS MATCH';
            regPasswordMatch.className   = 'passwordMatchReg match';
        } else {
            regPasswordMatch.textContent = 'PASSWORDS DO NOT MATCH';
            regPasswordMatch.className   = 'passwordMatchReg no-match';
        }
    }

    function updateRequirement(element, met) {
        if (met) element.classList.add('met');
        else     element.classList.remove('met');
    }

    // ─────────────────────────────────────────────
    // CAPTCHA GENERATE & DRAW
    // ─────────────────────────────────────────────
    function generateCaptcha() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        captchaCode = Array.from({ length: 6 }, () =>
            chars[Math.floor(Math.random() * chars.length)]
        ).join('');
        drawCaptcha();
        if (captchaInput) captchaInput.value = '';
    }

    function drawCaptcha() {
        if (!captchaCanvas) return;
        const ctx = captchaCanvas.getContext('2d');
        const w   = captchaCanvas.width;
        const h   = captchaCanvas.height;

        ctx.fillStyle = '#f0eeee';
        ctx.fillRect(0, 0, w, h);

        for (let i = 0; i < 6; i++) {
            ctx.beginPath();
            ctx.moveTo(Math.random() * w, Math.random() * h);
            ctx.lineTo(Math.random() * w, Math.random() * h);
            ctx.strokeStyle = 'rgba(0,0,0,0.08)';
            ctx.lineWidth   = 1.5;
            ctx.stroke();
        }

        for (let i = 0; i < 80; i++) {
            ctx.fillStyle = `rgba(0,0,0,${Math.random() * 0.12})`;
            ctx.beginPath();
            ctx.arc(Math.random() * w, Math.random() * h, 1, 0, Math.PI * 2);
            ctx.fill();
        }

        const colors = ['#c0392b', '#1a5276', '#117a65', '#784212', '#4a235a', '#1b2631'];
        captchaCode.split('').forEach((ch, i) => {
            ctx.save();
            ctx.translate(18 + i * 26, 36 + (Math.random() * 10 - 5));
            ctx.rotate((Math.random() - 0.5) * 0.5);
            ctx.font      = `${Math.floor(Math.random() * 6) + 20}px monospace`;
            ctx.fillStyle = colors[i % colors.length];
            ctx.fillText(ch, 0, 0);
            ctx.restore();
        });
    }

    // ─────────────────────────────────────────────
    // EMAIL INPUT — clear error on change
    // ─────────────────────────────────────────────
    if (signupEmailInput) {
        signupEmailInput.addEventListener('input', function () {
            if (this.value !== lastErrorEmail) clearSignupError();
        });
    }

    // ─────────────────────────────────────────────
    // FORM SUBMISSION — captcha checked here
    // ─────────────────────────────────────────────
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearSignupError();

            const password        = regPasswordInput.value;
            const confirmPassword = regConfirmPasswordInput.value;

            const hasLength    = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber    = /[0-9]/.test(password);

            // ── Validate password requirements
            if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber) {
                showSignupError('Password does not meet all requirements.');
                return;
            }

            // ── Validate password match
            if (password !== confirmPassword) {
                showSignupError('Passwords do not match.');
                return;
            }

            // ── Validate captcha inline on submit
            const userCaptcha = captchaInput ? captchaInput.value.trim().toUpperCase() : '';

            if (!userCaptcha) {
                showSignupError('Please enter the CAPTCHA code.');
                return;
            }

            if (userCaptcha !== captchaCode) {
                showSignupError('Incorrect CAPTCHA. Please try again.');
                generateCaptcha(); // refresh on wrong attempt
                return;
            }

            // ── All checks passed — submit
            const submitBtn    = document.getElementById('createAccountBtn');
            const originalText = submitBtn ? submitBtn.textContent : 'CREATE ACCOUNT';

            if (submitBtn) {
                submitBtn.disabled    = true;
                submitBtn.textContent = 'SENDING...';
            }

            fetch('../backend/loginSignUp/signup.php', {
                method: 'POST',
                body: new FormData(registerForm)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'verifyCodeSignUp.php';
                } else {
                    showSignupError(data.message || 'Registration failed.');
                    if (submitBtn) {
                        submitBtn.disabled    = false;
                        submitBtn.textContent = originalText;
                    }
                    generateCaptcha();
                }
            })
            .catch(() => {
                showSignupError('Something went wrong. Please try again.');
                if (submitBtn) {
                    submitBtn.disabled    = false;
                    submitBtn.textContent = originalText;
                }
            });
        });
    }

    // ─────────────────────────────────────────────
    // ERROR HELPERS
    // ─────────────────────────────────────────────
    function showSignupError(msg) {
        const e = document.getElementById('signupServerError');
        if (e) {
            e.textContent   = msg;
            e.style.display = 'block';
        }
        lastErrorEmail = signupEmailInput ? signupEmailInput.value : '';
    }

    function clearSignupError() {
        const e = document.getElementById('signupServerError');
        if (e) {
            e.textContent   = '';
            e.style.display = 'none';
        }
    }

    console.log('Register Modal with Password Validation loaded successfully!');
}