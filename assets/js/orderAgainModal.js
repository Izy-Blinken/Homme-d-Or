'use strict';

function showOrderSuccessModal(orderNumber) {
    console.log('Opening modal with order:', orderNumber);
    
    const modal = document.getElementById('orderSuccessModal');
    if (!modal) {
        console.error('Modal element not found!');
        return;
    }

    const orderNumberDisplay = document.getElementById('orderNumberDisplay');
    if (orderNumberDisplay) {
        orderNumberDisplay.textContent = orderNumber || '1';
    }

    modal.style.display = 'flex';
    modal.classList.remove('closing');
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        modal.classList.add('active');
    }, 10);
}

function closeOrderSuccessModal() {
    const modal = document.getElementById('orderSuccessModal');
    if (!modal) return;

    modal.classList.add('closing');
    modal.classList.remove('active');

    setTimeout(() => {
        modal.style.display = 'none';
        modal.classList.remove('closing');
        document.body.style.overflow = '';
    }, 300);
}

document.addEventListener('DOMContentLoaded', function() {
    
    const closeBtn = document.getElementById('closeOrderSuccess');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeOrderSuccessModal);
    }

    const modal = document.getElementById('orderSuccessModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeOrderSuccessModal();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('orderSuccessModal');
        if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
            closeOrderSuccessModal();
        }
    });

    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }
            
            const orderNumber = 'ORD' + Date.now().toString().slice(-6);
            showOrderSuccessModal(orderNumber);
            
            setTimeout(() => {
                this.reset();
            }, 1000);
        });
    }

    console.log('Order Success Modal initialized');
});