// Category pill filtering — no page reload
const pills = document.querySelectorAll('.pill');
const cards = document.querySelectorAll('.post-card');
const noResults = document.getElementById('noResults');

pills.forEach(pill => {
    pill.addEventListener('click', () => {
        pills.forEach(p => p.classList.remove('active'));
        pill.classList.add('active');

        const selected = pill.dataset.category;
        let visible = 0;

        cards.forEach(card => {
            const match = selected === 'all' || card.dataset.category === selected;
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
    });
});

// Newsletter AJAX subscribe
document.getElementById('subscribeBtn').addEventListener('click', () => {
    const email = document.getElementById('newsletterEmail').value.trim();
    const msg = document.getElementById('newsletterMsg');

    if (!email) {
        msg.textContent = 'Please enter your email.';
        msg.className = 'newsletter-msg error';
        return;
    }

    fetch('../backend/blog/subscribe.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'email=' + encodeURIComponent(email)
    })
    .then(r => r.json())
    .then(data => {
        msg.textContent = data.message;
        msg.className = 'newsletter-msg' + (data.success ? '' : ' error');
        if (data.success) document.getElementById('newsletterEmail').value = '';
    })
    .catch(() => {
        msg.textContent = 'Something went wrong. Try again.';
        msg.className = 'newsletter-msg error';
    });
});

