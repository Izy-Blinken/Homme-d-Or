// ==========================================
// 1. DROPDOWN MENU TOGGLE LOGIC
// ==========================================
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        // Close all other open menus first
        document.querySelectorAll('.filter-menu').forEach(menu => {
            if (menu !== this.nextElementSibling) menu.classList.remove('show');
        });
        this.nextElementSibling.classList.toggle('show');
    });
});

// Close menus when clicking anywhere else on the screen
document.addEventListener('click', function() {
    document.querySelectorAll('.filter-menu').forEach(menu => {
         menu.classList.remove('show');
    });
});

// ==========================================
// 2. SORTING LOGIC (Handles both pages)
// ==========================================
document.querySelectorAll('.filter-option').forEach(option => {
    option.addEventListener('click', function() {
        const filterType = this.textContent.trim();
        this.closest('.filter-menu').classList.remove('show');

        // Check if we are on the Wishlist page
        const wishlistContainer = document.querySelector('.tab-content.active');
        if (wishlistContainer) {
            const items = Array.from(wishlistContainer.querySelectorAll('.v-orders'));
            
            if (filterType === 'Alphabetical') {
                items.sort((a, b) => a.querySelector('.v-name').textContent.localeCompare(b.querySelector('.v-name').textContent));
            } else if (filterType === 'By Price') {
                items.sort((a, b) => {
                    const priceA = parseFloat(a.querySelector('.v-price').textContent.replace(/[^0-9.-]+/g,""));
                    const priceB = parseFloat(b.querySelector('.v-price').textContent.replace(/[^0-9.-]+/g,""));
                    return priceA - priceB;
                });
            }
            items.forEach(item => wishlistContainer.appendChild(item));
        }

        // Check if we are on the History page
        const historyContainer = document.querySelector('.history-table');
        if (historyContainer) {
            // Get all rows EXCEPT the header row
            const rows = Array.from(historyContainer.querySelectorAll('.history-row:not(.history-head)'));
            
            if (filterType === 'Alphabetical') {
                rows.sort((a, b) => {
                    const nameA = a.querySelector('.product-col').textContent.trim().toLowerCase();
                    const nameB = b.querySelector('.product-col').textContent.trim().toLowerCase();
                    return nameA.localeCompare(nameB);
                });
            } else if (filterType === 'Purchase Date') {
                rows.sort((a, b) => {
                    const dateA = a.querySelector('span:nth-child(2)').childNodes[0].textContent.trim();
                    const dateB = b.querySelector('span:nth-child(2)').childNodes[0].textContent.trim();
                    return dateB.localeCompare(dateA); 
                });
            } else if (filterType === 'By Price') {
                // NEW LOGIC: Sort by Price
                rows.sort((a, b) => {
                    // Grabs the Subtotal from the 6th column, strips the '₱' and commas, and converts to a mathable number
                    const priceA = parseFloat(a.querySelector('span:nth-child(6)').textContent.replace(/[^0-9.-]+/g,""));
                    const priceB = parseFloat(b.querySelector('span:nth-child(6)').textContent.replace(/[^0-9.-]+/g,""));
                    return priceA - priceB; // Sorts from Lowest to Highest
                });
            }
            
            // Re-append rows to visually reorder them
            rows.forEach(row => historyContainer.appendChild(row));
        }
    });
});

// ==========================================
// 3. SEARCH LOGIC (History Page Only)
// ==========================================
const searchInput = document.querySelector('.history-controls input');
const searchBtn = document.querySelector('.search-btn');

function performSearch() {
    if (!searchInput) return; // Exit if not on history page
    
    const searchTerm = searchInput.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.history-row:not(.history-head)');

    rows.forEach(row => {
        const productName = row.querySelector('.product-col').textContent.toLowerCase();
        // Hide row if it doesn't match search term, otherwise show it
        if (productName.includes(searchTerm)) {
            row.style.display = 'grid'; // Maintain grid layout
        } else {
            row.style.display = 'none';
        }
    });
}

// Trigger search on button click
if (searchBtn) {
    searchBtn.addEventListener('click', performSearch);
}

// Optional: Trigger search as user types (live search)
if (searchInput) {
    searchInput.addEventListener('keyup', performSearch);
}