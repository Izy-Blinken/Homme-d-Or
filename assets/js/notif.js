document.addEventListener('DOMContentLoaded', () => {
    const notifBell = document.getElementById('notif-bell');
    const notifPanel = document.getElementById('notif-panel');
    const notifList = document.getElementById('notif-list');
    const notifCount = document.getElementById('notif-count');
    const markAllReadBtn = document.getElementById('mark-all-read');

    // 1. Check if the user is logged in via the HTML data attribute
    const isLoggedIn = notifBell.dataset.loggedin === 'true';

    // 2. Dummy Data
    let notifications = [
        { id: 1, type: 'promo', title: 'Flash Sale! 20% Off', message: 'Use code GOLDEN20 at checkout for 20% off all Oud fragrances.', time: '10 mins ago', isRead: false },
        { id: 2, type: 'status_shipped', title: 'Order #1042 Shipped', message: 'Your order is on the way! Track your package via the link sent to your email.', time: '2 hours ago', isRead: false },
        { id: 3, type: 'new_arrival', title: 'New Arrival: Royal Amber', message: 'Discover our newest premium fragrance, Royal Amber. Available now.', time: '1 day ago', isRead: false },
        { id: 4, type: 'restock', title: 'Wishlist Restock', message: 'Good news! Midnight Oud is back in stock. Grab it before it sells out again.', time: '1 day ago', isRead: true },
        { id: 5, type: 'order_placed', title: 'Order #1045 Confirmed', message: 'Thank you for your purchase. We are preparing your order.', time: '2 days ago', isRead: true },
        { id: 6, type: 'status_paid', title: 'Payment Received', message: 'Payment for Order #1045 has been successfully processed.', time: '2 days ago', isRead: true },
        { id: 7, type: 'voucher', title: 'New Voucher Unlocked', message: 'You earned a free shipping voucher! Valid until the end of the month.', time: '3 days ago', isRead: true },
        { id: 8, type: 'status_completed', title: 'Order #1020 Completed', message: 'Your package has been delivered. Enjoy your Homme d\'Or fragrances!', time: '1 week ago', isRead: true },
        { id: 9, type: 'status_cancelled', title: 'Order #1015 Cancelled', message: 'Your order has been cancelled as requested. Refunds take 3-5 business days.', time: '2 weeks ago', isRead: true }
    ];

    function getNotifIcon(type) {
        const icons = {
            'new_arrival': '<i class="fa-solid fa-star" style="color: #c9a961;"></i>',
            'restock': '<i class="fa-solid fa-box-open" style="color: #4CAF50;"></i>',
            'promo': '<i class="fa-solid fa-tag" style="color: #E91E63;"></i>',
            'order_placed': '<i class="fa-solid fa-receipt" style="color: #2196F3;"></i>',
            'status_paid': '<i class="fa-solid fa-money-bill-wave" style="color: #4CAF50;"></i>',
            'status_shipped': '<i class="fa-solid fa-truck-fast" style="color: #FF9800;"></i>',
            'status_completed': '<i class="fa-solid fa-check-circle" style="color: #4CAF50;"></i>',
            'status_cancelled': '<i class="fa-solid fa-times-circle" style="color: #F44336;"></i>',
            'voucher': '<i class="fa-solid fa-ticket" style="color: #9C27B0;"></i>'
        };
        return icons[type] || '<i class="fa-solid fa-bell"></i>';
    }

    function renderNotifications() {
        // If the user is a guest, do not render notifications or show the red bubble
        if (!isLoggedIn) return;

        notifList.innerHTML = ''; 
        let unreadCount = 0;

        if (notifications.length === 0) {
            notifList.innerHTML = '<div class="notif-empty">No notifications yet.</div>';
        } else {
            notifications.forEach(notif => {
                if (!notif.isRead) unreadCount++;

                const notifItem = document.createElement('div');
                notifItem.className = `notif-item ${notif.isRead ? '' : 'unread'}`;
                notifItem.dataset.id = notif.id;

                notifItem.innerHTML = `
                    <div class="notif-item-layout">
                        <div class="notif-icon-bg">
                            ${getNotifIcon(notif.type)}
                        </div>
                        <div class="notif-content-wrapper">
                            <div class="notif-title">${notif.title}</div>
                            <div class="notif-msg">${notif.message}</div>
                            <div class="notif-time">${notif.time}</div>
                        </div>
                        ${!notif.isRead ? '<div class="notif-unread-dot"></div>' : ''}
                    </div>
                `;

                notifItem.addEventListener('click', () => {
                    notif.isRead = true;
                    renderNotifications(); 
                });

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

    // 3. Toggle logic with guest protection
    notifBell.addEventListener('click', (e) => {
        e.preventDefault();
        
        // If the user is a guest, exit the function immediately (nothing happens)
        if (!isLoggedIn) return;
        
        notifPanel.classList.toggle('open');
    });

    document.addEventListener('click', (e) => {
        if (!notifBell.contains(e.target) && !notifPanel.contains(e.target)) {
            notifPanel.classList.remove('open');
        }
    });

    markAllReadBtn.addEventListener('click', () => {
        notifications.forEach(n => n.isRead = true);
        renderNotifications();
    });

    renderNotifications();
});