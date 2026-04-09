document.addEventListener('DOMContentLoaded', function () {
    const editBtn = document.getElementById('edit-profile-btn');
    const editModal = document.getElementById('edit-modal');
    const closeBtn = document.getElementById('edit-modal-close');
    const cancelBtn = document.getElementById('edit-modal-cancel');
    const editForm = document.getElementById('edit-profile-form');

    function openEditModal() {
        editModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        editModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    if (editBtn) editBtn.addEventListener('click', openEditModal);
    if (closeBtn) closeBtn.addEventListener('click', closeEditModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeEditModal);

    editModal.addEventListener('click', function (e) {
        if (e.target === editModal) closeEditModal();
    });

    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(editForm);

            fetch('../../backend/profile/update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeEditModal();
                    showAdminToast('Profile updated successfully!');
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showAdminToast(data.message || 'Something went wrong.');
                }
            })
            .catch(() => showAdminToast('Could not connect to the server.'));
        });
    }
});

//  Logout Modal 

function openLogoutModal() {
    document.getElementById('logout-confirm-modal').style.display = 'flex';
}

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('logout-confirm-modal');
    const closeBtn = document.getElementById('logout-confirm-close');
    const cancelBtn = document.getElementById('logout-confirm-cancel');
    const yesBtn = document.getElementById('logout-confirm-yes');

    if (closeBtn) closeBtn.addEventListener('click', () => modal.style.display = 'none');
    if (cancelBtn) cancelBtn.addEventListener('click', () => modal.style.display = 'none');
    if (yesBtn) yesBtn.addEventListener('click', () => {
        window.location.href = '../../backend/auth/logout.php';
    });
});

//  Toast 

function showAdminToast(message) {
    let toast = document.getElementById('adminToast');

    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'adminToast';
        toast.style.cssText = [
            'position:fixed', 'bottom:24px', 'right:24px', 'z-index:9999',
            'background:#1e1e1e', 'color:#fff', 'padding:12px 20px',
            'border-radius:8px', 'border:1px solid rgba(212,175,55,0.4)',
            'font-size:0.875rem', 'opacity:0', 'transition:opacity 0.3s',
            'pointer-events:none'
        ].join(';');
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.style.opacity = '1';
    clearTimeout(toast._hide);
    toast._hide = setTimeout(() => { toast.style.opacity = '0'; }, 3000);
}

//  Admin Change Password Modal

document.addEventListener('DOMContentLoaded', function () {
    // Open via "Change Password" button
    const cpBtn = document.getElementById('change-password-btn');
    if (cpBtn) cpBtn.addEventListener('click', openAdminCpModal);

    // Close / cancel buttons for each step
    ['admin-cp-close-1', 'admin-cp-cancel-1',
     'admin-cp-close-2',
     'admin-cp-close-3', 'admin-cp-cancel-3'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('click', closeAdminCpModal);
    });

    // Step 2 "Back" goes back to step 1
    const back2 = document.getElementById('admin-cp-cancel-2');
    if (back2) back2.addEventListener('click', () => adminCpShowStep(1));

    // Close on overlay click
    const modal = document.getElementById('admin-cp-modal');
    if (modal) modal.addEventListener('click', e => {
        if (e.target === modal) closeAdminCpModal();
    });

    // OTP digit inputs behaviour
    const inputs = document.querySelectorAll('.adminCpCodeInput');
    inputs.forEach((input, index) => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1);
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    // Password strength + match watchers
    const newPwInput  = document.getElementById('adminCpNewPassword');
    const confPwInput = document.getElementById('adminCpConfirmPassword');
    if (newPwInput) {
        newPwInput.addEventListener('input', function () {
            adminCpUpdateStrength(this.value);
            adminCpCheckMatch();
        });
    }
    if (confPwInput) {
        confPwInput.addEventListener('input', adminCpCheckMatch);
    }
});

function openAdminCpModal() {
    adminCpShowStep(1);
    document.getElementById('adminCpCurrentPassword').value = '';
    document.getElementById('adminCpStep1Error').style.display = 'none';
    document.getElementById('admin-cp-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeAdminCpModal() {
    document.getElementById('admin-cp-modal').style.display = 'none';
    document.body.style.overflow = '';
}

function adminCpShowStep(n) {
    [1, 2, 3, 4].forEach(i => {
        const el = document.getElementById('adminCpStep' + i);
        if (el) el.style.display = (i === n) ? 'block' : 'none';
    });
}

// Step 1 → send OTP
function adminSendChangePasswordOtp() {
    const currentPassword = document.getElementById('adminCpCurrentPassword').value.trim();
    const errorEl = document.getElementById('adminCpStep1Error');
    errorEl.style.display = 'none';

    if (!currentPassword) {
        errorEl.textContent = 'Please enter your current password.';
        errorEl.style.display = 'block';
        return;
    }

    const formData = new FormData();
    formData.append('current_password', currentPassword);

    fetch('../../backend/profile/send_admin_change_password_otp.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            adminCpShowStep(2);
            document.querySelectorAll('.adminCpCodeInput').forEach(i => i.value = '');
            document.getElementById('adminCpOtpError').style.display = 'none';
            showAdminToast('Verification code sent to your email.');
            adminCpStartResendCooldown(60);
        } else {
            errorEl.textContent = data.message || 'Failed to send code.';
            errorEl.style.display = 'block';
        }
    })
    .catch(() => showAdminToast('Could not connect to the server.'));
}

// Step 2 → resend OTP
function adminResendChangePasswordOtp() {
    const resendBtn = document.getElementById('adminCpResendBtn');
    resendBtn.disabled = true;
    resendBtn.textContent = 'Sending...';

    fetch('../../backend/profile/send_admin_change_password_otp.php', {
        method: 'POST'
        // No current_password — backend skips verify on resend
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.adminCpCodeInput').forEach(i => i.value = '');
            document.getElementById('adminCpOtpError').style.display = 'none';
            showAdminToast('Verification code resent.');
            adminCpStartResendCooldown(60);
        } else {
            showAdminToast(data.message || 'Failed to resend code.');
            resendBtn.disabled = false;
            resendBtn.textContent = 'Resend Code';
        }
    })
    .catch(() => {
        showAdminToast('Could not connect to the server.');
        resendBtn.disabled = false;
        resendBtn.textContent = 'Resend Code';
    });
}

function adminCpStartResendCooldown(seconds) {
    const btn = document.getElementById('adminCpResendBtn');
    let countdown = seconds;
    btn.disabled = true;
    btn.textContent = `Resend in ${countdown}s`;
    const interval = setInterval(() => {
        countdown--;
        btn.textContent = `Resend in ${countdown}s`;
        if (countdown <= 0) {
            clearInterval(interval);
            btn.textContent = 'Resend Code';
            btn.disabled = false;
        }
    }, 1000);
}

// Step 2 → verify OTP
function adminVerifyCpOtp() {
    const inputs = document.querySelectorAll('.adminCpCodeInput');
    let code = '';
    inputs.forEach(i => code += i.value);
    const errorEl = document.getElementById('adminCpOtpError');

    if (code.length !== 6) {
        errorEl.textContent = 'Please enter all 6 digits.';
        errorEl.style.display = 'block';
        return;
    }

    const formData = new FormData();
    formData.append('code', code);

    fetch('../../backend/profile/verify_admin_change_password_otp.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            adminCpShowStep(3);
            document.getElementById('adminCpNewPassword').value = '';
            document.getElementById('adminCpConfirmPassword').value = '';
            document.getElementById('adminCpStep3Error').style.display = 'none';
            adminCpUpdateStrength('');
            adminCpCheckMatch();
        } else {
            errorEl.textContent = data.message || 'Invalid code.';
            errorEl.style.display = 'block';
        }
    })
    .catch(() => showAdminToast('Could not connect to the server.'));
}

// Step 3 → submit new password
function adminSubmitNewPassword() {
    const newPassword = document.getElementById('adminCpNewPassword').value;
    const confirmPassword = document.getElementById('adminCpConfirmPassword').value;
    const errorEl = document.getElementById('adminCpStep3Error');
    errorEl.style.display = 'none';

    if (
        newPassword.length < 8 ||
        !/[A-Z]/.test(newPassword) ||
        !/[a-z]/.test(newPassword) ||
        !/[0-9]/.test(newPassword)
    ) {
        errorEl.textContent = 'Password does not meet all requirements.';
        errorEl.style.display = 'block';
        return;
    }

    if (newPassword !== confirmPassword) {
        errorEl.textContent = 'Passwords do not match.';
        errorEl.style.display = 'block';
        return;
    }

    const formData = new FormData();
    formData.append('new_password', newPassword);
    formData.append('confirm_password', confirmPassword);

    fetch('../../backend/profile/admin_change_password.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            adminCpShowStep(4);
        } else {
            errorEl.textContent = data.message || 'Failed to change password.';
            errorEl.style.display = 'block';
        }
    })
    .catch(() => showAdminToast('Could not connect to the server.'));
}

// Password strength indicator
function adminCpUpdateStrength(pw) {
    const hasLength  = pw.length >= 8;
    const hasUpper = /[A-Z]/.test(pw);
    const hasLower = /[a-z]/.test(pw);
    const hasNumber  = /[0-9]/.test(pw);

    const req = {
        'admin-cp-req-length': hasLength,
        'admin-cp-req-upper': hasUpper,
        'admin-cp-req-lower': hasLower,
        'admin-cp-req-number': hasNumber
    };
    Object.entries(req).forEach(([id, met]) => {
        const el = document.getElementById(id);
        if (!el) return;
        const label = el.textContent.replace(/^[✓✗]\s/, '');
        el.textContent = (met ? '✓ ' : '✗ ') + label;
        el.style.color = met ? '#2ecc71' : '#aaa';
    });

    const score = [hasLength, hasUpper, hasLower, hasNumber].filter(Boolean).length;
    const bar = document.getElementById('adminCpStrengthBar');
    const text  = document.getElementById('adminCpStrengthText');
    if (!bar || !text) return;

    if (!pw) { bar.style.width = '0'; text.textContent = ''; return; }

    const levels = { 1: ['25%','#e74c3c','Weak'], 2: ['50%','#e67e22','Weak'], 3: ['75%','#f1c40f','Medium'], 4: ['100%','#2ecc71','Strong'] };
    const [width, color, label] = levels[score] || ['0', '#aaa', ''];
    bar.style.width = width;
    bar.style.background = color;
    text.textContent = label;
    text.style.color  = color;
}

// Password match text
function adminCpCheckMatch() {
    const pw  = document.getElementById('adminCpNewPassword')?.value  || '';
    const cpw = document.getElementById('adminCpConfirmPassword')?.value || '';
    const el  = document.getElementById('adminCpMatchText');
    if (!el) return;
    if (!cpw) { el.textContent = ''; return; }
    if (pw === cpw) { el.textContent = '✓ Passwords match'; el.style.color = '#2ecc71'; }
    else            { el.textContent = '✗ Passwords do not match'; el.style.color = '#e74c3c'; }
}

// Show/hide password toggle
function adminToggleCpVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}