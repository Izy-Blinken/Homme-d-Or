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

                // Reset containers
                document.getElementById('edit-new-variants-container').innerHTML = '';
                document.getElementById('edit-new-image-preview').innerHTML = '';
                document.getElementById('edit-primary-image-index').value = '-1';

                // Load existing variants
                if (typeof loadExistingVariants === 'function') {
                    loadExistingVariants(btn.dataset.id);
                }

                // Load existing images
                if (typeof loadEditImages === 'function') {
                    loadEditImages(btn.dataset.id);
                }
            });
        });
    }
    
    //Delete Product
    const deleteModal      = document.getElementById('delete-modal');
    const deleteModalClose = document.getElementById('delete-modal-close');
    const deleteModalCancel = document.getElementById('delete-modal-cancel');
    const deleteConfirmBtn = document.getElementById('delete-confirm-btn');
    const deleteProductName = document.getElementById('delete-product-name');

    if (deleteModal) {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', () => {
                deleteProductName.textContent = btn.dataset.name;
                deleteConfirmBtn.href = `../../backend/products/delete_product.php?id=${btn.dataset.id}`;
                deleteModal.classList.add('show');
            });
        });

        deleteModalClose.addEventListener('click',  () => deleteModal.classList.remove('show'));
        deleteModalCancel.addEventListener('click', () => deleteModal.classList.remove('show'));
    }

    //  ORDER MANAGEMENT PAGE

    const orderModal    = document.getElementById('order-modal');
    const orderCloseBtn = document.getElementById('order-modal-close');
    const orderDoneBtn  = document.getElementById('order-modal-done');

    if (orderModal) {
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const d = btn.dataset;
                document.getElementById('modal-order-id').textContent = d.id;
                document.getElementById('modal-customer').textContent = d.customer;
                document.getElementById('modal-date').textContent     = d.date;
                document.getElementById('modal-total').textContent    = d.total;
                document.getElementById('modal-status').textContent   = d.status;
                document.getElementById('modal-payment').textContent  = d.payment;
                document.getElementById('modal-address').textContent  = d.address;
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

    //  FILTER RESET
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

document.addEventListener('DOMContentLoaded', () => {

    // Open/close category modal
    const catModal      = document.getElementById('category-modal');
    const manageCatBtn  = document.getElementById('manage-categories-btn');
    const catModalClose = document.getElementById('category-modal-close');

    if (manageCatBtn) manageCatBtn.addEventListener('click', () => catModal.classList.add('show'));
    if (catModalClose) catModalClose.addEventListener('click', () => catModal.classList.remove('show'));

    // Inline edit toggle
    document.querySelectorAll('.cat-edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            document.getElementById('cat-view-' + id).style.display = 'none';
            const form = document.getElementById('cat-edit-form-' + id);
            form.style.display = 'flex';
        });
    });

    document.querySelectorAll('.cat-edit-cancel').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            document.getElementById('cat-view-' + id).style.display = 'flex';
            document.getElementById('cat-edit-form-' + id).style.display = 'none';
        });
    });

    // Delete category
    const deleteCatModal   = document.getElementById('delete-category-modal');
    const catDeleteClose   = document.getElementById('cat-delete-modal-close');
    const catDeleteCancel  = document.getElementById('cat-delete-modal-cancel');
    const catDeleteConfirm = document.getElementById('cat-delete-confirm-btn');
    const catDeleteName    = document.getElementById('cat-delete-name');

    if (deleteCatModal) {
        document.querySelectorAll('.cat-delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                catDeleteName.textContent = btn.dataset.name;
                catDeleteConfirm.href = `../../backend/products/delete_category.php?id=${btn.dataset.id}`;
                document.getElementById('category-modal').classList.remove('show');
                deleteCatModal.classList.add('show');
            });
        });

        if (catDeleteClose)  catDeleteClose.addEventListener('click',  () => { deleteCatModal.classList.remove('show'); document.getElementById('category-modal').classList.add('show'); });
        if (catDeleteCancel) catDeleteCancel.addEventListener('click', () => { deleteCatModal.classList.remove('show'); document.getElementById('category-modal').classList.add('show'); });
    }

    // View Product
    const viewProductModal = document.getElementById('view-product-modal');
    const viewModalClose   = document.getElementById('view-modal-close');
    const viewModalDone    = document.getElementById('view-modal-done');

    if (viewProductModal) {
        document.querySelectorAll('.prod-view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const productId = btn.dataset.id;

                document.getElementById('view-loading').style.display = 'block';
                document.getElementById('view-content').style.display = 'none';
                viewProductModal.classList.add('show');

                fetch(`../../backend/products/get_product_details.php?id=${productId}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.error) {
                            alert('Error: ' + data.error);
                            viewProductModal.classList.remove('show');
                            return;
                        }

                        const p = data.product;

                        document.getElementById('view-name').textContent     = p.product_name;
                        document.getElementById('view-category').textContent = p.category_name || 'Uncategorized';
                        document.getElementById('view-price').textContent    = '₱' + parseFloat(p.price).toLocaleString('en-PH', { minimumFractionDigits: 2 });
                        document.getElementById('view-discounted-price').textContent = p.discounted_price
                            ? '₱' + parseFloat(p.discounted_price).toLocaleString('en-PH', { minimumFractionDigits: 2 })
                            : '—';
                        document.getElementById('view-stock').textContent    = p.stock_qty;
                        document.getElementById('view-sku').textContent      = p.sku;
                        document.getElementById('view-status').textContent   = p.product_status.replace(/-/g, ' ');
                        document.getElementById('view-desc').textContent     = p.product_desc || '—';

                        // Images
                        const primaryImg   = document.getElementById('view-primary-image');
                        const noImage      = document.getElementById('view-no-image');
                        const thumbnailBox = document.getElementById('view-thumbnails');
                        thumbnailBox.innerHTML = '';

                        const images = data.images;
                        if (images && images.length > 0) {
                            const primary = images.find(i => i.is_primary == 1) || images[0];
                            primaryImg.src           = `../../assets/images/products/${primary.image_url}`;
                            primaryImg.style.display = 'inline-block';
                            noImage.style.display    = 'none';

                            images.forEach(img => {
                                const thumb = document.createElement('img');
                                thumb.src   = `../../assets/images/products/${img.image_url}`;
                                thumb.alt   = '';
                                thumb.style.cssText = `
                                    width:52px; height:52px; object-fit:cover; cursor:pointer;
                                    border: 2px solid ${img.is_primary == 1 ? '#8B6914' : '#ddd'};
                                    border-radius:2px;
                                `;
                                thumb.addEventListener('click', () => {
                                    primaryImg.src = thumb.src;
                                    thumbnailBox.querySelectorAll('img').forEach(t => t.style.borderColor = '#ddd');
                                    thumb.style.borderColor = '#8B6914';
                                });
                                thumbnailBox.appendChild(thumb);
                            });
                        } else {
                            primaryImg.style.display = 'none';
                            noImage.style.display    = 'inline-flex';
                        }

                        // Variants
                        const variantsSection = document.getElementById('view-variants-section');
                        const variantsBody    = document.getElementById('view-variants-body');
                        variantsBody.innerHTML = '';

                        if (data.variants && data.variants.length > 0) {
                            variantsSection.style.display = 'block';
                            data.variants.forEach(v => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td style="padding:6px 10px; border:1px solid #eee;">${v.size_label}</td>
                                    <td style="padding:6px 10px; border:1px solid #eee;">₱${parseFloat(v.price).toLocaleString('en-PH', { minimumFractionDigits:2 })}</td>
                                    <td style="padding:6px 10px; border:1px solid #eee;">${v.stock_qty}</td>
                                    <td style="padding:6px 10px; border:1px solid #eee;">${v.sku}</td>
                                `;
                                variantsBody.appendChild(tr);
                            });
                        } else {
                            variantsSection.style.display = 'none';
                        }

                        document.getElementById('view-loading').style.display  = 'none';
                        document.getElementById('view-content').style.display  = 'block';
                    })
                    .catch(() => {
                        alert('Failed to load product details.');
                        viewProductModal.classList.remove('show');
                    });
            });
        });

        if (viewModalClose) viewModalClose.addEventListener('click', () => viewProductModal.classList.remove('show'));
        if (viewModalDone)  viewModalDone.addEventListener('click',  () => viewProductModal.classList.remove('show'));
    }

    // Add Product — multi-image preview, click to set primary
    const addImagesInput  = document.getElementById('add-product-images');
    const addImagePreview = document.getElementById('add-image-preview');
    const addPrimaryIndex = document.getElementById('add-primary-image-index');

    if (addImagesInput) {
        addImagesInput.addEventListener('change', () => {
            addImagePreview.innerHTML = '';
            addPrimaryIndex.value     = '0';

            const files = Array.from(addImagesInput.files).slice(0, 5);

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const wrapper = document.createElement('div');
                    wrapper.style.cssText = 'position:relative; display:inline-block; cursor:pointer;';

                    const img = document.createElement('img');
                    img.src           = e.target.result;
                    img.style.cssText = `
                        width:64px; height:64px; object-fit:cover;
                        border: 3px solid ${index === 0 ? '#8B6914' : '#ddd'};
                        border-radius:3px; display:block;
                    `;

                    const badge = document.createElement('span');
                    badge.textContent   = 'PRIMARY';
                    badge.style.cssText = `
                        position:absolute; bottom:2px; left:50%; transform:translateX(-50%);
                        background:#8B6914; color:#fff; font-size:0.6rem; padding:1px 4px;
                        white-space:nowrap; border-radius:2px;
                        display: ${index === 0 ? 'block' : 'none'};
                    `;

                    wrapper.appendChild(img);
                    wrapper.appendChild(badge);

                    wrapper.addEventListener('click', () => {
                        addPrimaryIndex.value = index;
                        addImagePreview.querySelectorAll('img').forEach(i => i.style.borderColor = '#ddd');
                        addImagePreview.querySelectorAll('span').forEach(s => s.style.display    = 'none');
                        img.style.borderColor = '#8B6914';
                        badge.style.display   = 'block';
                    });

                    addImagePreview.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // add variants
    const addVariantBtn = document.getElementById('add-variant-row-btn');
    let variantCount = 0;

    if (addVariantBtn) {
        addVariantBtn.addEventListener('click', () => {
            variantCount++;
            const row = document.createElement('div');
            row.className = 'variant-row';
            row.id = 'variant-row-' + variantCount;
            row.innerHTML = `
                <div class="variant-grid">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>SIZE</label>
                        <input type="text" name="variant_size[]" placeholder="e.g. 30ml">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>PRICE (₱)</label>
                        <input type="number" name="variant_price[]" step="0.01" placeholder="0.00">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>STOCK</label>
                        <input type="number" name="variant_stock[]" placeholder="0">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>SKU</label>
                        <input type="text" name="variant_sku[]" placeholder="e.g. GN-30ML">
                    </div>
                    <button type="button" class="btn-delete"
                            style="padding:5px 10px; font-size:0.78rem; align-self:flex-end;"
                            onclick="document.getElementById('variant-row-${variantCount}').remove()">✕</button>
                </div>
            `;
            document.getElementById('variants-container').appendChild(row);
        });
    }

    // edit variants
    const editAddVariantBtn = document.getElementById('edit-add-variant-row-btn');
    let editVariantCount = 0;

    if (editAddVariantBtn) {
        editAddVariantBtn.addEventListener('click', () => {
            editVariantCount++;
            const row = document.createElement('div');
            row.className = 'variant-row';
            row.id = 'edit-new-variant-row-' + editVariantCount;
            row.innerHTML = `
                <div class="variant-grid">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>SIZE</label>
                        <input type="text" name="variant_size[]" placeholder="e.g. 30ml">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>PRICE (₱)</label>
                        <input type="number" name="variant_price[]" step="0.01" placeholder="0.00">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>STOCK</label>
                        <input type="number" name="variant_stock[]" placeholder="0">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>SKU</label>
                        <input type="text" name="variant_sku[]" placeholder="e.g. GN-30ML">
                    </div>
                    <button type="button" class="btn-delete"
                            style="padding:5px 10px; font-size:0.78rem; align-self:flex-end;"
                            onclick="document.getElementById('edit-new-variant-row-${editVariantCount}').remove()">✕</button>
                </div>
            `;
            document.getElementById('edit-new-variants-container').appendChild(row);
        });
    }

});

// Load existing variants for edit modal
function loadExistingVariants(productId) {
    const container = document.getElementById('edit-existing-variants');
    if (!container) return;
    container.innerHTML = '<p style="font-size:0.82rem; color:#888;">Loading variants...</p>';

    fetch('../../backend/products/get_variants.php?product_id=' + productId)
        .then(r => r.json())
        .then(variants => {
            container.innerHTML = '';
            if (variants.length === 0) {
                container.innerHTML = '<p style="font-size:0.82rem; color:#888; margin-bottom:0.5rem;">No variants yet.</p>';
                return;
            }
            variants.forEach(v => {
                const row = document.createElement('div');
                row.className = 'variant-row';
                row.id = 'existing-variant-row-' + v.variant_id;
                row.innerHTML = `
                    <div class="variant-grid">
                        <input type="hidden" name="existing_variant_id[]" value="${v.variant_id}">
                        <div class="form-group" style="margin-bottom:0;">
                            <label>SIZE</label>
                            <input type="text" name="existing_variant_size[]" value="${v.size_label}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>PRICE (₱)</label>
                            <input type="number" name="existing_variant_price[]" value="${v.price}" step="0.01">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>STOCK</label>
                            <input type="number" name="existing_variant_stock[]" value="${v.stock_qty}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>SKU</label>
                            <input type="text" name="existing_variant_sku[]" value="${v.sku}">
                        </div>
                        <button type="button" class="btn-delete"
                                style="padding:5px 10px; font-size:0.78rem; align-self:flex-end;"
                                onclick="deleteVariant(${v.variant_id})">✕</button>
                    </div>
                `;
                container.appendChild(row);
            });
        });
}

// Load existing images for edit modal
function loadEditImages(productId) {
    const existingBox  = document.getElementById('edit-existing-images');
    const slotsHint    = document.getElementById('edit-image-slots-hint');
    const newInput     = document.getElementById('edit-product-images');
    const newPreview   = document.getElementById('edit-new-image-preview');
    const primaryIndex = document.getElementById('edit-primary-image-index');

    if (!existingBox) return;

    existingBox.innerHTML = '';
    if (newPreview) newPreview.innerHTML = '';
    if (primaryIndex) primaryIndex.value = '-1';

    fetch('../../backend/products/get_product_details.php?id=' + productId)
        .then(r => r.json())
        .then(data => {
            const images      = data.images || [];
            const existingCnt = images.length;
            const slotsLeft   = Math.max(0, 5 - existingCnt);

            if (slotsHint) slotsHint.textContent = `${existingCnt}/5 images — ${slotsLeft} slot(s) remaining`;

            images.forEach(img => {
                const wrapper = document.createElement('div');
                wrapper.style.cssText = 'position:relative; display:inline-block;';

                const el = document.createElement('img');
                el.src           = `../../assets/images/products/${img.image_url}`;
                el.style.cssText = `
                    width:64px; height:64px; object-fit:cover; cursor:pointer;
                    border: 3px solid ${img.is_primary == 1 ? '#8B6914' : '#ddd'};
                    border-radius:3px; display:block;
                `;

                const badge = document.createElement('span');
                badge.textContent   = 'PRIMARY';
                badge.style.cssText = `
                    position:absolute; bottom:18px; left:50%; transform:translateX(-50%);
                    background:#8B6914; color:#fff; font-size:0.6rem; padding:1px 4px;
                    white-space:nowrap; border-radius:2px;
                    display: ${img.is_primary == 1 ? 'block' : 'none'};
                `;

                // Delete button
                const delBtn = document.createElement('button');
                delBtn.type          = 'button';
                delBtn.textContent   = '✕';
                delBtn.style.cssText = `
                    position:absolute; top:-6px; right:-6px;
                    width:18px; height:18px; border-radius:50%;
                    background:#c00; color:#fff; border:none; cursor:pointer;
                    font-size:0.65rem; line-height:1; padding:0;
                    display:flex; align-items:center; justify-content:center;
                `;
                delBtn.addEventListener('click', () => {
                    if (!confirm('Remove this image?')) return;
                    const fd = new FormData();
                    fd.append('image_id',   img.image_id);
                    fd.append('product_id', productId);
                    fetch('../../backend/products/delete_product_image.php', { method:'POST', body:fd })
                        .then(r => r.json())
                        .then(res => {
                            if (res.success) loadEditImages(productId);
                            else alert(res.message || 'Failed to delete image.');
                        });
                });

                // Click to set primary
                el.addEventListener('click', () => {
                    const fd = new FormData();
                    fd.append('image_id',   img.image_id);
                    fd.append('product_id', productId);
                    fetch('../../backend/products/set_primary_image.php', { method:'POST', body:fd })
                        .then(r => r.json())
                        .then(res => {
                            if (res.success) loadEditImages(productId);
                        });
                });

                wrapper.appendChild(el);
                wrapper.appendChild(badge);
                wrapper.appendChild(delBtn);
                existingBox.appendChild(wrapper);
            });

            if (newInput) {
                newInput.style.display = slotsLeft > 0 ? 'block' : 'none';
                if (slotsLeft === 0 && slotsHint) slotsHint.textContent += ' — remove an image to add more.';
            }
        });
}

function deleteVariant(variantId) {
    if (!confirm('Delete this variant?')) return;
    fetch('../../backend/products/delete_variant.php?id=' + variantId)
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                document.getElementById('existing-variant-row-' + variantId).remove();
            }
        });
}