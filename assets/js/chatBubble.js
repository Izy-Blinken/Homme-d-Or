const chatBubble = document.getElementById('chatBubbleID');
const chatBox = document.getElementById('chatBoxID');
const chatCloseBtn = document.getElementById('chatCloseID');
const chatMessages = document.getElementById('chatMessagesID');

const IS_LOGGED_IN = typeof USER_ID !== 'undefined' && USER_ID !== null;

const faqs = [
    {
        question: 'How do I choose Cash on Delivery (COD) as a payment option?',
        answer: 'During checkout, select "Cash on Delivery" from the payment options. This is available for all orders within our delivery areas. Payment is collected upon delivery.',
    },
    {
        question: 'How can I change my profile details?',
        answer: 'Go to your Profile page by clicking the user icon on the top right. From there, you can update your name, email, phone number, and address.',
    },
    {
        question: 'How do I cancel my order?',
        answer: 'You can cancel your order from the My Orders page as long as it has not been shipped yet. Go to your order, click "Cancel Order," and select a reason.',
    },
    {
        question: 'How do I change my password?',
        answer: 'Go to your Profile page and click Edit Profile, from there, you will see the button, "Change Password". You will need to enter your current password and your new password.',
    },
];

let sessionId = null;
let escalated = false;

function appendMessage(content, side, isOptions = false) {

    const msg = document.createElement('div');
    msg.className = `chatMessage chatMessage${side === 'left' ? 'Left' : 'Right'}`;

    if (side === 'left') {
        msg.innerHTML = `
            <div class="chatMessageAvatar"><i class="fa-solid fa-user"></i></div>
            <div class="chatMessageBubble"><p>${content}</p></div>
        `;

    } else {
        msg.innerHTML = `
            <div class="chatMessageBubbleUser"><p>${content}</p></div>
        `;
    }

    chatMessages.appendChild(msg);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function appendOptions() {

    const wrap = document.createElement('div');
    wrap.className = 'chatMessage chatMessageLeft';
    wrap.id = 'chat-options-wrap';

    const bubble = document.createElement('div');
    bubble.className = 'chatMessageBubble';
    bubble.style.maxWidth = '90%';

    bubble.innerHTML = `<p style="margin-bottom:0.5rem; font-weight:600;">You may want to ask:</p>`;

    faqs.forEach((faq, i) => {

        const btn = document.createElement('button');

        btn.textContent = faq.question;
        btn.className = 'chat-option-btn';
        btn.style.cssText = 'display:block; width:100%; text-align:left; background:#e0e7ff; border:none; padding:8px 12px; margin-bottom:6px; cursor:pointer; font-size:13px; color:rgb(5,17,112); transition:background 0.2s;';
        btn.addEventListener('mouseenter', () => btn.style.background = '#c7d2fe');
        btn.addEventListener('mouseleave', () => btn.style.background = '#e0e7ff');
        btn.addEventListener('click', () => handleFaqClick(i));
        bubble.appendChild(btn);
    });

    const talkBtn = document.createElement('button');

    talkBtn.textContent = 'Talk to customer service.';
    talkBtn.className = 'chat-option-btn';
    talkBtn.style.cssText = 'display:block; width:100%; text-align:left; background:rgb(5,17,112); border:none; padding:8px 12px; cursor:pointer; font-size:13px; color:white; transition:background 0.2s;';
    talkBtn.addEventListener('mouseenter', () => talkBtn.style.background = 'rgb(17,21,49)');
    talkBtn.addEventListener('mouseleave', () => talkBtn.style.background = 'rgb(5,17,112)');
    talkBtn.addEventListener('click', handleEscalate);
    bubble.appendChild(talkBtn);

    wrap.appendChild(bubble);
    chatMessages.appendChild(wrap);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function handleFaqClick(index) {

    const faq = faqs[index];

    appendMessage(faq.question, 'right');

    // save to chatbot_logs if session exists
    if (sessionId) {
        saveChatLog(faq.question, 'user');
    }

    setTimeout(() => {
        appendMessage(faq.answer, 'left');
        if (sessionId) saveChatLog(faq.answer, 'bot');
    }, 600);

}

function handleEscalate() {

    appendMessage('Talk to customer service.', 'right');

    if (!IS_LOGGED_IN) {

        setTimeout(() => {

            appendMessage('Please log in to connect with our customer service team.', 'left');
            
            setTimeout(() => {

                const loginBtn = document.createElement('div');
                loginBtn.className = 'chatMessage chatMessageLeft';
                loginBtn.innerHTML = `
                    <div class="chatMessageAvatar"><i class="fa-solid fa-user"></i></div>
                    <div class="chatMessageBubble">
                        <button onclick="openLoginModal()" style="background:rgb(5,17,112); color:white; border:none; padding:8px 16px; cursor:pointer; font-size:13px;">Login</button>
                    </div>
                `;
                chatMessages.appendChild(loginBtn);
                chatMessages.scrollTop = chatMessages.scrollHeight;

            }, 400);

        }, 600);

        return;
    }

    // kapag verified(nakalogin), escalate na from bot to admin
    setTimeout(() => {

        appendMessage('Connecting you to a customer service representative. Please wait while an agent is assigned to you.', 'left');

        fetch('/homme_dor/backend/chat/escalate.php', {

            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ session_id: sessionId })
        })

        .then(res => res.json())
        .then(data => {

            if (data.success) {
                escalated = true;
                sessionId = data.session_id;
                enableChatInput();
            }
        })
        .catch(() => {});

    }, 600);
}

function enableChatInput() {

    const input = document.getElementById('chatInputField');
    const sendBtn = document.getElementById('chatSendBtn');

    input.disabled = false;
    input.placeholder = 'Type your message...';

    sendBtn.onclick = () => sendLiveMessage();
    input.onkeypress = (e) => { if (e.key === 'Enter') sendLiveMessage(); };
}

function sendLiveMessage() {

    const input = document.getElementById('chatInputField');
    const text = input.value.trim();
    
    if (!text) return;

    appendMessage(text, 'right');
    input.value = '';

    fetch('/homme_dor/backend/chat/send_customer_message.php', {

        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: sessionId, content: text })
    })

    .then(res => res.json())
    .catch(() => {});

    // polling for admin reply
    pollMessages();
}

let lastMsgId = 0;
let pollingInterval = null;

function pollMessages() {

    if (pollingInterval) return;

    pollingInterval = setInterval(() => {

        if (!sessionId) return;

        fetch(`/homme_dor/backend/chat/get_customer_messages.php?session_id=${sessionId}&last_id=${lastMsgId}`)
            .then(res => res.json())
            .then(data => {

                if (data.messages && data.messages.length > 0) {

                    data.messages.forEach(m => {
                        appendMessage(m.content, 'left');
                        lastMsgId = m.message_id;
                    });

                }
            })
        .catch(() => {});

    }, 4000);
}

function saveChatLog(message, sender) {

    fetch('/homme_dor/backend/chat/save_log.php', {

        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: sessionId, sender, message })
    })

    .catch(() => {});
}

function initChat() {

    if (!IS_LOGGED_IN) {
        appendMessage('Hi! How can we help you today?', 'left');
        appendOptions();
        return;
    }

    // create or resume session
    fetch('/homme_dor/backend/chat/init_session.php', { method: 'POST' })
        .then(res => res.json())
        .then(data => {

            if (data.session_id) {

                sessionId = data.session_id;
                escalated = data.escalated;

                if (escalated) {

                    fetch(`/homme_dor/backend/chat/get_customer_messages.php?session_id=${data.session_id}&history=1`)
                        .then(res => res.json())
                        .then(hist => {

                            if (hist.messages && hist.messages.length > 0) {

                                hist.messages.forEach(m => {

                                    if (m.sender_type === 'user') {
                                        appendMessage(m.content, 'right');
                                    } else if (m.sender_type === 'bot') {
                                        appendMessage(m.content, 'left');
                                    } else {
                                        appendMessage(m.content, 'left');
                                        lastMsgId = m.message_id ?? lastMsgId;
                                    }

                                });

                            } else {
                                appendMessage('You are connected to a customer service representative.', 'left');
                            }

                            enableChatInput();
                            pollMessages();

                        })
                        .catch(() => {
                            appendMessage('You are connected to a customer service representative.', 'left');
                            enableChatInput();
                            pollMessages();
                        });

                } else {
                    appendMessage('Hi! How can we help you today?', 'left');
                    appendOptions();
                }
            }
        })

        .catch(() => {
            appendMessage('Hi! How can we help you today?', 'left');
            appendOptions();
        });
}

// toggle chat
chatBubble.addEventListener('click', (e) => {

    e.preventDefault();
    e.stopPropagation();

    if (!chatBox.classList.contains('show')) {

        chatBox.classList.add('show');

        if (chatMessages.children.length === 0) {
            initChat();
        }

    } else {
        chatBox.classList.remove('show');
    }
});

chatCloseBtn.addEventListener('click', () => chatBox.classList.remove('show'));

document.addEventListener('click', (e) => {
    
    if (!e.target.closest('.chatWidget')) {
        chatBox.classList.remove('show');
    }
});