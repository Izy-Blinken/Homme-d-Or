document.addEventListener('DOMContentLoaded', () => {
    const notifBell = document.getElementById('notif-bell');
    const notifPanel = document.getElementById('notif-panel');
    const notifList = document.getElementById('notif-list');
    const notifCount = document.getElementById('notif-count');
    const markAllReadBtn = document.getElementById('mark-all-read');

    const isLoggedIn = notifBell.dataset.loggedin === 'true';
    const isGuest = notifBell.dataset.isguest === 'true';

    if (!isLoggedIn && !isGuest) return;

    // ── ICON MAP ───────────────────────────────────────────────────
    function getNotifIcon(type) {
        const icons = {
            'restock': '<i class="fa-solid fa-box-open" style="color:#4CAF50;"></i>',
            'order_status': '<i class="fa-solid fa-truck-fast" style="color:#FF9800;"></i>',
            'voucher': '<i class="fa-solid fa-ticket" style="color:#9C27B0;"></i>',
            'admin_assignment': '<i class="fa-solid fa-user-shield" style="color:#2196F3;"></i>',
            'password_change': '<i class="fa-solid fa-lock" style="color:#F44336;"></i>',
            'cart_reminder': '<i class="fa-solid fa-cart-shopping" style="color:#FF9800;"></i>',
            'sale_product': '<i class="fa-solid fa-tag" style="color:#E91E63;"></i>',
            'message': '<i class="fa-solid fa-envelope" style="color:#2196F3;"></i>',
            'general': '<i class="fa-solid fa-bell" style="color:#c9a961;"></i>',
        };
        return icons[type] || '<i class="fa-solid fa-bell"></i>';
    }

    // ── TIME AGO ───────────────────────────────────────────────────
    function timeAgo(dateStr) {
        const now = new Date();
        const date = new Date(dateStr);
        const diff = Math.floor((now - date) / 1000);

        if (diff < 60) return 'Just now';
        if (diff < 3600) return Math.floor(diff / 60) + ' mins ago';
        if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
        if (diff < 604800) return Math.floor(diff / 86400) + ' days ago';
        return date.toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    // ── RENDER ─────────────────────────────────────────────────────
    function renderNotifications(notifications, unreadCount) {
        notifList.innerHTML = '';

        if (notifications.length === 0) {
            notifList.innerHTML = '<div class="notif-empty">No notifications yet.</div>';
        } else {
            notifications.forEach(notif => {
                const notifItem = document.createElement('div');
                notifItem.className = `notif-item ${notif.is_read == 1 ? '' : 'unread'}`;
                notifItem.dataset.id = notif.notif_id;

                notifItem.innerHTML = `
                    <div class="notif-item-layout">
                        <div class="notif-icon-bg">
                            ${getNotifIcon(notif.notif_type)}
                        </div>
                        <div class="notif-content-wrapper">
                            <div class="notif-msg">${notif.notif_message}</div>
                            <div class="notif-time">${timeAgo(notif.created_at)}</div>
                        </div>
                        ${notif.is_read == 0 ? '<div class="notif-unread-dot"></div>' : ''}
                    </div>
                `;

                notifItem.addEventListener('click', () => markRead(notif.notif_id, notifItem));
                notifList.appendChild(notifItem);
            });
        }

        if (unreadCount > 0) {
            notifCount.style.display = 'block';
            notifCount.textContent = unreadCount;
        } else {
            notifCount.style.display = 'none';
        }
    }

    // ── FETCH FROM BACKEND ─────────────────────────────────────────
    function loadNotifications() {
        fetch('../backend/notifications/get_notifications.php')
            .then(res => res.json())
            .then(data => {
                renderNotifications(data.notifications, data.unread_count);
            })
            .catch(() => {
                notifList.innerHTML = '<div class="notif-empty">Failed to load notifications.</div>';
            });
    }

    // ── MARK SINGLE READ ───────────────────────────────────────────
    function markRead(notifId, itemEl) {
        const formData = new FormData();
        formData.append('notif_id', notifId);

        fetch('../backend/notifications/mark_read.php', {
            method: 'POST',
            body: formData
        }).then(() => loadNotifications());
    }

    // MARK ALL READ
    markAllReadBtn.addEventListener('click', () => {
        fetch('../backend/notifications/mark_read.php', {
            method: 'POST',
            body: new FormData()
        }).then(() => loadNotifications());
    });

    // BELL TOGGLE
    notifBell.addEventListener('click', (e) => {
        e.preventDefault();
        if (!isLoggedIn && !isGuest) return;
        notifPanel.classList.toggle('open');
        if (notifPanel.classList.contains('open')) loadNotifications();
    });

    document.addEventListener('click', (e) => {
        if (!notifBell.contains(e.target) && !notifPanel.contains(e.target)) {
            notifPanel.classList.remove('open');
        }
    });

    loadNotifications();

    setInterval(loadNotifications, 30000);
});