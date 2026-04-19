function showGeneralToast(message, type='success') {
    const toast = document.getElementById('generalToast');
    toast.textContent = message;
    toast.className = `generalToast ${type}`;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

function ucfirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function initLiveSearch(inputId, suggestionsId, backendPath) {
    const input = document.getElementById(inputId);
    const box = document.getElementById(suggestionsId);

    if (!input || !box) return;

    input.addEventListener('input', async () => {
        const query = input.value.trim();

        if (query.length < 1) {
            box.innerHTML = '';
            box.style.display = 'none';
            return;
        }

        const res = await fetch(`${backendPath}?q=${encodeURIComponent(query)}`);
        const data = await res.json();

        if (data.length === 0) {
            box.innerHTML = '<div class="suggestion-item">No results found.</div>';
        } else {
            box.innerHTML = data.map(p => {
                // Products
                if (p.product_name) {
                    const sub = p.discounted_price
                        ? `₱${parseFloat(p.discounted_price).toLocaleString()}`
                        : `₱${parseFloat(p.price).toLocaleString()}`;
                    return `
                        <div class="suggestion-item" onclick="selectSuggestion('${inputId}', '${p.product_name.replace(/'/g, "\\'")}', '${suggestionsId}', true)">
                            <span class="suggestion-name">${p.product_name}</span>
                            <span class="suggestion-price">${sub}</span>
                        </div>
                    `;
                }
                // Orders
                const label = `${p.fname} ${p.lname}`;
                const sub = ucfirst(p.order_status ?? '');
                return `
                    <div class="suggestion-item" onclick="selectSuggestion('${inputId}', '${label.replace(/'/g, "\\'")}', '${suggestionsId}', true)">
                        <span class="suggestion-name">Order #${p.order_id} — ${label}</span>
                        <span class="suggestion-price">${sub}</span>
                    </div>
                `;
            }).join('');
        }

        box.style.display = 'block';
    });

    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !box.contains(e.target)) {
            box.style.display = 'none';
        }
    });
}

function selectSuggestion(inputId, value, suggestionsId, autoSubmit = false) {
    const input = document.getElementById(inputId);
    input.value = value;
    document.getElementById(suggestionsId).style.display = 'none';
    if (autoSubmit) {
        input.closest('form').submit();
    }
}

// admin navbar live search
// admin navbar live search
(function () {
    const input = document.getElementById('navbar-search-input');
    const results = document.getElementById('navbar-search-results');
    let debounceTimer;

    if (!input || !results) return;

    const SEARCH_URL = '../../backend/navbarLiveSearch.php';

    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = this.value.trim();

        if (q.length < 1) {
            results.style.display = 'none';
            results.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => fetchResults(q), 250);
    });

    function fetchResults(q) {
        fetch(SEARCH_URL + '?q=' + encodeURIComponent(q))
            .then(res => res.json())
            .then(data => renderResults(data, q))
            .catch(() => {
                results.innerHTML = '<div class="search-no-results">Search error. Try again.</div>';
                results.style.display = 'block';
            });
    }

    function highlight(text, q) {
        const escaped = q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return text.replace(new RegExp('(' + escaped + ')', 'gi'),
            '<mark style="background:#fff3cd;padding:0;border-radius:2px;">$1</mark>');
    }

    function renderResults(data, q) {
        const customers = data.customers || [];
        const products = data.products || [];

        if (customers.length === 0 && products.length === 0) {
            results.innerHTML = '<div class="search-no-results">No results found for "<strong>' +
                escapeHtml(q) + '</strong>"</div>';
            results.style.display = 'block';
            return;
        }

        let html = '';

        if (customers.length > 0) {
            html += '<div class="search-section-label">Customers</div>';
            customers.forEach(c => {
                const fullName = escapeHtml(c.fname + ' ' + c.lname);
                const email = escapeHtml(c.email);
                html += `
                <div class="search-result-item" onclick="goTo('customers', ${c.user_id})">
                    <span class="result-name">${highlight(fullName, q)}</span>
                    <span class="result-sub">${highlight(email, q)}</span>
                </div>`;
            });
        }

        if (customers.length > 0 && products.length > 0) {
            html += '<hr class="search-divider">';
        }

        if (products.length > 0) {
            html += '<div class="search-section-label">Products</div>';
            products.forEach(p => {
                const name = escapeHtml(p.product_name);
                const price = p.discounted_price
                    ? '₱' + parseFloat(p.discounted_price).toFixed(2)
                    : '₱' + parseFloat(p.price).toFixed(2);
                const status = escapeHtml(p.product_status);
                html += `
                <div class="search-result-item" onclick="goTo('products', ${p.product_id})">
                    <span class="result-name">${highlight(name, q)}</span>
                    <span class="result-sub">${price} &bull; ${status}</span>
                </div>`;
            });
        }

        results.innerHTML = html;
        results.style.display = 'block';
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !results.contains(e.target)) {
            results.style.display = 'none';
        }
    });

    // Reopen if input is focused and has value
    input.addEventListener('focus', function () {
        if (this.value.trim().length > 0 && results.innerHTML !== '') {
            results.style.display = 'block';
        }
    });

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    window.goTo = function (type, id) {
        results.style.display = 'none';
        input.value = '';
        if (type === 'customers') {
            window.location.href = '../../pages/Admin Pages/customerList.php?id=' + id;
        } else if (type === 'products') {
            window.location.href = '../../pages/Admin Pages/productManagement.php?id=' + id;
        }
    };
})();

// Customer navbar live search - Desktop & Mobile
(function () {
    // Desktop search
    const desktopSearch = document.getElementById('desktop-search');
    const desktopSuggestions = document.getElementById('desktop-suggestions');
    
    if (desktopSearch && desktopSuggestions) {
        initLiveSearch('desktop-search', 'desktop-suggestions', '../backend/productLiveSearch.php');
    }

    // Mobile search
    const mobileSearch = document.getElementById('mobile-search');
    const mobileSuggestions = document.getElementById('mobile-suggestions');
    
    if (mobileSearch && mobileSuggestions) {
        initLiveSearch('mobile-search', 'mobile-suggestions', '../backend/productLiveSearch.php');
    }
})();

function addToCart(productId, btn) {
    const original = btn.innerText;
    btn.innerText = 'Adding...';
    btn.disabled = true;

    fetch('../backend/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${productId}&quantity=1`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            btn.innerText = 'Added!';
            setTimeout(() => {
                btn.innerText = original;
                btn.disabled = false;
            }, 1500);
        } else if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            btn.innerText = original;
            btn.disabled = false;
            alert(data.message || 'Failed to add to cart.');
        }
    })
    .catch(() => {
        btn.innerText = original;
        btn.disabled = false;
        alert('Something went wrong. Please try again.');
    });
}