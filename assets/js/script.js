function showGeneralToast(message, type='success') {
    const toast = document.getElementById('generalToast');
    toast.textContent = message;
    toast.className = `generalToast ${type}`;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000); 
}


function initLiveSearch(inputId, suggestionsId, backendPath) {
    const input = document.getElementById(inputId);
    const box   = document.getElementById(suggestionsId);

    if (!input || !box) return;

    input.addEventListener('input', async () => {
        const query = input.value.trim();

        if (query.length < 2) {
            box.innerHTML = '';
            box.style.display = 'none';
            return;
        }

        const res  = await fetch(`${backendPath}?q=${encodeURIComponent(query)}`);
        const data = await res.json();

        if (data.length === 0) {
            box.innerHTML = '<div class="suggestion-item">No results found.</div>';
        } else {
            box.innerHTML = data.map(p => `
                <div class="suggestion-item" onclick="selectSuggestion('${inputId}', '${p.product_name}', '${suggestionsId}')">
                    <span class="suggestion-name">${p.product_name}</span>
                    <span class="suggestion-price">₱${parseFloat(p.price).toLocaleString()}</span>
                </div>
            `).join('');
        }

        box.style.display = 'block';
    });

    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !box.contains(e.target)) {
            box.style.display = 'none';
        }
    });
}

function selectSuggestion(inputId, name, suggestionsId) {
    document.getElementById(inputId).value = name;
    document.getElementById(suggestionsId).style.display = 'none';
}