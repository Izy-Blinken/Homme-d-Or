//get modal elements
const signupModal = document.getElementById('signupModal'); //const "variable_name" ay variable for getting the element na imamanipulate
const closeBtn = document.getElementsByClassName('close')[0]; //[0] means yung unang 'close' na naka-declare

//open signup modal (function to, same concept sa methods ng java)
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

//(Btw, nalalaman kung ano yung element na gagalawin base sa name nila)

// close kapag cinlick ung close button. Yung closeBtn name dito is yung variable na dineclare sa taas to get the close element sa html 
if (closeBtn) {
    closeBtn.onclick = closeSignupModal;
}

// close kapag yung modal(outside ng form--"window") yung cinlick
window.addEventListener('click', function(event) {
    if (event.target == signupModal) {
        closeSignupModal();
    }
});

// close kapag esc 
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeSignupModal();
    }
});