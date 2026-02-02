const chatBubble = document.getElementById('chatBubbleID');
const chatBox = document.getElementById('chatBoxID');
const chatCloseBtn = document.getElementById('chatCloseID');
const chatSendBtn = document.getElementById('chatSendBtn');
const chatInputField = document.getElementById('chatInputField');
const chatMessages = document.getElementById('chatMessagesID');


chatBubble.addEventListener('click', function(e) {
    e.preventDefault();
    chatBox.classList.add('show');

    
});

chatCloseBtn.addEventListener('click', function() {
    chatBox.classList.remove('show');
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('.chatWidget')) { 

        chatBox.classList.remove('show'); 
    }
});



chatSendBtn.addEventListener('click', sendMessage);

chatInputField.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Send message function
function sendMessage() {
    const messageText = chatInputField.value.trim();
    
    if (messageText !== '') {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'chatMessage chatMessageRight';
        
        messageDiv.innerHTML = `
            <div class="chatMessageAvatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="chatMessageBubble">
                <p>${messageText}</p>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        
        chatInputField.value = '';
        
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        setTimeout(function() {
            autoReply();
        }, 1000);
    }
}

// sample reply(auto)
function autoReply() {
    const replies = [
        "Thank you for your message! A support agent will be with you shortly.",
        "We've received your inquiry. How else can we help?",
        "Thanks! Is there anything else you'd like to know?",
        "Got it! Let me check that for you."
    ];
    
    const randomReply = replies[Math.floor(Math.random() * replies.length)];
    
    const replyDiv = document.createElement('div');
    replyDiv.className = 'chatMessage chatMessageLeft';
    
    replyDiv.innerHTML = `
        <div class="chatMessageAvatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="chatMessageBubble">
            <p>${randomReply}</p>
        </div>
    `;
    
    chatMessages.appendChild(replyDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}