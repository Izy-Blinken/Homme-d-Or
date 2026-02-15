let currentRating = 0;
let hoverValue = 0;

// Review Order Modal
function openReviewModal() {
    const modal = document.getElementById('reviewOrderModal');
    modal.classList.remove('closing');
    modal.classList.add('show');
    
    currentRating = 0;
    updateStars();
    document.getElementById('reviewText').value = '';
    updateSubmitButton();
}

function closeReviewModal() {
    const modal = document.getElementById('reviewOrderModal');
    modal.classList.add('closing');
    setTimeout(() => {
        modal.classList.remove('show');
        modal.classList.remove('closing');
    }, 200);

    
}

function setRating(rating) {
    currentRating = rating;
    updateStars();
    updateSubmitButton();
    document.getElementById('ratingText').textContent = `${rating} of 5 stars`;
}

function hoverRating(rating) {
    hoverValue = rating;
    updateStars();
}

function resetHover() {
    hoverValue = 0;
    updateStars();
}

function updateStars() {
    const stars = document.querySelectorAll('.star');
    const activeValue = hoverValue || currentRating;
    
    stars.forEach((star, index) => {
        if (index < activeValue) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}

function updateSubmitButton() {
    const submitBtn = document.getElementById('submitReviewBtn');
    submitBtn.disabled = currentRating === 0;
}

function submitReview(event) {
    event.preventDefault();
    const reviewText = document.getElementById('reviewText').value;
    
    console.log('Review Submitted:');
    console.log('Rating:', currentRating);
    console.log('Review:', reviewText);
    
    showGeneralToast('Review Submitted Successfully!', 'success');
    closeReviewModal();
}

//Cancel Order Modal
function openCancelModal() {
    const modal = document.getElementById('cancelOrderModal');
    modal.classList.remove('closing');
    modal.classList.add('show');
    
    //reset form
    const radioButtons = document.querySelectorAll('input[name="cancelReason"]');
    radioButtons.forEach(radio => radio.checked = false);
    document.getElementById('otherReason').value = '';
    document.getElementById('otherReasonGroup').style.display = 'none';
}

function closeCancelModal() {
    const modal = document.getElementById('cancelOrderModal');
    modal.classList.add('closing');
    setTimeout(() => {
        modal.classList.remove('show');
        modal.classList.remove('closing');
    }, 200);


}

function submitCancellation(event) {
    event.preventDefault();
    const selectedReason = document.querySelector('input[name="cancelReason"]:checked').value;
    const otherReason = document.getElementById('otherReason').value;
    
    console.log('Order Cancelled:');
    console.log('Reason:', selectedReason);
    if (selectedReason === 'Other' && otherReason) {
        console.log('Additional Details:', otherReason);
    }
    
    let message = `Order cancelled!\nReason: ${selectedReason}`;
    if (selectedReason === 'Other' && otherReason) {
        message += `\nDetails: ${otherReason}`;
    }
    
    showGeneralToast('Order Cancelled Successfully!', 'success');
    closeCancelModal();
}


document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('reviewOrderModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReviewModal();
        }
    });
    
    document.getElementById('cancelOrderModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCancelModal();
        }
    });
    
    // other reason text area
    const radioButtons = document.querySelectorAll('input[name="cancelReason"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            const otherGroup = document.getElementById('otherReasonGroup');
            if (this.value === 'Other') {
                otherGroup.style.display = 'block';
            } else {
                otherGroup.style.display = 'none';
            }
        });
    });
});

function showGeneralToast(message, type='success') {
    const toast = document.getElementById('generalToast');
    toast.textContent = message;
    toast.className = `generalToast ${type}`;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000); 
}