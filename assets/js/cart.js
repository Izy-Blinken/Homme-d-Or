function updateSummary() {
    let subtotal = 0;
    let hasItems = false;
    const checkboxes = document.querySelectorAll('.item-select');
    
    checkboxes.forEach(cb => {
        if (cb.checked) {
            subtotal += parseFloat(cb.getAttribute('data-price'));
            hasItems = true;
        }
    });

    const shipping = hasItems ? 150 : 0;
    const total = subtotal + shipping;

    // Format numbers with commas
    const formatter = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    document.getElementById('summary-subtotal').innerText = '₱' + formatter.format(subtotal);
    document.getElementById('summary-shipping').innerText = '₱' + formatter.format(shipping);
    document.getElementById('summary-total').innerText = '₱' + formatter.format(total);

    // Disable checkout button if nothing is selected
    const checkoutBtn = document.getElementById('checkout-btn');
    if (!hasItems) {
        checkoutBtn.style.opacity = '0.5';
        checkoutBtn.style.pointerEvents = 'none';
        checkoutBtn.innerText = 'Select items to checkout';
    } else {
        checkoutBtn.style.opacity = '1';
        checkoutBtn.style.pointerEvents = 'auto';
        checkoutBtn.innerText = 'Proceed to Checkout';
    }

    // Sync "Select All" checkbox state
    const selectAllCb = document.getElementById('selectAll');
    if (selectAllCb) {
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        selectAllCb.checked = allChecked && checkboxes.length > 0;
    }
}

function toggleSelectAll(masterCheckbox) {
    const checkboxes = document.querySelectorAll('.item-select');
    checkboxes.forEach(cb => {
        cb.checked = masterCheckbox.checked;
    });
    updateSummary();
}

// Run on initial load
document.addEventListener('DOMContentLoaded', updateSummary);