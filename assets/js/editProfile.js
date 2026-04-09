// Track if the user made changes in the modal
let hasChanges = false;

// Load profile data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadProfile();
});

function loadProfile() {
    fetch('../backend/user_profile/get_profile.php')
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            showToast('Failed to load profile.');
            return;
        }

        const user = data.user;

        // Fill in display info
        document.getElementById('displayName').textContent = user.fname + ' ' + user.lname;
        document.getElementById('displayBirthday').textContent = user.bday_display || '—';
        document.getElementById('displayContact').textContent = user.phone || '—';
        document.getElementById('displayEmail').textContent = user.email;

        // Set profile photo
        if (user.profile_photo) {
            const photoUrl = '../assets/images/profile_photos/' + user.profile_photo;
            document.getElementById('currentProfileImage').src = photoUrl;
            document.getElementById('modalProfileImage').src = photoUrl;
        }

        // Pre-fill the edit form
        document.getElementById('editName').value = user.fname;
        document.getElementById('editLname').value = user.lname;
        document.getElementById('editBirthday').value = user.bday || '';
        document.getElementById('editContact').value = user.phone || '';
        document.getElementById('editEmail').value = user.email;

        // Order counts
        document.getElementById('countProcessing').textContent = data.orders.processing + ' orders';
        document.getElementById('countReview').textContent = data.orders.to_review + ' orders';
        document.getElementById('countCompleted').textContent = data.orders.completed + ' orders';

        // Order history
        const historyList = document.getElementById('historyList');
        historyList.innerHTML = '';
        if (data.history.length === 0) {
            historyList.innerHTML = '<p>No orders yet.</p>';
        } else {
            data.history.forEach(function(item) {
                const date = new Date(item.created_at);
                const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                const price = '₱' + parseFloat(item.total_amount).toLocaleString('en-PH', { minimumFractionDigits: 2 });

                historyList.innerHTML += `
                <button class="historyItem" onclick="window.location.href='viewHistory.php'">
                    <div class="historyDate">${formattedDate}</div>
                    <div class="historyInfo">
                        <p class="historyProduct">${item.product_name}</p>
                        <p class="historyPrice">${price}</p>
                    </div>
                </button>`;
            });
        }

        // Wishlist
        const wishlistGrid = document.getElementById('wishlistGrid');
        wishlistGrid.innerHTML = '';
        if (data.wishlist.length === 0) {
            wishlistGrid.innerHTML = '<p>Your wishlist is empty.</p>';
        } else {
            data.wishlist.forEach(function(item) {
                const imgUrl = item.image_url
                    ? '../assets/images/products/' + item.image_url
                    : '../assets/images/products_images/image_unavailable.png';

                const displayPrice = item.discounted_price
                    ? '₱' + parseFloat(item.discounted_price).toLocaleString('en-PH', { minimumFractionDigits: 2 })
                    : '₱' + parseFloat(item.price).toLocaleString('en-PH', { minimumFractionDigits: 2 });

                wishlistGrid.innerHTML += `
                <button class="wishlistItem" onclick="window.location.href='viewWishlist.php'">
                    <div class="wishlistImage">
                        <img src="${imgUrl}" alt="${item.product_name}">
                    </div>
                    <p class="wishlistName">${item.product_name}</p>
                    <p class="wishlistPrice">${displayPrice}</p>
                </button>`;
            });
        }
    })
    .catch(function() {
        showToast('Could not connect to the server.');
    });
}


function openEditModal() {
    hasChanges = false;
    const modal = document.getElementById('editProfileModal');
    modal.classList.remove('closing');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    setTimeout(() => modal.classList.add('active'), 10);

}

function closeEditModal() {
    if (hasChanges) {
        document.getElementById('confirmationModal').style.display = 'flex';
    } else {
        const modal = document.getElementById('editProfileModal');
        modal.classList.remove('active');
        modal.classList.add('closing');
        setTimeout(() => {
            modal.style.display = 'none';
            modal.classList.remove('closing');
            document.body.style.overflow = 'auto';
        }, 300);
    }
}

function cancelDiscard() {
    document.getElementById('confirmationModal').style.display = 'none';
}


function confirmDiscard() {
    document.getElementById('confirmationModal').style.display = 'none';
    const modal = document.getElementById('editProfileModal');
    modal.classList.remove('active');
    modal.classList.add('closing');
    setTimeout(() => {
        modal.style.display = 'none';
        modal.classList.remove('closing');
        document.body.style.overflow = 'auto';
    }, 300);
    hasChanges = false;
}



// Detect any changes inside the form
document.addEventListener('input', function(e) {
    if (e.target.closest('#editProfileForm')) {
        hasChanges = true;
    }
});

function saveProfile(event) {
    event.preventDefault();

    const fname = document.getElementById('editName').value.trim();
    const lname = document.getElementById('editLname').value.trim();
    const bday = document.getElementById('editBirthday').value;
    const phone = document.getElementById('editContact').value.trim();
    const email = document.getElementById('editEmail').value.trim();

    if (!fname || !lname || !email) {
        showToast('First name, last name, and email are required.');
        return;
    }

    const formData = new FormData();
    formData.append('fname', fname);
    formData.append('lname', lname);
    formData.append('bday', bday);
    formData.append('phone', phone);
    formData.append('email', email);

    fetch('../backend/user_profile/update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            hasChanges = false;
            document.getElementById('editProfileModal').style.display = 'none';
            showToast('Profile updated successfully!');
            loadProfile();
        } else {
            showToast(data.message || 'Something went wrong.');
        }
    })
    .catch(function() {
        showToast('Could not connect to the server.');
    });
}

function uploadPhoto(input) {
    if (!input.files || !input.files[0]) return;

    const formData = new FormData();
    formData.append('photo', input.files[0]);

    // Show preview immediately
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('currentProfileImage').src = e.target.result;
        document.getElementById('modalProfileImage').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);

    fetch('../backend/user_profile/upload_photo.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Photo updated!');
        } else {
            showToast(data.message || 'Photo upload failed.');
        }
    })
    .catch(function() {
        showToast('Could not upload photo.');
    });
}

function removePhoto() {
    const defaultPhoto = '../assets/images/products_images/customerPic.png';
    document.getElementById('currentProfileImage').src = defaultPhoto;
    document.getElementById('modalProfileImage').src = defaultPhoto;
    showToast('Profile photo removed.');
}

function signOut() {
    window.location.href = '../backend/user_profile/logout.php';
}

function showToast(message) {
    const toast = document.getElementById('generalToast');
    toast.textContent = message;
    toast.style.display = 'block';
    toast.style.opacity = '1';

    setTimeout(function() {
        toast.style.opacity = '0';
        setTimeout(function() {
            toast.style.display = 'none';
        }, 500);
    }, 3000);
}

function openDeleteModal() {
    const modal = document.getElementById('deleteAccountModal');
    document.getElementById('deleteStep1').style.display = 'block';
    document.getElementById('deleteStep2').style.display = 'none';
    document.getElementById('deleteOtpInput').value = '';
    document.getElementById('deleteOtpError').style.display = 'none';
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('active'), 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteAccountModal');
    modal.classList.remove('active');
    modal.classList.add('closing');
    setTimeout(() => {
        modal.style.display = 'none';
        modal.classList.remove('closing');
    }, 300);
}
function sendDeleteOtp() {

    fetch('../backend/user_profile/send_delete_otp.php', {
        method: 'POST'
    })

    .then(res => res.json())
    .then(data => {

        if (data.success) {
            document.getElementById('deleteStep1').style.display = 'none';
            document.getElementById('deleteStep2').style.display = 'block';
            document.getElementById('deleteOtpInput').value = '';
            document.getElementById('deleteOtpError').style.display = 'none';
            showToast('Verification code sent to your email.');

        } else {
            showToast(data.message || 'Failed to send code. Please try again.');
        }
    })
    .catch(function() {
        showToast('Could not connect to the server.');
    });
}

function confirmDeleteAccount() {

    const code = document.getElementById('deleteOtpInput').value.trim();
    const errorEl = document.getElementById('deleteOtpError');

    if (code.length !== 6) {
        errorEl.textContent = 'Please enter the 6-digit code.';
        errorEl.style.display = 'block';
        return;
    }

    const formData = new FormData();
    formData.append('code', code);

    fetch('../backend/user_profile/verify_delete_account.php', {
        method: 'POST',
        body: formData
    })
    
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Account deleted, redirect to homepage
            window.location.href = '../pages/index.php';
        } else {
            errorEl.textContent = data.message || 'Invalid or expired code.';
            errorEl.style.display = 'block';
        }
    })
    .catch(function() {
        showToast('Could not connect to the server.');
    });
}

// ── Change Password Modal ─────────────────────────────

function openChangePasswordModal() {
    const modal = document.getElementById('changePasswordModal');
    document.getElementById('cpStep1').style.display = 'block';
    document.getElementById('cpStep2').style.display = 'none';
    document.getElementById('cpStep3').style.display = 'none';
    document.getElementById('cpStep4').style.display = 'none';
    document.getElementById('cpCurrentPassword').value = '';
    document.getElementById('cpStep1Error').style.display = 'none';
    
    document.querySelector('#changePasswordModal .modalHeader').style.display = 'block';
    document.getElementById('cpModalSubtitle').style.display = 'block';

    modal.classList.remove('closing');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    setTimeout(() => modal.classList.add('active'), 10);
}

function closeChangePasswordModal() {
    const modal = document.getElementById('changePasswordModal');
    modal.classList.remove('active');
    modal.classList.add('closing');
    setTimeout(() => {
        modal.style.display = 'none';
        modal.classList.remove('closing');
        document.body.style.overflow = 'auto';
    }, 300);
}

function sendChangePasswordOtp(isResend = false) {
    const currentPassword = document.getElementById('cpCurrentPassword').value.trim();
    const errorEl = document.getElementById('cpStep1Error');

    if (!isResend && !currentPassword) {
        errorEl.textContent = 'Please enter your current password.';
        errorEl.style.display = 'block';
        return;
    }

    const resendBtn = document.getElementById('cpResendBtn');
    if (isResend) {
        resendBtn.disabled = true;
        resendBtn.textContent = 'Sending...';
    }

    const formData = new FormData();
    if (!isResend) formData.append('current_password', currentPassword);

    fetch('../backend/user_profile/send_change_password_otp.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cpStep1').style.display = 'none';
            document.getElementById('cpStep2').style.display = 'block';
            document.querySelectorAll('.cpCodeInput').forEach(i => { i.value = ''; i.classList.remove('filled', 'error'); });
            document.getElementById('cpOtpError').style.display = 'none';
            showToast('Verification code sent to your email.');
            startCpResendCooldown(60);
        } else {
            if (isResend) {
                document.getElementById('cpOtpError').textContent = data.message || 'Failed to resend.';
                document.getElementById('cpOtpError').style.display = 'block';
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend Code';
            } else {
                errorEl.textContent = data.message || 'Failed to send code.';
                errorEl.style.display = 'block';
            }
        }
    })
    .catch(() => showToast('Could not connect to the server.'));
}

function startCpResendCooldown(seconds) {
    const btn = document.getElementById('cpResendBtn');
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

function verifyCpOtp() {
    const inputs = document.querySelectorAll('.cpCodeInput');
    let code = '';
    inputs.forEach(i => code += i.value);

    const errorEl = document.getElementById('cpOtpError');

    if (code.length !== 6) {
        inputs.forEach(i => i.classList.add('error'));
        setTimeout(() => inputs.forEach(i => i.classList.remove('error')), 500);
        errorEl.textContent = 'Please enter all 6 digits.';
        errorEl.style.display = 'block';
        return;
    }

    const formData = new FormData();
    formData.append('code', code);

    fetch('../backend/user_profile/verify_change_password_otp.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cpStep2').style.display = 'none';
            document.getElementById('cpStep3').style.display = 'block';
            document.getElementById('cpNewPassword').value = '';
            document.getElementById('cpConfirmPassword').value = '';
        } else {
            inputs.forEach(i => i.classList.add('error'));
            setTimeout(() => inputs.forEach(i => i.classList.remove('error')), 500);
            errorEl.textContent = data.message || 'Invalid code.';
            errorEl.style.display = 'block';
        }
    })
    .catch(() => showToast('Could not connect to the server.'));
}

function toggleCpVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function submitNewPassword() {
    const newPassword = document.getElementById('cpNewPassword').value;
    const confirmPassword = document.getElementById('cpConfirmPassword').value;
    const errorEl = document.getElementById('cpResetError');

    if (newPassword.length < 8 || !/[A-Z]/.test(newPassword) || !/[a-z]/.test(newPassword) || !/[0-9]/.test(newPassword)) {
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

    fetch('../backend/user_profile/change_password.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cpStep3').style.display = 'none';
            document.getElementById('cpStep4').style.display = 'block';
            document.querySelector('#changePasswordModal .modalHeader').style.display = 'none';
        
        } else {
            errorEl.textContent = data.message || 'Failed to change password.';
            errorEl.style.display = 'block';
        }
    })
    .catch(() => showToast('Could not connect to the server.'));
}

// Password strength for change password modal
document.addEventListener('DOMContentLoaded', function() {
    const cpNewPassword = document.getElementById('cpNewPassword');
    const cpConfirmPassword = document.getElementById('cpConfirmPassword');
    if (!cpNewPassword) return;

    cpNewPassword.addEventListener('input', function() {
        const pw = this.value;
        const hasLength = pw.length >= 8;
        const hasUppercase = /[A-Z]/.test(pw);
        const hasLowercase = /[a-z]/.test(pw);
        const hasNumber = /[0-9]/.test(pw);

        document.getElementById('cp-req-length').classList.toggle('met', hasLength);
        document.getElementById('cp-req-uppercase').classList.toggle('met', hasUppercase);
        document.getElementById('cp-req-lowercase').classList.toggle('met', hasLowercase);
        document.getElementById('cp-req-number').classList.toggle('met', hasNumber);

        const strength = [hasLength, hasUppercase, hasLowercase, hasNumber].filter(Boolean).length;
        const bar = document.getElementById('cpStrengthBarFill');
        const text = document.getElementById('cpStrengthText');
        bar.className = 'strengthBarFill';
        text.className = 'strengthText';

        if (strength <= 2) { bar.classList.add('weak'); text.classList.add('weak'); text.textContent = 'Weak'; }
        else if (strength === 3) { bar.classList.add('medium'); text.classList.add('medium'); text.textContent = 'Medium'; }
        else { bar.classList.add('strong'); text.classList.add('strong'); text.textContent = 'Strong'; }

        checkCpPasswordMatch();
    });

    cpConfirmPassword.addEventListener('input', checkCpPasswordMatch);
});

function checkCpPasswordMatch() {
    const pw = document.getElementById('cpNewPassword').value;
    const cpw = document.getElementById('cpConfirmPassword').value;
    const el = document.getElementById('cpPasswordMatch');
    if (!cpw) { el.textContent = ''; el.className = 'passwordMatch'; return; }
    if (pw === cpw) { el.textContent = 'Passwords match'; el.className = 'passwordMatch match'; }
    else { el.textContent = 'Passwords do not match'; el.className = 'passwordMatch no-match'; }
}

// OTP input behavior for change password
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.cpCodeInput');
    inputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1);
            if (this.value.length === 1) {
                this.classList.add('filled');
                if (index < inputs.length - 1) inputs[index + 1].focus();
            } else {
                this.classList.remove('filled');
            }
        });
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) inputs[index - 1].focus();
        });
    });
});