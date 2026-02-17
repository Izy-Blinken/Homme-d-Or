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
  
  event.target.classList.add('active');
  
  const navbar = document.querySelector('.navbar');
  const tabsBar = document.querySelector('.tabs');
  const scrollTop = navbar.offsetHeight + tabsBar.offsetHeight;
  window.scrollTo({ top: scrollTop, behavior: 'smooth' });
}

// Filters
document.addEventListener('DOMContentLoaded', function() {
  const filterChips = document.querySelectorAll('.filter-chip');
  
  filterChips.forEach(chip => {
    chip.addEventListener('click', function() {
      const filterBar = this.closest('.filter-bar');
      
      const chips = filterBar.querySelectorAll('.filter-chip');
      chips.forEach(c => c.classList.remove('active'));
      
      this.classList.add('active');
    });
  });
});


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