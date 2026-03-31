// cust details modal
const customerModal = document.getElementById('customer-modal');
const customerModalClose = document.getElementById('customer-modal-close');
const customerModalDone = document.getElementById('customer-modal-done');

if (customerModal) {

    document.querySelectorAll('.view-customer-btn').forEach(btn => {

        btn.addEventListener('click', () => {

            const d = btn.dataset;

            document.getElementById('modal-cust-name').textContent = d.name;
            document.getElementById('modal-cust-email').textContent = d.email;
            document.getElementById('modal-cust-phone').textContent = d.phone;
            document.getElementById('modal-cust-joined').textContent = d.joined;
            document.getElementById('modal-cust-last-order').textContent = d.lastOrder;
            document.getElementById('modal-cust-orders').textContent = d.orders;
            document.getElementById('modal-cust-spent').textContent = d.spent;
            document.getElementById('modal-cust-status').textContent = d.status;
            document.getElementById('modal-cust-verified').textContent = d.verified;

            //admin label
            const adminLabel = document.getElementById('modal-admin-label');
            adminLabel.style.display = d.isAdmin === '1' ? 'inline' : 'none';

            // pfp or icon lang(pag walang pic)
            const img = document.getElementById('customer-photo-img');
            const placeholder = document.getElementById('customer-photo-placeholder');

            if (d.photo) {

                img.src = `../../assets/images/profiles/${d.photo}`;
                img.style.display = 'block';
                placeholder.style.display = 'none';

            } else {
                img.style.display = 'none';
                placeholder.style.display = 'flex';
            }

            customerModal.classList.add('show');

        });

    });

    customerModalClose.addEventListener('click', () => customerModal.classList.remove('show'));
    customerModalDone.addEventListener('click', () => customerModal.classList.remove('show'));
}


// reviews modal
const reviewsModal = document.getElementById('reviews-modal');
const reviewsModalClose = document.getElementById('reviews-modal-close');
const reviewsModalDone = document.getElementById('reviews-modal-done');

if (reviewsModal) {

    document.querySelectorAll('.view-reviews-btn').forEach(btn => {

        btn.addEventListener('click', async () => {

            document.getElementById('reviews-modal-name').textContent = btn.dataset.name;
            document.getElementById('reviews-list').innerHTML = '<p style="text-align:center; color:#888;">Loading...</p>';
            reviewsModal.classList.add('show');

            //revs ni cust
            const res = await fetch(`../../backend/customers/get_reviews.php?user_id=${btn.dataset.id}`);
            const data = await res.json();

            if (data.length === 0) {

                document.getElementById('reviews-list').innerHTML = '<p style="text-align:center; color:#888; padding:1rem;">No reviews yet.</p>';
                return;
            }

            document.getElementById('reviews-list').innerHTML = data.map(r => `

                <div class="review-row">

                    <div class="review-row-info">
                        <div class="review-product-name">${r.product_name}</div>
                        <div class="review-rating">${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}</div>
                        <div class="review-date">${r.created_at}</div>
                    </div>

                    <button class="btn-view-details view-single-review-btn"
                        data-product="${r.product_name}"
                        data-rating="${r.rating}"
                        data-comment="${r.comment ?? ''}"
                        data-date="${r.created_at}">
                        View
                    </button>

                </div>

            `).join('');

            
            document.querySelectorAll('.view-single-review-btn').forEach(b => {
                b.addEventListener('click', () => openSingleReview(b.dataset));
            });

        });
    });

    reviewsModalClose.addEventListener('click', () => reviewsModal.classList.remove('show'));
    reviewsModalDone.addEventListener('click', () => reviewsModal.classList.remove('show'));
}


// view review modal
const singleReviewModal = document.getElementById('single-review-modal');
const singleReviewModalClose = document.getElementById('single-review-modal-close');
const singleReviewBack = document.getElementById('single-review-back');

function openSingleReview(d) {

    document.getElementById('single-review-content').innerHTML = `

        <div style="padding: 0.5rem 0;">
        
            <div style="margin-bottom:0.75rem;">
                <small style="color:#888; font-weight:bold;">PRODUCT</small>
                <div style="font-weight:600; margin-top:0.25rem;">${d.product}</div>
            </div>

            <div style="margin-bottom:0.75rem;">
                <small style="color:#888; font-weight:bold;">RATING</small>
                <div style="font-size:1.2rem; color:#f4a100; margin-top:0.25rem;">
                    ${'★'.repeat(parseInt(d.rating))}${'☆'.repeat(5 - parseInt(d.rating))}
                </div>
            </div>

            <div style="margin-bottom:0.75rem;">
                <small style="color:#888; font-weight:bold;">REVIEW</small>
                <div style="margin-top:0.25rem; line-height:1.6;">
                    ${d.comment || '<em style="color:#aaa;">No comment left.</em>'}
                </div>
            </div>

            <div>
                <small style="color:#888; font-weight:bold;">DATE</small>
                <div style="margin-top:0.25rem;">${d.date}</div>
            </div>

        </div>

    `;

    singleReviewModal.classList.add('show');
}

if (singleReviewModal) {
    singleReviewModalClose.addEventListener('click', () => singleReviewModal.classList.remove('show'));
    singleReviewBack.addEventListener('click', () => singleReviewModal.classList.remove('show'));
}


// assign admin modal
const assignAdminModal = document.getElementById('assign-admin-modal');
const assignAdminModalClose = document.getElementById('assign-admin-modal-close');
const assignAdminCancel = document.getElementById('assign-admin-cancel');

if (assignAdminModal) {

    document.querySelectorAll('.assign-admin-btn').forEach(btn => {

        btn.addEventListener('click', () => {

            document.getElementById('assign-admin-name').textContent = btn.dataset.name;
            document.getElementById('assign-user-id').value = btn.dataset.id;
            assignAdminModal.classList.add('show');


        });

    });

    assignAdminModalClose.addEventListener('click', () => assignAdminModal.classList.remove('show'));
    assignAdminCancel.addEventListener('click', () => assignAdminModal.classList.remove('show'));
}