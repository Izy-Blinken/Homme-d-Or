//get modal elements
const signupModal = document.getElementById('signupModal'); //const "variable_name" ay variable for getting the element na imamanipulate
const closeBtn = document.getElementsByClassName('close')[0]; //[0] means yung unang 'close' na naka-declare

//open signup modal (function to, same concept sa methods ng java)
function openSignupModal() {
    signupModal.style.display = 'block'; // change "display: none" to "display: block" ; kumbaga from hidden to visible
    document.body.style.overflow = 'hidden'; // nagllock sa background para hindi magscroll
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