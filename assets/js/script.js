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
    const box   = document.getElementById(suggestionsId);

    if (!input || !box) return;

    input.addEventListener('input', async () => {
        const query = input.value.trim();

        if (query.length < 1) {
            box.innerHTML = '';
            box.style.display = 'none';
            return;
        }

        const res  = await fetch(`${backendPath}?q=${encodeURIComponent(query)}`);
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
                const sub   = ucfirst(p.order_status ?? '');
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