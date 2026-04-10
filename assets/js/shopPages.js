// Auto-Load Tab from URL
document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  const tabId = urlParams.get('tab');

  if (tabId) {
    setTimeout(() => {
      showPage(tabId);
    }, 100);
  }
});

// Tab switching
function showPage(pageId) {
  const pages = document.querySelectorAll('.page');
  pages.forEach(page => page.classList.remove('active'));

  const tabs = document.querySelectorAll('.tab');
  tabs.forEach(tab => tab.classList.remove('active'));

  const targetPage = document.getElementById(pageId);
  if (targetPage) {
    targetPage.classList.add('active');
  }

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

// Countdown
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

// Search + Sort per page
document.addEventListener("DOMContentLoaded", () => {
  const pages = document.querySelectorAll('.page');

  pages.forEach(page => {
    const searchInput = page.querySelector('.search-input');
    const suggestionsBox = page.querySelector('.search-suggestions');
    const sortSelect = page.querySelector('.sort-select');
    const grid = page.querySelector('.product-grid');

    if (!searchInput || !grid) return;

    const allCards = Array.from(grid.querySelectorAll('.product-card'));
    const originalOrder = [...allCards];
    let currentSort = 'default';

    searchInput.addEventListener('input', () => {
      const searchTerm = searchInput.value.toLowerCase().trim();
      if (searchTerm.length > 0) {
        updateSuggestions(searchTerm);
      } else if (suggestionsBox) {
        suggestionsBox.classList.remove('active');
      }
      applyFiltersAndSort();
    });

    if (sortSelect) {
      sortSelect.addEventListener('change', () => {
        currentSort = sortSelect.value;
        applyFiltersAndSort();
      });
    }

    document.addEventListener('click', (e) => {
      if (!e.target.closest('.search-container') && suggestionsBox) {
        suggestionsBox.classList.remove('active');
      }
    });

    function applyFiltersAndSort() {
      const searchTerm = searchInput.value.toLowerCase().trim();

      // Filter first
      const filtered = originalOrder.filter(card => {
        const name = (card.getAttribute('data-name') || '').toLowerCase();
        return name.includes(searchTerm);
      });

      // Sort filtered set
      let sorted = [...filtered];
      switch (currentSort) {
        case 'price-asc':
          sorted.sort((a, b) => parseFloat(a.dataset.price || 0) - parseFloat(b.dataset.price || 0));
          break;
        case 'price-desc':
          sorted.sort((a, b) => parseFloat(b.dataset.price || 0) - parseFloat(a.dataset.price || 0));
          break;
        case 'name-asc':
          sorted.sort((a, b) => (a.dataset.name || '').localeCompare(b.dataset.name || ''));
          break;
        case 'name-desc':
          sorted.sort((a, b) => (b.dataset.name || '').localeCompare(a.dataset.name || ''));
          break;
        case 'most-bought':
          sorted.sort((a, b) => parseInt(b.dataset.bought || 0, 10) - parseInt(a.dataset.bought || 0, 10));
          break;
        case 'default':
        default:
          // keep original order but filtered
          sorted = originalOrder.filter(card => filtered.includes(card));
          break;
      }

      // Render
      grid.innerHTML = '';
      sorted.forEach(card => {
        card.classList.remove('hidden');
        grid.appendChild(card);
      });

      // hide not included (if needed elsewhere)
      originalOrder.forEach(card => {
        if (!sorted.includes(card)) card.classList.add('hidden');
      });
    }

    function updateSuggestions(searchTerm) {
      if (!suggestionsBox) return;

      suggestionsBox.innerHTML = '';
      let hasMatches = false;

      originalOrder.forEach(card => {
        const name = card.getAttribute('data-name') || '';
        if (name.includes(searchTerm)) {
          hasMatches = true;
          const div = document.createElement('div');
          div.className = 'suggestion-item';

          const regex = new RegExp(`(${searchTerm})`, "gi");
          const highlightedName = name.replace(regex, "<span style='color: var(--gold);'>$1</span>");

          div.innerHTML = `<span>${highlightedName}</span>`;
          div.addEventListener('click', () => {
            searchInput.value = name;
            suggestionsBox.classList.remove('active');
            applyFiltersAndSort();
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