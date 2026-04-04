document.addEventListener('DOMContentLoaded', () => {

    const convoItems = document.getElementById('convo-items');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-btn');
    const assignBtn = document.getElementById('assign-btn');
    const assignSelect = document.getElementById('assign-admin-select');

    let lastMessageId = 0;
    let polling = null;

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

    function loadConversations() {

        const activeFilter = document.querySelector('.convo-filter-btn.active')?.dataset.filter ?? 'all';
        const url = activeFilter === 'archived'
            ? '../../backend/messages/get_conversations.php?archived=1'
            : '../../backend/messages/get_conversations.php';
 
        fetch(url)

            .then(res => res.json())
            .then(data => {

                if (data.length === 0) {
                    convoItems.innerHTML = '<div class="no-convos">No conversations yet.</div>';
                    return;
                }

                const filtered = activeFilter === 'archived' ? data : (activeFilter === 'all' ? data : data.filter(c => {
                    if (activeFilter === 'admin') return c.type === 'admin' || c.type === 'superadmin';
                    if (activeFilter === 'user') return c.type === 'user' || c.type === 'escalated';
                    return c.type === activeFilter;
                }));

                convoItems.innerHTML = filtered.map(c => {

                    const isActive = (c.type === 'user' && OPEN_USER_ID == c.id) ||
                                     (c.type === 'admin' && OPEN_ADMIN_ID == c.id) ||
                                     (c.type === 'superadmin' && OPEN_SUPERADMIN_ID == c.id) ||
                                     (c.type === 'escalated' && OPEN_SESSION_ID == c.session_id);

                    let param;
                    if (c.type === 'user') {
                        param = `user_id=${c.id}`;
                    } else if (c.type === 'admin') {
                        param = `admin_id=${c.id}`;
                    } else if (c.type === 'superadmin') {
                        param = `superadmin_id=${c.id}`;
                    } else {
                        param = `session_id=${c.session_id}`;
                    }

                    const initial = c.name.charAt(0).toUpperCase();
                    const avatarClass = c.type === 'admin' || c.type === 'superadmin' ? 'convo-avatar admin-avatar' : 'convo-avatar';
                    const escalatedLabel = c.type === 'escalated' ? `<span style="font-size:0.7rem; background:#e65100; color:white; padding:1px 6px; margin-left:4px;">ESCALATED</span>` : '';
                    const archivedLabel = c.archived ? `<span style="font-size:0.7rem; background:#888; color:white; padding:1px 6px; margin-left:4px;">ARCHIVED</span>` : '';
                    const label = escalatedLabel + archivedLabel;                    
                    const onClick = isActive ? 'onclick="return false;"' : '';
                    
                    return `

                        <a href="messages.php?${param}" class="convo-item ${isActive ? 'active' : ''}" ${onClick}>
                            <div class="${avatarClass}">${initial}</div>
                            <div class="convo-info">
                                <div class="convo-name">${c.name}${label}</div>
                                <div class="convo-preview">${c.last_message ?? ''}</div>
                            </div>
                            <div class="convo-meta">
                                <div class="convo-time">${c.last_time ? timeAgo(c.last_time) : ''}</div>
                                ${c.unread > 0 ? `<span class="unread-badge">${c.unread}</span>` : ''}
                            </div>
                        </a>
                    `;

                }).join('');

            })
        .catch(() => {});

    }

    function loadMessages() {

        if (!OPEN_USER_ID && !OPEN_ADMIN_ID && !OPEN_SUPERADMIN_ID && !OPEN_SESSION_ID) return;

        let param;
        if (OPEN_SESSION_ID) {
            param = `session_id=${OPEN_SESSION_ID}`;
        } else if (OPEN_USER_ID) {
            param = `user_id=${OPEN_USER_ID}`;
        } else if (OPEN_ADMIN_ID) {
            param = `admin_id=${OPEN_ADMIN_ID}`;
        } else {
            param = `superadmin_id=${OPEN_SUPERADMIN_ID}`;
        }

        fetch(`../../backend/messages/get_messages.php?${param}`)
            .then(res => res.json())
            .then(data => {

                const msgs = data.messages ?? [];

                if (msgs.length === 0) {
                    chatMessages.innerHTML = '<div style="text-align:center; color:#aaa; margin-top:2rem; font-size:0.88rem;">No messages yet.</div>';
                    return;
                }

                const lastId = msgs[msgs.length - 1].message_id;

                if (lastId !== null && lastId === lastMessageId) return;

                lastMessageId = lastId;

                chatMessages.innerHTML = msgs.map(m => {

                    let isSent; //pause muna.

                    if (m.is_bot) {
                        isSent = false;
                    } else if (m.sender_type === 'user') {
                        isSent = false;
                    } else {
                        isSent = m.sender_type === CURRENT_TYPE && m.sender_id == CURRENT_ID;
                    }

                    const time = new Date(m.sent_at).toLocaleTimeString('en-PH', {
                        hour: '2-digit', minute: '2-digit'
                    });

                    const nameLabel = !isSent ? `<div style="font-size:0.72rem; color:#888; margin-bottom:2px;">${m.sender_name}</div>` : '';

                    return `
                        <div class="msg-row ${isSent ? 'sent' : 'received'}">
                            ${nameLabel}
                            <div class="msg-bubble" style="${m.is_bot ? 'background:#f0f0f0; color:#555; font-style:italic;' : ''}">${m.content}</div>
                            <div class="msg-time">${time}</div>
                        </div>
                    `;

                }).join('');

                chatMessages.scrollTop = chatMessages.scrollHeight;

            })
            .catch(() => {});
    }

    function sendMessage() {

        const content = chatInput ? chatInput.value.trim() : '';
        if (!content || !RECEIVER_ID || !RECEIVER_TYPE) return;

        const body = new FormData();

        body.append('content', content);
        body.append('receiver_id', RECEIVER_ID);
        body.append('receiver_type', RECEIVER_TYPE);

        if (OPEN_SESSION_ID) {
            body.append('session_id', OPEN_SESSION_ID);
        }

        chatInput.value = '';
        chatInput.style.height = 'auto';

        fetch('../../backend/messages/send_message.php', { method: 'POST', body })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    loadMessages();
                    loadConversations();
                }

            })

        .catch(() => {});
    }

    if (assignBtn && assignSelect) {

        assignBtn.addEventListener('click', () => {

            const admin_id = assignSelect.value;
            if (!admin_id) return;

            const body = new FormData();

            body.append('session_id', OPEN_SESSION_ID);
            body.append('admin_id', admin_id);
            body.append('user_id', OPEN_USER_ID);

            fetch('../../backend/messages/assign_escalation.php', { method: 'POST', body })
                .then(res => res.json())
                .then(data => {

                    if (data.success) {
                        location.reload();
                    }

                })

            .catch(() => {});

        });
    }

    if (chatInput) {

        chatInput.addEventListener('input', () => {

            chatInput.style.height = 'auto';
            chatInput.style.height = chatInput.scrollHeight + 'px';
        });

        chatInput.addEventListener('keydown', e => {

            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }

        });
    }

    if (sendBtn) {
        sendBtn.addEventListener('click', sendMessage);
    }

    loadConversations();

    document.querySelectorAll('.convo-filter-btn').forEach(btn => {

        btn.addEventListener('click', () => {
            
            document.querySelectorAll('.convo-filter-btn').forEach(b => {
                b.style.borderBottomColor = 'transparent';
                b.classList.remove('active');
            });

            btn.classList.add('active');
            btn.style.borderBottomColor = '#1a2433';

            loadConversations();

        });

    });

    if (OPEN_USER_ID || OPEN_ADMIN_ID || OPEN_SUPERADMIN_ID || OPEN_SESSION_ID) {

        loadMessages();

        polling = setInterval(() => {
            loadMessages();
            loadConversations();

        }, 4000);

    }

    const archiveBtn = document.getElementById('archive-btn');
    const deleteBtn = document.getElementById('delete-btn');

    if (archiveBtn && OPEN_ARCHIVED) {
        archiveBtn.textContent = 'Unarchive';
        archiveBtn.dataset.archived = '1';
    }

    function getOpenConvoType() {

        if (OPEN_SESSION_ID) {
            return 'escalated';
        }

        if (OPEN_USER_ID) {
            return 'user';
        }

        if (OPEN_ADMIN_ID) {
            return 'admin';
        }

        if (OPEN_SUPERADMIN_ID) {
            return 'superadmin';
        }

        return null;
    }

    function getOpenConvoRefId() {

        if (OPEN_SESSION_ID) {
            return OPEN_SESSION_ID;
        }

        if (OPEN_USER_ID) {
            return OPEN_USER_ID;
        }

        if (OPEN_ADMIN_ID) {
            return OPEN_ADMIN_ID;
        }

        if (OPEN_SUPERADMIN_ID) {
            return OPEN_SUPERADMIN_ID;
        }

        return null;
    }

    if (archiveBtn) {

        archiveBtn.addEventListener('click', () => {

            const convo_type = getOpenConvoType();
            const convo_ref_id = getOpenConvoRefId();
            
            if (!convo_type || !convo_ref_id) return;

            const isArchived = archiveBtn.dataset.archived === '1';
            const action = isArchived ? 'unarchive' : 'archive';
            const label = isArchived ? 'unarchive' : 'archive';

            if (!confirm(`${label.charAt(0).toUpperCase() + label.slice(1)} this conversation?`)) {
                return;
            }

            const body = new FormData();
            body.append('convo_type', convo_type);
            body.append('convo_ref_id', convo_ref_id);
            body.append('action', action);

            fetch('../../backend/messages/archive_conversation.php', { method: 'POST', body })
                
                .then(res => res.json())
                .then(data => {

                    if (data.success) {

                        if (isArchived) {
                            window.location.reload();
                        } else {
                            window.location.href = 'messages.php';
                        }
                    }
                })

        });
    }

    if (deleteBtn) {

        deleteBtn.addEventListener('click', () => {

            const convo_type = getOpenConvoType();
            const convo_ref_id = getOpenConvoRefId();

            if (!convo_type || !convo_ref_id) return;

            if (!confirm('Permanently delete this conversation? This cannot be undone.')) {
                return;
            }

            const body = new FormData();
            body.append('convo_type', convo_type);
            body.append('convo_ref_id', convo_ref_id);

            fetch('../../backend/messages/delete_conversation.php', { method: 'POST', body })
                
                .then(res => res.json())
                .then(data => {

                    console.log(data);

                    if (data.success) {
                        window.location.href = 'messages.php';
                    }
                })
                
            .catch(err => console.log(err));

        });
    }

});