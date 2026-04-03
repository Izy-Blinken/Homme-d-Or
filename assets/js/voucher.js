document.addEventListener('DOMContentLoaded', () => {

    const addVoucherModal = document.getElementById('add-voucher-modal');
    const addVoucherBtn = document.getElementById('add-voucher-btn');
    const addVoucherClose = document.getElementById('add-voucher-modal-close');
    const addVoucherCancel = document.getElementById('add-voucher-cancel');
    const voucherTypeSelect = document.getElementById('voucher-type-select');
    const limitGroup = document.getElementById('limit-group');

    addVoucherBtn.addEventListener('click', () => addVoucherModal.classList.add('show'));
    addVoucherClose.addEventListener('click', () => addVoucherModal.classList.remove('show'));
    addVoucherCancel.addEventListener('click', () => addVoucherModal.classList.remove('show'));

    // kapag broadcast, limit is editable. kapag individual, fixed to 1
    voucherTypeSelect.addEventListener('change', () => {

        const limitInput = limitGroup.querySelector('input');

        if (voucherTypeSelect.value === 'individual') {
            limitInput.value = 1;
            limitInput.readOnly = true;
        } else {
            limitInput.readOnly = false;
        }
        
    });

    // trigger on load
    voucherTypeSelect.dispatchEvent(new Event('change'));

});