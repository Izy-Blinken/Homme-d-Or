// Auto-Load Tab from URL
document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  const tabId = urlParams.get('tab');
  
  if (tabId) {
    // Small delay ensures the DOM is fully loaded before switching
    setTimeout(() => {
      showPage(tabId);
    }, 100);
  }
});

// Tab switching (Upgraded)
function showPage(pageId) {
  const pages = document.querySelectorAll('.page');
  pages.forEach(page => page.classList.remove('active'));
  
  const tabs = document.querySelectorAll('.tab');
  tabs.forEach(tab => tab.classList.remove('active'));
  
  const targetPage = document.getElementById(pageId);
  if (targetPage) {
    targetPage.classList.add('active');
  }
  
  // Smart auto-detect: Finds the correct tab button based on the pageId
  const targetTab = Array.from(tabs).find(tab => tab.getAttribute('onclick').includes(pageId));
  if (targetTab) {
    targetTab.classList.add('active');
  }
  
  const navbar = document.querySelector('.navbar');
  const tabsBar = document.querySelector('.tabs');
  
  if (navbar && tabsBar) {
    const scrollTop = navbar.offsetHeight + tabsBar.offsetHeight;
    window.scrollTo({ top: scrollTop, behavior: 'smooth' });
  }
}

// Set countdown end time (8 hours, 34 minutes, 22 seconds from now)
let countdownEnd = Date.now() + (8 * 3600 + 34 * 60 + 22) * 1000;

function updateCountdown() {
  const now = Date.now();
  const diff = countdownEnd - now;
  
  if (diff < 0) {
    countdownEnd = Date.now() + 24 * 3600 * 1000;
    return;
  }
  
  const hours = Math.floor(diff / 3600000);
  const minutes = Math.floor((diff % 3600000) / 60000);
  const seconds = Math.floor((diff % 60000) / 1000);
  
  const pad = (num) => String(num).padStart(2, '0');
  
  const hoursEl = document.getElementById('hours');
  const minsEl = document.getElementById('mins');
  const secsEl = document.getElementById('secs');
  
  if (hoursEl) hoursEl.textContent = pad(hours);
  if (minsEl) minsEl.textContent = pad(minutes);
  if (secsEl) secsEl.textContent = pad(seconds);
}

setInterval(updateCountdown, 1000);
updateCountdown();

function animateCardsOnScroll() {
  const cards = document.querySelectorAll('.product-card');
  
  cards.forEach((card, index) => {
    const rect = card.getBoundingClientRect();
    const isVisible = rect.top < window.innerHeight - 50;
    
    if (isVisible && !card.classList.contains('animated')) {
      setTimeout(() => {
        card.style.animation = `cardIn 0.6s ease both`;
        card.classList.add('animated');
      }, index * 50);
    }
  });
}

window.addEventListener('scroll', animateCardsOnScroll);
window.addEventListener('DOMContentLoaded', animateCardsOnScroll);

// Search & Filter Logic (Applied to ALL pages)
document.addEventListener("DOMContentLoaded", () => {
    // Find all shop pages
    const pages = document.querySelectorAll('.page');

    pages.forEach(page => {
        const searchInput = page.querySelector('.search-input');
        const suggestionsBox = page.querySelector('.search-suggestions');
        const filterChips = page.querySelectorAll('.filter-chip');

        // Target all items that can be filtered (Grid cards AND Top Picks ranking items)
        const filterableItems = page.querySelectorAll('.product-card, .rank-item, .rank-featured');

        // Skip if this page doesn't have a search bar setup yet
        if (!searchInput || filterableItems.length === 0) return;

        let currentFilter = 'all';

        // 1. Category Filtering
        filterChips.forEach(chip => {
            chip.addEventListener('click', () => {
                filterChips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                currentFilter = chip.getAttribute('data-filter');
                applyFilters();
            });
        });

        // 2. Search & Live Suggestions
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase().trim();
            
            if (searchTerm.length > 0) {
                updateSuggestions(searchTerm);
            } else {
                suggestionsBox.classList.remove('active');
            }
            applyFilters();
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-container')) {
                suggestionsBox.classList.remove('active');
            }
        });

        // Core Filtering Logic
        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase().trim();

            filterableItems.forEach(item => {
                const category = item.getAttribute('data-category') || '';
                const name = (item.getAttribute('data-name') || '').toLowerCase();

                const matchesCategory = (currentFilter === 'all' || category === currentFilter);
                const matchesSearch = name.includes(searchTerm);

                if (matchesCategory && matchesSearch) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        // Build Live Suggestions Dropdown
        function updateSuggestions(searchTerm) {
            suggestionsBox.innerHTML = '';
            let hasMatches = false;

            filterableItems.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                const category = item.getAttribute('data-category') || '';

                if (name.toLowerCase().includes(searchTerm)) {
                    hasMatches = true;
                    
                    const div = document.createElement('div');
                    div.className = 'suggestion-item';
                    
                    const regex = new RegExp(`(${searchTerm})`, "gi");
                    const highlightedName = name.replace(regex, "<span style='color: var(--gold);'>$1</span>");

                    div.innerHTML = `
                        <span>${highlightedName}</span>
                        <span class="suggestion-category">${category}</span>
                    `;

                    div.addEventListener('click', () => {
                        searchInput.value = name;
                        suggestionsBox.classList.remove('active');
                        applyFilters();
                    });

                    suggestionsBox.appendChild(div);
                }
            });

            if (hasMatches) {
                suggestionsBox.classList.add('active');
            } else {
                suggestionsBox.innerHTML = '<div class="suggestion-item" style="color: rgba(240, 232, 213, 0.5);">No fragrances found</div>';
                suggestionsBox.classList.add('active');
            }
        }
    });
});