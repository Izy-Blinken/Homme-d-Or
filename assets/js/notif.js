document.addEventListener('DOMContentLoaded', () => {

    const bell = document.getElementById('notif-bell');
    const panel = document.getElementById('notif-panel');
    const countBadge = document.getElementById('notif-count');
    const notifList = document.getElementById('notif-list');
    const markAllBtn = document.getElementById('mark-all-read');

    if (!bell || !panel) return;

    let allNotifs = [];
    let panelOpen = false;
    let showingDetail = false;

    function timeAgo(dateStr) {

        const date = new Date(dateStr);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000);

        if (diff < 60) {
            return 'just now';
        }

        if (diff < 3600) {
            return Math.floor(diff / 60) + 'm ago';
        }

        if (diff < 86400) {
            return Math.floor(diff / 3600) + 'h ago';
        }

        return date.toLocaleDateString('en-PH', { month: 'short', day: 'numeric' });
    }

    function renderList() {

        showingDetail = false;

        if (allNotifs.length === 0) {
            notifList.innerHTML = '<div class="notif-empty">No notifications yet.</div>';
            return;
        }

        notifList.innerHTML = allNotifs.map(n => `
            <div class="notif-item ${n.is_read == 0 ? 'unread' : ''}" data-id="${n.notif_id}" data-msg="${encodeURIComponent(n.notif_message)}" data-time="${n.created_at}">
                <div>${n.notif_message}</div>
                <div class="notif-time">${timeAgo(n.created_at)}</div>
            </div>
        `).join('');


        notifList.querySelectorAll('.notif-item').forEach(item => {

            item.addEventListener('click', (e) => {
                e.stopPropagation();
                openDetail(item);
            });

        });
    }

    function openDetail(item) {

        const id = item.dataset.id;
        const msg = decodeURIComponent(item.dataset.msg);
        const time = item.dataset.time;

        // mark as read
        const body = new FormData();
        body.append('notif_id', id);
        fetch('/homme_dor/backend/notifications/mark_read.php', { method: 'POST', body });
        item.classList.remove('unread');

        showingDetail = true;

        notifList.innerHTML = `
            <div class="notif-detail open">

                <button class="notif-detail-back" id="notif-back">← Back</button>
                <div class="notif-detail-content">
                    <div class="notif-detail-label">MESSAGE</div>
                    <div class="notif-detail-value">${msg}</div>
                    <div class="notif-detail-label">RECEIVED</div>
                    <div style="font-size:0.85rem; color:#888;">${new Date(time).toLocaleString('en-PH')}</div>
                </div>

            </div>
        `;

        document.getElementById('notif-back').addEventListener('click', (e) => {
            e.stopPropagation();
            renderList();
            loadNotifications();
        });
    }

    function loadNotifications() {

        fetch('/homme_dor/backend/notifications/get_notifications.php')
            .then(res => res.json())
            .then(data => {

                allNotifs = data.notifications ?? [];
                const unread = data.unread_count ?? 0;

                if (unread > 0) {
                    countBadge.textContent = unread > 99 ? '99+' : unread;
                    countBadge.style.display = 'inline';
                } else {
                    countBadge.style.display = 'none';
                }

                if (!showingDetail) {
                    renderList();
                }

            })
        .catch(() => {});
    }

    bell.addEventListener('click', (e) => {

        e.stopPropagation();
        panelOpen = !panelOpen;
        panel.classList.toggle('open', panelOpen);

        if (panelOpen) loadNotifications();
    });

    document.addEventListener('click', (e) => {

        if (!panel.contains(e.target) && e.target !== bell) {
            panelOpen = false;
            panel.classList.remove('open');
        }

    });

    if (markAllBtn) {

        markAllBtn.addEventListener('click', () => {
            
            fetch('../backend/notifications/mark_read.php', { method: 'POST' });
            allNotifs.forEach(n => n.is_read = 1);
            countBadge.style.display = 'none';

            if (!showingDetail) renderList();
        });

    }

    // polling every 5 seconds
    loadNotifications();
    setInterval(loadNotifications, 5000);

});