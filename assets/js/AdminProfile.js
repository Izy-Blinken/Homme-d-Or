// Modal open/close 
const editModal = document.getElementById('edit-modal');
const openBtn = document.getElementById('edit-profile-btn');
const closeBtn = document.getElementById('edit-modal-close');
const cancelBtn = document.getElementById('edit-modal-cancel');

openBtn.addEventListener('click', () => editModal.classList.add('show'));
closeBtn.addEventListener('click', () => editModal.classList.remove('show'));
cancelBtn.addEventListener('click', () => editModal.classList.remove('show'));

editModal.addEventListener('click', (e) => {
    if (e.target === editModal) editModal.classList.remove('show');
});

// Form submit
document.getElementById('edit-profile-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const saveBtn = this.querySelector('.btn-save');
    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving...';

    const formData = new FormData(this);

    try {
        const res  = await fetch('../../backend/profile/update_profile.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            showToast(data.message);
            editModal.classList.remove('show');

            // Reload to reflect updated brand name / logo
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message, true);
        }
    } catch (err) {
        showToast('Something went wrong. Please try again.', true);
    } finally {
        saveBtn.disabled = false;
        saveBtn.textContent = 'Save Changes';
    }
});

// Toast notif
function showToast(message, isError = false) {
    let toast = document.getElementById('generalToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'generalToast';
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.style.background = isError ? '#ef5350' : '#c9a961';
    toast.style.color = isError ? 'white'   : 'black';
    toast.style.display = 'block';

    clearTimeout(toast._timeout);
    toast._timeout = setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

// Logout
document.getElementById('logout-btn').addEventListener('click', () => {
    window.location.href = '../../backend/auth/logout.php';
});