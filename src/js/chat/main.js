const toggleBtn = document.getElementById('chat-toggle');
const chatWindow = document.getElementById('chat-window');
const closeBtn = document.getElementById('chat-close');
const sendBtn = document.getElementById('chat-send');
const input = document.getElementById('chat-input');
const messages = document.getElementById('chat-messages');

let pollingInterval;
let lastMessageTimestamp = null;

let activeThread;
let allMessages;

document.addEventListener('DOMContentLoaded', async () => {    
    const chatButton = document.getElementById('chat-button');
    if (chatButton) {
        chatButton.addEventListener('click', () => chatWindow.classList.remove('d-none'));
    }
    
    toggleBtn.addEventListener('click', () => {
        chatWindow.classList.toggle('d-none');
        if (chatWindow.classList.contains('d-none')) {
            clearInterval(pollingInterval);
        } else {
            pollMessages();
            pollingInterval = setInterval(pollMessages, 3000);
        }
    });
    
    closeBtn.addEventListener('click', () => chatWindow.classList.add('d-none'));
    
    sendBtn.addEventListener('click', () => {
        sendMessage();
    });
    
    input.addEventListener('keypress', (e) => {
        if (e.key === "Enter") {
            sendMessage();
        }
    });

    activeThread = await getActiveThread();
    allMessages = await getMessagesAPI(userId, activeThread);

    if (allMessages) renderMessages(allMessages);
});

async function getActiveThread() {
    if (userId === null) {
        console.warn("Utilisateur non connecté");
        return false;
    }
    
    const response = await fetch(`/api/threads/?userId=${userId}`, {
        method: "GET",
        headers: {
            'Content-Type': 'application/json'
        }
    });

    const data = await response.json();

    if (data.ok) {
        return data.threadId;
    }
    return false;
}

async function getNewThread() {
    if (userId === null) {
        console.warn("Utilisateur non connecté");
        return false;
    }
    
    const response = await fetch("/api/threads/", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ userId })
    });

    const data = await response.json();

    if (data.ok) {
        return data.threadId;
    }
    return false;
}

async function pollMessages() {
    if (!activeThread || !userId) return;

    try {
        const response = await fetch(`/api/messages/?userId=${userId}&threadId=${activeThread}`);
        const data = await response.json();

        if (data.ok && Array.isArray(data.messages)) {
            // Ne ré-affiche que si nouveaux messages
            const newMessages = data.messages;

            // Option : ne re-render que si on détecte du nouveau
            if (JSON.stringify(newMessages) !== JSON.stringify(allMessages)) {
                allMessages = newMessages;
                renderMessages(allMessages);
            }
        }
    } catch (e) {
        console.error("Erreur lors du polling :", e);
    }
}


function showMessage(content) {
    const msgDiv = document.createElement('div');
    msgDiv.className = "text-end mb-2";
    msgDiv.innerHTML = `<span class="badge bg-success">${content}</span>`;
    messages.appendChild(msgDiv);
}

function sanitizeMessage() {
    const msg = input.value.trim();
    if (msg.length > 100 || msg.length < 1) return false;
    return msg;
}

async function getMessagesAPI(userId, threadId) {
    if (userId === null) {
        // alert("Vous devez être connecté pour voir les messages.");
        return false;
    }

    const response = await fetch(`/api/messages/?userId=${userId}&threadId=${threadId}`, {
        method: "GET",
        headers: {
            'Content-Type': 'application/json'
        }
    });

    const data = await response.json();

    if (data.ok) {
        return data.messages || [];
    }

    console.error("Erreur de chargement des messages:", data);
    return [];
}

async function postMessageAPI(userId, message, threadId=-1) {
    if (userId === null) {
        // alert("Vous devez être connecté pour envoyer un message.");
        return false;
    }

    const response = await fetch("/api/messages/", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            userId,
            threadId,
            message,
        })
    });

    const r = await response.json();

    return r.ok;
}

async function sendMessage() {
    let thread = activeThread;
    
    if (!thread) {
        thread = await getNewThread();
        activeThread = thread;
    }

    const msg = sanitizeMessage();
    if (!msg) return;

    if (await postMessageAPI(userId, msg, thread)) {
        showMessage(msg);
        input.value = '';
        messages.scrollTop = messages.scrollHeight;
    }
}

function renderMessages(messagesArray) {
    messages.innerHTML = '';
    console.log(messagesArray);

    messagesArray.forEach((msg) => {
        const msgDiv = document.createElement('div');

        const isMe = msg.sender_id === userId;
        msgDiv.className = `mb-2 text-${isMe ? 'end' : 'start'}`;
        msgDiv.innerHTML = `
            <span class="badge bg-${isMe ? 'success' : 'secondary'}">${msg.message}</span>
        `;

        messages.appendChild(msgDiv);
    });

    messages.scrollTop = messages.scrollHeight;
}
