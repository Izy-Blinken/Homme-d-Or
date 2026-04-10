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
let captchaCode = '';

let lastErrorEmail = '';

if (document.getElementById('signupModal')) {

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
            
            if (regStrengthBarFill) regStrengthBarFill.className = 'strengthBarFillReg';
            if (regStrengthText) regStrengthText.className = 'strengthTextReg';
            
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
        
    if (!regPasswordInput || !regConfirmPasswordInput) return;
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

    function generateCaptcha() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        captchaCode = Array.from({length: 6}, () =>
            chars[Math.floor(Math.random() * chars.length)]
        ).join('');
        drawCaptcha();
        if (captchaInput) captchaInput.value = '';
    }

    function drawCaptcha() {
        if (!captchaCanvas) return;
        const ctx = captchaCanvas.getContext('2d');
        const w = captchaCanvas.width, h = captchaCanvas.height;
        ctx.fillStyle = '#f0eeee';
        ctx.fillRect(0, 0, w, h);
        for (let i = 0; i < 6; i++) {
            ctx.beginPath();
            ctx.moveTo(Math.random() * w, Math.random() * h);
            ctx.lineTo(Math.random() * w, Math.random() * h);
            ctx.strokeStyle = 'rgba(0,0,0,0.08)';
            ctx.lineWidth = 1.5;
            ctx.stroke();
        }
        for (let i = 0; i < 80; i++) {
            ctx.fillStyle = `rgba(0,0,0,${Math.random() * 0.12})`;
            ctx.beginPath();
            ctx.arc(Math.random() * w, Math.random() * h, 1, 0, Math.PI * 2);
            ctx.fill();
        }
        const colors = ['#c0392b','#1a5276','#117a65','#784212','#4a235a','#1b2631'];
        captchaCode.split('').forEach((ch, i) => {
            ctx.save();
            ctx.translate(18 + i * 26, 36 + (Math.random() * 10 - 5));
            ctx.rotate((Math.random() - 0.5) * 0.5);
            ctx.font = `${Math.floor(Math.random() * 6) + 20}px monospace`;
            ctx.fillStyle = colors[i % colors.length];
            ctx.fillText(ch, 0, 0);
            ctx.restore();
        });
    }

    function openSignupModal() {
        if (signupModal) {
            signupModal.classList.remove('closing');
            signupModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                signupModal.classList.add('show');
            }, 10);
            generateCaptcha();
        }
    }

    if (signupEmailInput) signupEmailInput.addEventListener('input', function () {
        if (this.value !== lastErrorEmail) {
            clearSignupError();
        }
    });


    // Form submission
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            clearSignupError();
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

            fetch('../backend/loginSignUp/signup.php', {
                method: 'POST',
                body: new FormData(registerForm)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'verifyCodeSignUp.php';    
                } else {
                    showSignupError(data.message || 'Email is already registered.');
                }
            })
        });
    }

    function showSignupError (msg) {
        const e = document.getElementById('signupServerError')
        if (e) {
            e.textContent = msg;
            e.style.display = 'block';
        }
        lastErrorEmail = signupEmailInput.value;
    }

    function clearSignupError() {
        const e = document.getElementById('signupServerError');
        if (e) {
            e.textContent = '';
            e.style.display = 'none';
        }
    }

    console.log('Register Modal with Password Validation loaded successfully!');
}