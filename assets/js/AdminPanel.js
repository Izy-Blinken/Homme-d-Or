document.addEventListener('DOMContentLoaded', () => {


    const menuBtn  = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');
    const sidebar  = document.getElementById('admin-sidebar');
    const overlay  = document.getElementById('sidebar-overlay');

    function openSidebar()  {
        sidebar.classList.add('active');
        overlay.classList.add('show');
    }
    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('show');
    }

    if (menuBtn)  menuBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (overlay)  overlay.addEventListener('click', closeSidebar);


    //  PRODUCT MANAGEMENT PAGE
    const productModal     = document.getElementById('product-modal');
    const addProductBtn    = document.getElementById('add-product-btn');
    const modalCloseBtn    = document.getElementById('modal-close-btn');
    const modalCancelBtn   = document.getElementById('modal-cancel-btn');

    if (productModal) {
        if (addProductBtn)  addProductBtn.addEventListener('click',  () => productModal.classList.add('show'));
        if (modalCloseBtn)  modalCloseBtn.addEventListener('click',  () => productModal.classList.remove('show'));
        if (modalCancelBtn) modalCancelBtn.addEventListener('click', () => productModal.classList.remove('show'));
    }

    // Category tabs (Product Management)
    const tabBtns = document.querySelectorAll('.tab-btn');
    if (tabBtns.length > 0) {
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                tabBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    }


    // Edit Product Modal
    const editProductModal    = document.getElementById('edit-product-modal');
    const editModalCloseBtn   = document.getElementById('edit-modal-close-btn');
    const editModalCancelBtn  = document.getElementById('edit-modal-cancel-btn');

    if (editProductModal) {

        if (editModalCloseBtn)  editModalCloseBtn.addEventListener('click',  () => editProductModal.classList.remove('show'));
        if (editModalCancelBtn) editModalCancelBtn.addEventListener('click', () => editProductModal.classList.remove('show'));

        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {

                document.getElementById('edit-product-id').value       = btn.dataset.id;
                document.getElementById('edit-product-name').value     = btn.dataset.name;
                document.getElementById('edit-category-id').value      = btn.dataset.categoryId;
                document.getElementById('edit-price').value            = btn.dataset.price;
                document.getElementById('edit-discounted-price').value = btn.dataset.discountedPrice;
                document.getElementById('edit-stock-qty').value        = btn.dataset.stock;
                document.getElementById('edit-sku').value              = btn.dataset.sku;
                document.getElementById('edit-product-desc').value     = btn.dataset.desc;

                editProductModal.classList.add('show');
            });
        });
    }
    
    //Delete Product
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => {
            if (confirm('Are you sure you want to delete this product?')) {
                window.location.href = `../../backend/products/delete_product.php?id=${btn.dataset.id}`;
            }
        });
    });

    //  ORDER MANAGEMENT PAGE
    const orderModal     = document.getElementById('order-modal');
    const orderCloseBtn  = document.getElementById('modal-close-btn');
    const orderDoneBtn   = document.getElementById('modal-done-btn');

    if (orderModal) {
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const d = btn.dataset;
                document.getElementById('modal-order-id').textContent  = 'Order ' + d.id;
                document.getElementById('modal-customer').textContent  = d.customer;
                document.getElementById('modal-date').textContent      = d.date;
                document.getElementById('modal-total').textContent     = d.total;
                document.getElementById('modal-status').textContent    = d.status;
                document.getElementById('modal-items').textContent     = d.items;
                document.getElementById('modal-address').textContent   = d.address;
                document.getElementById('modal-status-select').value   = d.status;
                orderModal.classList.add('show');
            });
        });

        if (orderCloseBtn) orderCloseBtn.addEventListener('click', () => orderModal.classList.remove('show'));
        if (orderDoneBtn)  orderDoneBtn.addEventListener('click',  () => orderModal.classList.remove('show'));
    }


    //  SALES REPORT PAGE
    const reportBtns = document.querySelectorAll('.schedule-btn');

    if (reportBtns.length > 0) {
        const periodData = {
            daily: {
                sales: '$8,425',   orders: '127',   avg: '$66.34',
                sc: '+15.3% from previous period', oc: '+10 orders',  ac: '+5.2% increase'
            },
            weekly: {
                sales: '$52,180',  orders: '834',   avg: '$62.57',
                sc: '+9.1% from previous period',  oc: '+44 orders',  ac: '+3.8% increase'
            },
            monthly: {
                sales: '$214,300', orders: '3,204', avg: '$66.89',
                sc: '+12.4% from previous period', oc: '+210 orders', ac: '+4.1% increase'
            }
        };

        reportBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                reportBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const d = periodData[btn.dataset.period];
                if (!d) return;

                document.getElementById('val-total-sales').textContent        = d.sales;
                document.getElementById('val-orders').textContent             = d.orders;
                document.getElementById('val-avg').textContent                = d.avg;
                document.getElementById('val-total-sales-change').textContent = d.sc;
                document.getElementById('val-orders-change').textContent      = d.oc;
                document.getElementById('val-avg-change').textContent         = d.ac;
            });
        });
    }

    //  PROFILE PAGE
    const editModal       = document.getElementById('edit-modal');
    const editProfileBtn  = document.getElementById('edit-profile-btn');
    const editModalClose  = document.getElementById('edit-modal-close');
    const editModalCancel = document.getElementById('edit-modal-cancel');
    const logoutBtn       = document.getElementById('logout-btn');

    if (editModal) {
        if (editProfileBtn)  editProfileBtn.addEventListener('click',  () => editModal.classList.add('show'));
        if (editModalClose)  editModalClose.addEventListener('click',  () => editModal.classList.remove('show'));
        if (editModalCancel) editModalCancel.addEventListener('click', () => editModal.classList.remove('show'));
    }

    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'login.html';
            }
        });
    }

    //  FILTER RESET — Order, Product, Customer pages
    const resetBtn = document.querySelector('.reset-btn');

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            const selectIds = [
                'status-filter', 'date-filter',
                'stock-filter',  'discount-filter',
                'sort-filter',   'category-filter'
            ];
            selectIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });

            const searchIds = ['search-input', 'product-search', 'customer-search'];
            searchIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
        });
    }


});