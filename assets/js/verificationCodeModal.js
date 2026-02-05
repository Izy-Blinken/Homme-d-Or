// ============================================
// FORGOT PASSWORD JAVASCRIPT
// ============================================
// This file handles 3 pages:
// 1. sendEmailCode.php - User enters email
// 2. verificationCode.php - User enters 6-digit code
// 3. changePassword.php - User creates new password
// ============================================


// ============================================
// STEP 1: SEND EMAIL CODE PAGE
// ============================================

// Check if we're on the send email page
// document.getElementById() finds an element by its ID
if (document.getElementById('emailForm')) {
    
    // Get the form element
    const emailForm = document.getElementById('emailForm');
    
    // Listen for when the form is submitted
    // addEventListener() watches for an action (like clicking submit)
    emailForm.addEventListener('submit', function(event) {
        
        // Stop the form from actually submitting to server
        // (since this is a static demo)
        event.preventDefault();
        
        // Get the email the user typed
        const email = document.getElementById('email').value;
        
        // Save the email in browser memory (localStorage)
        // localStorage keeps data even after closing browser
        localStorage.setItem('userEmail', email);
        
        // Redirect to verification page
        // window.location.href changes the page
        window.location.href = 'verificationCode.php';
    });
}


// ============================================
// STEP 2: VERIFICATION CODE PAGE
// ============================================

// Check if we're on the verification page
if (document.getElementById('verificationModal')) {
    
    // Get the modal element
    const modal = document.getElementById('verificationModal');
    
    // Get the email from localStorage (saved in step 1)
    const savedEmail = localStorage.getItem('userEmail');
    
    // Show the modal when page loads
    // classList.add() adds a CSS class to show the modal
    modal.classList.add('active');
    
    // Display the user's email in the modal
    if (savedEmail) {
        document.getElementById('userEmail').textContent = savedEmail;
    }
    
    
    // --- HANDLE CODE INPUT BOXES ---
    
    // Get all 6 code input boxes
    // querySelectorAll() finds ALL elements with a class
    const codeInputs = document.querySelectorAll('.codeInput');
    
    // Focus on first input when page loads
    codeInputs[0].focus();
    
    // Loop through each input box
    // forEach() runs code for each item in a list
    codeInputs.forEach((input, index) => {
        
        // Listen for when user types
        input.addEventListener('input', function(e) {
            
            // Get what the user typed
            const value = e.target.value;
            
            // Only allow numbers
            // Regular expression /[^0-9]/g finds anything that's NOT a number
            if (value && /[^0-9]/g.test(value)) {
                e.target.value = '';
                return;
            }
            
            // Add 'filled' class for styling
            if (value) {
                e.target.classList.add('filled');
            } else {
                e.target.classList.remove('filled');
            }
            
            // Auto-move to next input if user typed a number
            if (value && index < 5) {
                codeInputs[index + 1].focus();
            }
        });
        
        // Listen for backspace key
        input.addEventListener('keydown', function(e) {
            // e.key tells us which key was pressed
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                // Move to previous input
                codeInputs[index - 1].focus();
            }
        });
        
        // Prevent typing more than 1 character
        input.addEventListener('paste', function(e) {
            e.preventDefault(); // Stop paste action
        });
    });
    
    
    // --- HANDLE VERIFY BUTTON ---
    
    const verificationForm = document.getElementById('verificationForm');
    
    verificationForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Collect all 6 digits into one string
        let code = '';
        codeInputs.forEach(input => {
            code += input.value;
        });
        
        // Check if all 6 boxes are filled
        if (code.length === 6) {
            // In a real app, you'd verify the code with server
            // For static demo, we just accept any 6 digits
            
            // Save that verification was successful
            localStorage.setItem('codeVerified', 'true');
            
            // Go to change password page
            window.location.href = 'changePassword.php';
            
        } else {
            // Show error - shake the inputs
            codeInputs.forEach(input => {
                input.classList.add('error');
            });
            
            // Remove error class after animation
            setTimeout(function() {
                codeInputs.forEach(input => {
                    input.classList.remove('error');
                });
            }, 300);
        }
    });
    
    
    // --- HANDLE RESEND CODE BUTTON ---
    
    const resendButton = document.getElementById('resendButton');
    
    resendButton.addEventListener('click', function() {
        // Clear all inputs
        codeInputs.forEach(input => {
            input.value = '';
            input.classList.remove('filled');
        });
        
        // Focus first input
        codeInputs[0].focus();
        
        // In real app, you'd send new code to email
        alert('New verification code sent to ' + savedEmail);
    });
    
    
    // --- HANDLE CLOSE BUTTON ---
    
    const closeButton = document.getElementById('closeVerificationModal');
    
    closeButton.addEventListener('click', function() {
        // Add closing animation class
        modal.classList.add('closing');
        
        // Wait for animation to finish, then go back
        setTimeout(function() {
            window.location.href = 'sendEmailCode.php';
        }, 300); // 300ms = 0.3 seconds
    });
}


// ============================================
// STEP 3: CHANGE PASSWORD PAGE
// ============================================

// Check if we're on the change password page
if (document.getElementById('changePasswordModal')) {
    
    // Check if user actually verified code
    const codeVerified = localStorage.getItem('codeVerified');
    
    if (!codeVerified) {
        // If they didn't verify, send them back
        alert('Please verify your code first');
        window.location.href = 'sendEmailCode.php';
    }
    
    // Show the modal
    const modal = document.getElementById('changePasswordModal');
    modal.classList.add('active');
    
    
    // --- PASSWORD VISIBILITY TOGGLE ---
    
    // Get toggle buttons
    const toggleNewPassword = document.getElementById('toggleNewPassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    
    // Get password inputs
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    
    // Toggle new password visibility
    toggleNewPassword.addEventListener('click', function() {
        // Check current type
        if (newPasswordInput.type === 'password') {
            // Show password
            newPasswordInput.type = 'text';
            // Change icon to "eye-slash"
            this.querySelector('i').classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            // Hide password
            newPasswordInput.type = 'password';
            // Change icon back to "eye"
            this.querySelector('i').classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
    
    // Toggle confirm password visibility
    toggleConfirmPassword.addEventListener('click', function() {
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            this.querySelector('i').classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            confirmPasswordInput.type = 'password';
            this.querySelector('i').classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
    
    
    // --- PASSWORD STRENGTH CHECKER ---
    
    const strengthBarFill = document.getElementById('strengthBarFill');
    const strengthText = document.getElementById('strengthText');
    
    // Get requirement elements
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    
    // Check password as user types
    newPasswordInput.addEventListener('input', function() {
        const password = this.value;
        
        // Check each requirement
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password); // Has capital letter
        const hasLowercase = /[a-z]/.test(password); // Has lowercase letter
        const hasNumber = /[0-9]/.test(password); // Has number
        
        // Update requirement indicators
        // If requirement is met, add 'met' class (turns green)
        if (hasLength) {
            reqLength.classList.add('met');
        } else {
            reqLength.classList.remove('met');
        }
        
        if (hasUppercase) {
            reqUppercase.classList.add('met');
        } else {
            reqUppercase.classList.remove('met');
        }
        
        if (hasLowercase) {
            reqLowercase.classList.add('met');
        } else {
            reqLowercase.classList.remove('met');
        }
        
        if (hasNumber) {
            reqNumber.classList.add('met');
        } else {
            reqNumber.classList.remove('met');
        }
        
        // Calculate password strength
        // Count how many requirements are met
        const metRequirements = [hasLength, hasUppercase, hasLowercase, hasNumber].filter(Boolean).length;
        
        // Clear previous strength classes
        strengthBarFill.className = 'strengthBarFill';
        strengthText.className = 'strengthText';
        
        // Update strength indicator
        if (metRequirements === 0) {
            strengthText.textContent = '';
        } else if (metRequirements <= 2) {
            strengthBarFill.classList.add('weak');
            strengthText.classList.add('weak');
            strengthText.textContent = 'Weak';
        } else if (metRequirements === 3) {
            strengthBarFill.classList.add('medium');
            strengthText.classList.add('medium');
            strengthText.textContent = 'Medium';
        } else {
            strengthBarFill.classList.add('strong');
            strengthText.classList.add('strong');
            strengthText.textContent = 'Strong';
        }
        
        // Check if passwords match
        checkPasswordMatch();
    });
    
    
    // --- PASSWORD MATCH CHECKER ---
    
    const passwordMatch = document.getElementById('passwordMatch');
    
    function checkPasswordMatch() {
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        // Only show message if user started typing confirm password
        if (confirmPassword.length > 0) {
            if (newPassword === confirmPassword) {
                passwordMatch.textContent = '✓ Passwords match';
                passwordMatch.className = 'passwordMatch match';
            } else {
                passwordMatch.textContent = '✗ Passwords do not match';
                passwordMatch.className = 'passwordMatch no-match';
            }
        } else {
            passwordMatch.textContent = '';
            passwordMatch.className = 'passwordMatch';
        }
    }
    
    // Check match when user types in confirm field
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    
    
    // --- HANDLE FORM SUBMISSION ---
    
    const changePasswordForm = document.getElementById('changePasswordForm');
    
    changePasswordForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        // Validate all requirements
        const hasLength = newPassword.length >= 8;
        const hasUppercase = /[A-Z]/.test(newPassword);
        const hasLowercase = /[a-z]/.test(newPassword);
        const hasNumber = /[0-9]/.test(newPassword);
        
        // Check if all requirements are met
        if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber) {
            alert('Please meet all password requirements');
            return;
        }
        
        // Check if passwords match
        if (newPassword !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }
        
        // Everything is valid!
        // In real app, you'd save to database here
        
        // Clear saved data
        localStorage.removeItem('userEmail');
        localStorage.removeItem('codeVerified');
        
        // Close modal with animation
        modal.classList.add('closing');
        
        // Show success toast after modal closes
        setTimeout(function() {
            modal.classList.remove('active');
            showSuccessToast();
            
            // Redirect to login after 3 seconds
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 3000);
        }, 300);
    });
    
    
    // --- SUCCESS TOAST ---
    
    function showSuccessToast() {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'successToast';
        toast.innerHTML = '<i class="fas fa-check-circle"></i> <span>Password changed successfully!</span>';
        
        // Add to page
        document.body.appendChild(toast);
        
        // Show toast with animation
        setTimeout(function() {
            toast.style.display = 'flex';
            toast.style.opacity = '1';
        }, 100);
        
        // Hide toast after 3 seconds
        setTimeout(function() {
            toast.style.opacity = '0';
            setTimeout(function() {
                toast.remove(); // Remove from page
            }, 300);
        }, 3000);
    }
    
    
    // --- HANDLE CLOSE BUTTON ---
    
    const closeButton = document.getElementById('closeChangePasswordModal');
    
    closeButton.addEventListener('click', function() {
        if (confirm('Are you sure? Your progress will be lost.')) {
            modal.classList.add('closing');
            setTimeout(function() {
                window.location.href = 'sendEmailCode.php';
            }, 300);
        }
    });
}