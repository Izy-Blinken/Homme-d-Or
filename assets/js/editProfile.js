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
                    ? '../assets/images/products_images/' + item.image_url
                    : '../assets/images/products_images/nocturne.png';

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
    document.getElementById('editProfileModal').style.display = 'flex';

    // Clear password fields every time
    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
}

function closeEditModal() {
    if (hasChanges) {
        document.getElementById('confirmationModal').style.display = 'flex';
    } else {
        document.getElementById('editProfileModal').style.display = 'none';
    }
}

function cancelDiscard() {
    document.getElementById('confirmationModal').style.display = 'none';
}

function confirmDiscard() {
    document.getElementById('confirmationModal').style.display = 'none';
    document.getElementById('editProfileModal').style.display = 'none';
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
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

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
    formData.append('current_password', currentPassword);
    formData.append('new_password', newPassword);
    formData.append('confirm_password', confirmPassword);

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