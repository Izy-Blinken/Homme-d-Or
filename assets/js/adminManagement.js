document.addEventListener('DOMContentLoaded', () => {

    // ─── Assign Admin Modal ────────────────────────────────────────────────────

    const assignAdminModal      = document.getElementById('assign-admin-modal');
    const openAssignAdminBtn    = document.getElementById('open-assign-admin-btn');
    const assignAdminClose      = document.getElementById('assign-admin-modal-close');
    const assignAdminCancel     = document.getElementById('assign-admin-cancel');
    const assignAdminSubmit     = document.getElementById('assign-admin-submit');
    const assignUserIdInput     = document.getElementById('assign-user-id');
    const customerSearchInput   = document.getElementById('customer-search-input');
    const customerSearchResults = document.getElementById('customer-search-results');
    const selectedDisplay       = document.getElementById('selected-customer-display');

    function openAssignModal() {
        // reset state
        customerSearchInput.value      = '';
        customerSearchResults.style.display = 'none';
        customerSearchResults.innerHTML = '';
        selectedDisplay.style.display  = 'none';
        selectedDisplay.textContent    = '';
        assignUserIdInput.value        = '';
        assignAdminModal.classList.add('show');
    }

    function closeAssignModal() {
        assignAdminModal.classList.remove('show');
    }

    if (openAssignAdminBtn) openAssignAdminBtn.addEventListener('click', openAssignModal);
    if (assignAdminClose)   assignAdminClose.addEventListener('click', closeAssignModal);
    if (assignAdminCancel)  assignAdminCancel.addEventListener('click', closeAssignModal);

    // Prevent submit if no customer selected
    if (assignAdminSubmit) {
        assignAdminSubmit.addEventListener('click', (e) => {
            if (!assignUserIdInput.value) {
                e.preventDefault();
                showGeneralToast('Please select a customer first.', 'error');
            }
        });
    }

    // ─── Live customer search (debounced AJAX) ─────────────────────────────────

    let searchDebounceTimer = null;

    if (customerSearchInput) {
        customerSearchInput.addEventListener('input', () => {
            clearTimeout(searchDebounceTimer);
            const q = customerSearchInput.value.trim();

            if (q.length < 2) {
                customerSearchResults.style.display = 'none';
                customerSearchResults.innerHTML = '';
                return;
            }

            searchDebounceTimer = setTimeout(() => {
                fetchCustomers(q);
            }, 280);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!customerSearchInput.contains(e.target) && !customerSearchResults.contains(e.target)) {
                customerSearchResults.style.display = 'none';
            }
        });
    }

    function fetchCustomers(q) {
        customerSearchResults.innerHTML =
            '<div style="padding:0.75rem; color:#aaa; font-size:0.85rem;">Searching…</div>';
        customerSearchResults.style.display = 'block';

        fetch(`../../backend/customers/search_customers.php?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    customerSearchResults.innerHTML =
                        '<div style="padding:0.75rem; color:#aaa; font-size:0.85rem;">No customers found.</div>';
                    return;
                }
                customerSearchResults.innerHTML = data.map(u => `
                    <div class="customer-result-item"
                         data-id="${u.user_id}"
                         data-name="${escapeHtml(u.fname + ' ' + u.lname)}"
                         style="padding:0.7rem 1rem; cursor:pointer; border-bottom:1px solid rgba(255,255,255,0.05);
                                font-size:0.88rem; color:#f0e8d5; transition:background 0.2s;">
                        <span style="font-weight:600;">${escapeHtml(u.fname + ' ' + u.lname)}</span>
                        <span style="color:#aaa; margin-left:0.5rem; font-size:0.8rem;">${escapeHtml(u.email)}</span>
                    </div>
                `).join('');

                customerSearchResults.querySelectorAll('.customer-result-item').forEach(item => {
                    item.addEventListener('mouseenter', () => item.style.background = 'rgba(201,169,97,0.12)');
                    item.addEventListener('mouseleave', () => item.style.background = 'transparent');
                    item.addEventListener('click', () => selectCustomer(item.dataset.id, item.dataset.name));
                });
            })
            .catch(() => {
                customerSearchResults.innerHTML =
                    '<div style="padding:0.75rem; color:#ff6b6b; font-size:0.85rem;">Error fetching customers.</div>';
            });
    }

    function selectCustomer(id, name) {
        assignUserIdInput.value      = id;
        customerSearchInput.value    = name;
        selectedDisplay.textContent  = '✓ Selected: ' + name;
        selectedDisplay.style.display = 'block';
        customerSearchResults.style.display = 'none';
    }


    // ─── View / Edit Permissions Modal ────────────────────────────────────────

    const permissionsModal       = document.getElementById('permissions-modal');
    const permissionsModalClose  = document.getElementById('permissions-modal-close');
    const permissionsModalCancel = document.getElementById('permissions-modal-cancel');

    if (permissionsModal) {
        document.querySelectorAll('.view-permissions-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('permissions-modal-name').textContent = btn.dataset.name;
                document.getElementById('permissions-admin-id').value         = btn.dataset.adminId;

                const perms = JSON.parse(btn.dataset.perms);
                permissionsModal.querySelectorAll('.perm-checkbox').forEach(cb => {
                    cb.checked = perms[cb.dataset.perm] == 1;
                });

                permissionsModal.classList.add('show');
            });
        });

        permissionsModalClose.addEventListener('click',  () => permissionsModal.classList.remove('show'));
        permissionsModalCancel.addEventListener('click', () => permissionsModal.classList.remove('show'));
    }


    // ─── Close modals when clicking backdrop ──────────────────────────────────

    [assignAdminModal, permissionsModal].forEach(modal => {
        if (!modal) return;
        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.remove('show');
        });
    });

    // Confirm modal backdrop
    const confirmModal = document.getElementById('confirm-action-modal');
    if (confirmModal) {
        confirmModal.addEventListener('click', e => {
            if (e.target === confirmModal) confirmModal.style.display = 'none';
        });
    }


    // ─── Filter bar live search suggestions ───────────────────────────────────

    if (typeof initLiveSearch === 'function') {
        initLiveSearch('admin-search-input', 'admin-search-suggestions', '../../backend/ordersLiveSearch.php');
    }

});


// ─── Confirm Modal (global, matches customerList.js pattern) ──────────────────

function openConfirmModal(userId, name, action, button) {
    const modal     = document.getElementById('confirm-action-modal');
    const body      = document.getElementById('confirm-body');
    const yesBtn    = document.getElementById('confirm-yes');
    const cancelBtn = document.getElementById('confirm-cancel');

    if (action === 'remove-admin') {
        body.textContent = `Remove admin access from ${name}?`;
    } else {
        body.textContent = `Are you sure you want to ${action.replace('-', ' ')} for ${name}?`;
    }

    modal.style.display = 'flex';

    yesBtn.onclick = () => {
        const form = button.closest('form');
        if (form) form.submit();
        modal.style.display = 'none';
    };

    cancelBtn.onclick = () => { modal.style.display = 'none'; };
    document.getElementById('confirm-close').onclick = () => { modal.style.display = 'none'; };
}


// ─── Utility ──────────────────────────────────────────────────────────────────

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}