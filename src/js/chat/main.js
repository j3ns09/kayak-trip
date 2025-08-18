const toggleBtn = document.getElementById('chat-toggle');
const chatWindow = document.getElementById('chat-window');
const closeBtn = document.getElementById('chat-close');
const sendBtn = document.getElementById('chat-send');
const input = document.getElementById('chat-input');
const messages = document.getElementById('chat-messages');

let activeThread;
let allMessages;

document.addEventListener('DOMContentLoaded', async () => {    
    toggleBtn.addEventListener('click', () => chatWindow.classList.toggle('d-none'));
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

    console.log(allMessages);
    renderMessages(allMessages);
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
        alert("Vous devez être connecté pour voir les messages.");
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
        alert("Vous devez être connecté pour envoyer un message.");
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

    messagesArray.forEach((msg) => {
        const msgDiv = document.createElement('div');

        const isClient = msg.sender_type === 'client';
        msgDiv.className = `mb-2 text-${isClient ? 'end' : 'start'}`;
        msgDiv.innerHTML = `
            <span class="badge bg-${isClient ? 'success' : 'secondary'}">${msg.message}</span>
        `;

        messages.appendChild(msgDiv);
    });

    messages.scrollTop = messages.scrollHeight;
}
