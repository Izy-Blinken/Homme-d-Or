// Image Gallery Swapper
function changeImage(element) {
    // Update main image source
    document.getElementById('main-product-image').src = element.src;
    
    // Manage active state on thumbnails
    let thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach(thumb => thumb.classList.remove('active-thumb'));
    element.classList.add('active-thumb');
}

// Variant Selector Toggle
function selectVariant(element) {
    let options = element.parentElement.querySelectorAll('.variant-btn');
    options.forEach(opt => opt.classList.remove('active'));
    element.classList.add('active');
}