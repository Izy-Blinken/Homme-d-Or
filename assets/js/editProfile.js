let hasChanges = false;

function openEditModal() {
    const modal = document.getElementById('editProfileModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    hasChanges = false;
    
    setTimeout(() => {
        modal.classList.add('active');
    }, 10);
    
    const inputs = modal.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            hasChanges = true;
        });
    });
}

function closeEditModal() {
    if (hasChanges) {
        document.getElementById('confirmationModal').classList.add('show');
    } else {
        forceCloseModal();
    }
}

function cancelDiscard() {
    document.getElementById('confirmationModal').classList.remove('show');
}

function confirmDiscard() {
    document.getElementById('confirmationModal').classList.remove('show');
    forceCloseModal();
    showGeneralToast('Changes discarded', 'info');

}

function forceCloseModal() {
    const modal = document.getElementById('editProfileModal');
    modal.classList.add('closing');
    modal.classList.remove('active');
    
    setTimeout(() => {
        modal.style.display = 'none';
        modal.classList.remove('closing');
        document.body.style.overflow = 'auto';
        hasChanges = false;
    }, 300);
}

function removePhoto() {
    document.getElementById('modalProfileImage').src = '../assets/images/default-profile.png';
    document.getElementById('profilePhotoInput').value = '';
    hasChanges = true;
}

function saveProfile(event) {
    event.preventDefault();
        
    forceCloseModal();
    showGeneralToast('Profile updated successfully!', 'success');
}


function showGeneralToast(message, type = 'success') {
    const toast = document.getElementById('generalToast');
    toast.textContent = message;
    toast.className = `generalToast ${type}`;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000); 
}