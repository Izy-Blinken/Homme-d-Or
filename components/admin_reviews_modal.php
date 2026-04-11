<!-- Customer Reviews Modal (Admin Panel) -->
<!-- Usage: call openCustomerReviews(userId, fullName) from any action button -->

<div class="romcomOverlay" id="adminReviewsModal" role="dialog" aria-modal="true">
    <div class="romcomModalContent" style="max-width: 38rem;">
        <div class="romcomHeader" style="position: relative;">
            <h2>Customer Reviews</h2>
            <span class="view-close-btn" id="closeAdminReviewsModal">&times;</span>
        </div>
        <div class="romcomDivider"></div>
        <div class="romcomBody" id="adminReviewsBody">
            <div style="text-align:center; padding: 2rem; color: #94a3b8;">
                <i class="fa-solid fa-spinner fa-spin" style="color:#c9a961;"></i> Loading...
            </div>
        </div>
        <div style="padding: 0 2.5rem 2rem; text-align:right;">
            <button class="romcomBtnClose" onclick="closeAdminReviews()"
                style="padding:0.6rem 1.5rem; border:1px solid white; background:#64748b; color:white; font-weight:600; cursor:pointer; font-size:0.875rem; text-transform:uppercase; letter-spacing:0.05em;">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function openCustomerReviews(userId, fullName) {
    const modal = document.getElementById('adminReviewsModal');
    const body = document.getElementById('adminReviewsBody');

    body.innerHTML = `
        <div style="text-align:center; padding:2rem; color:#94a3b8;">
            <i class="fa-solid fa-spinner fa-spin" style="color:#c9a961; margin-right:0.5rem;"></i> Loading reviews...
        </div>`;

    modal.style.display = 'flex';
    modal.offsetHeight;
    modal.classList.add('show');
    modal.classList.remove('closing');

    fetch(`../../backend/admin/get_reviews.php?user_id=${userId}`)
        .then(r => r.json())
        .then(data => {
            if (!data.success || !data.reviews.length) {
                body.innerHTML = `
                    <p style="text-align:center; color:#64748b; padding:2rem;">
                        ${escHtml(fullName)} has not submitted any reviews yet.
                    </p>`;
                return;
            }

            const cards = data.reviews.map(r => {
                const date = new Date(r.created_at).toLocaleDateString('en-US', {
                    year: 'numeric', month: 'long', day: 'numeric'
                });
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    stars += `<i class="fa-${i <= r.rating ? 'solid' : 'regular'} fa-star"
                        style="color:${i <= r.rating ? '#c9a961' : '#475569'}; font-size:0.8rem;"></i>`;
                }
                const img = r.product_image
                    ? `../../assets/images/products/${escHtml(r.product_image)}`
                    : '../../assets/images/brand_images/nocturne.png';

                return `
                    <div style="border:1px solid rgba(201,169,97,0.2); padding:1.25rem; margin-bottom:1rem; background:rgba(255,255,255,0.02);">
                        <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.75rem;">
                            <img src="${img}" alt="" style="width:50px; height:50px; object-fit:cover; border:1px solid #c9a961;">
                            <div>
                                <p style="color:#c9a961; font-weight:600; font-size:0.9rem; margin:0;">
                                    ${escHtml(r.product_name)}
                                </p>
                                <div style="margin:0.2rem 0;">${stars}</div>
                                <p style="color:#64748b; font-size:0.75rem; margin:0;">${date}</p>
                            </div>
                        </div>
                        ${r.comment
                            ? `<p style="color:#e2e8f0; font-size:0.85rem; line-height:1.6; margin:0;">${escHtml(r.comment)}</p>`
                            : `<p style="color:#64748b; font-size:0.8rem; font-style:italic; margin:0;">No written comment.</p>`
                        }
                    </div>`;
            }).join('');

            body.innerHTML = `
                <p style="color:#94a3b8; font-size:0.85rem; margin-bottom:1.25rem;">
                    ${data.reviews.length} review${data.reviews.length !== 1 ? 's' : ''} from
                    <strong style="color:white;">${escHtml(fullName)}</strong>
                </p>
                ${cards}`;
        })
        .catch(() => {
            body.innerHTML = `
                <p style="text-align:center; color:#e74c3c; padding:2rem;">
                    Failed to load reviews. Please try again.
                </p>`;
        });
}

function closeAdminReviews() {
    const modal = document.getElementById('adminReviewsModal');
    modal.classList.add('closing');
    modal.classList.remove('show');
    modal.addEventListener('animationend', function handler() {
        modal.style.display = 'none';
        modal.classList.remove('closing');
        modal.removeEventListener('animationend', handler);
    });
}

function escHtml(str) {
    return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

document.getElementById('closeAdminReviewsModal').addEventListener('click', closeAdminReviews);

document.getElementById('adminReviewsModal').addEventListener('click', function (e) {
    if (e.target === this) closeAdminReviews();
});

document.addEventListener('keydown', function (e) {
    const modal = document.getElementById('adminReviewsModal');
    if (e.key === 'Escape' && modal.classList.contains('show')) closeAdminReviews();
});
</script>