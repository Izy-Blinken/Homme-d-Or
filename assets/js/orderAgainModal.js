'use strict';

document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkoutForm');
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Pause the instant submission
            
            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }
            
            const submitBtn = document.querySelector('.placeOrderBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i> Processing...';
            submitBtn.style.opacity = '0.8';
            submitBtn.style.pointerEvents = 'none';
            
            // Wait 1.5 seconds for effect, then securely submit the data
            setTimeout(() => {
                // Attach a generated order number to the form data
                const orderInput = document.createElement('input');
                orderInput.type = 'hidden';
                orderInput.name = 'orderNumber';
                orderInput.value = 'ORD' + Date.now().toString().slice(-6);
                this.appendChild(orderInput);
                
                // Submit the form to orderConfirmation.php
                this.submit();
            }, 1500);
        });
    }
});