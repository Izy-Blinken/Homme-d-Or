//get modal elements
const signupModal = document.getElementById('signupModal'); //const "variable_name" ay variable for getting the element na imamanipulate
const closeBtn = document.getElementsByClassName('close')[0];

//open signup modal
function openSignupModal() {
    signupModal.style.display = 'block'; // change "display: none" to "display: block" ; kumbaga 
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

// Close signup modal
function closeSignupModal() {
    signupModal.style.display = 'none';
    document.body.style.overflow = 'auto'; // Re-enable scrolling
}

// Close when clicking X button
if (closeBtn) {
    closeBtn.onclick = closeSignupModal;
}

// Close when clicking outside modal
window.onclick = function(event) {
    if (event.target == signupModal) {
        closeSignupModal();
    }
}

// Close when pressing ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeSignupModal();
    }
});