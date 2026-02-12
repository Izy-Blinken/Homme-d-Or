// Wishlist/History filter
document.querySelector('.filter-btn').addEventListener('click', function(e) {
    e.stopPropagation();
        this.nextElementSibling.classList.toggle('show');
});

document.addEventListener('click', function() {
    document.querySelectorAll('.filter-menu').forEach(menu => {
         menu.classList.remove('show');
    });
})

// Filter options
document.querySelectorAll('.filter-option').forEach(option => {
    option.addEventListener('click', function() {
        console.log('Filter by:', this.textContent);
            this.closest('.filter-menu').classList.remove('show');
    });
});

