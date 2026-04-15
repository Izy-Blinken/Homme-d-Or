let currentRating = 0;
let hoverValue = 0;
let currentProductId = null;
let currentOrderId = null;

function openReviewModal(orderId, productId) {
    currentOrderId = orderId;
    currentProductId = productId;
    const modal = document.getElementById('reviewOrderModal');
    modal.classList.remove('closing');
    modal.classList.add('show');
    currentRating = 0;
    updateStars();
    document.getElementById('reviewText').value = '';
    document.getElementById('ratingText').textContent = '';
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
    if (submitBtn) submitBtn.disabled = currentRating === 0;
}

function submitReview(event) {
    event.preventDefault();
    const reviewText = document.getElementById('reviewText').value;

    if (!currentProductId) {
        showGeneralToast('Error: Product ID missing', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('product_id', currentProductId);
    formData.append('rating', currentRating);
    formData.append('comment', reviewText);

    fetch('../backend/products/submit_review.php', {        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showGeneralToast('Review Submitted Successfully!', 'success');
            closeReviewModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showGeneralToast(data.message || 'Failed to submit review', 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showGeneralToast('Something went wrong', 'error');
    });
}

function openCancelModal(orderId) {
    document.getElementById('cancelOrderId').value = orderId;
    const modal = document.getElementById('cancelOrderModal');
    modal.classList.remove('closing');
    modal.classList.add('show');
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
    const checkedRadio = document.querySelector('input[name="cancelReason"]:checked');
    if (!checkedRadio) {
        showGeneralToast('Please select a cancellation reason.', 'error');
        return;
    }
    const selectedReason = checkedRadio.value;
    const otherReason = document.getElementById('otherReason').value;
    const orderId = document.getElementById('cancelOrderId').value;
    const finalReason = (selectedReason === 'Other' && otherReason)
        ? otherReason
        : selectedReason;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    fetch('../backend/cancelOrder.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-Token': csrfToken
    },
    body: `order_id=${orderId}&reason=${encodeURIComponent(finalReason)}`

})

.then(res => {
    console.log('Status:', res.status);
    return res.text();
})
.then(text => {
    console.log('Raw response:', text);
    const data = JSON.parse(text);
    if (data.success) {
        showGeneralToast('Order Cancelled Successfully!', 'success');
        closeCancelModal();
        setTimeout(() => location.reload(), 1500);
    } else {
        showGeneralToast(data.message || 'Cancellation failed.', 'error');
    }
})
.catch(err => {
    console.error('Cancel error:', err);
    showGeneralToast('Something went wrong.', 'error');
});
}

function openViewModal(img, name, variant, qty, total, payment, date, status) {
    document.getElementById("viewImage").src = img;
    document.getElementById("viewName").textContent = name;
    document.getElementById("viewVariant").textContent = variant;
    document.getElementById("viewQty").textContent = qty;
    document.getElementById("viewTotal").textContent = total;
    document.getElementById("viewPayment").textContent = payment;
    document.getElementById("viewDate").textContent = date;
    document.getElementById("viewStatus").textContent = status;
    const modal = document.getElementById("viewOrderModal");
    modal.classList.remove("closing");
    modal.classList.add("show");
}

function closeViewModal() {
    const modal = document.getElementById("viewOrderModal");
    modal.classList.add("closing");
    setTimeout(() => {
        modal.classList.remove("show", "closing");
    }, 200);
}

function showGeneralToast(message, type = 'success') {
    const toast = document.getElementById('generalToast');
    toast.textContent = message;
    toast.className = `generalToast ${type}`;
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    const reviewModal = document.getElementById('reviewOrderModal');
    if (reviewModal) {
        reviewModal.addEventListener('click', function(e) {
            if (e.target === this) closeReviewModal();
        });
    }

    const cancelModal = document.getElementById('cancelOrderModal');
    if (cancelModal) {
        cancelModal.addEventListener('click', function(e) {
            if (e.target === this) closeCancelModal();
        });
    }

    const viewModal = document.getElementById('viewOrderModal');
    if (viewModal) {
        viewModal.addEventListener('click', function(e) {
            if (e.target === this) closeViewModal();
        });
    }

    const radioButtons = document.querySelectorAll('input[name="cancelReason"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            const otherGroup = document.getElementById('otherReasonGroup');
            otherGroup.style.display = this.value === 'Other' ? 'block' : 'none';
        });
    });
});