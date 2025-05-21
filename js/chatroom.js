const socket = new WebSocket("ws://localhost:3000");

let messageTextarea = document.getElementById("message-textarea")
let messageContainer = document.getElementById("message-container")
let roomIdInput = document.getElementById("message-form-input")

// WebSocket: servernek küldés hogy melyik chatroomban vagyunk
socket.onopen = () => {
    let roomId = roomIdInput.value;
    socket.send(JSON.stringify({ type: "join_room", roomId: roomId }));
};

// WebSocket: a servertől küldött üzenet beillesztése
socket.onmessage = (event) => {
    let messageData = JSON.parse(event.data);

    let newMessage = document.createElement("div");
    newMessage.className = "message-left";
    newMessage.innerHTML = `
        <p class="p-left p-username">${messageData.username}</p>
        <div class="message-box message-box-left">
            <p>${messageData.message} <br><span class="time-span">${messageData.time}</span></p>
        </div>
    `;
    messageContainer.appendChild(newMessage);

    setTimeout(() => {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }, 200);
};

//betöltéskor ugrás az oldal aljára
window.addEventListener("load", function() {
    let messageContainer = document.getElementById("message-container");
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
});

//üzenetküldés
document.getElementById("message-form").addEventListener("submit", function(e) {
    e.preventDefault()

    let message = messageTextarea.value.trim()
    let roomId = roomIdInput.value

    if (message === "") return

    let xhr = new XMLHttpRequest()
    xhr.open("POST", baseURL + "/php/chatroom.php")
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")

    xhr.onload = function() {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText)
            let time = response.timestamp

            let newMessage = document.createElement("div")
            newMessage.className = "message-right"
            newMessage.innerHTML = `
                <p class="p-right p-username">You</p>
                <div class="message-box message-box-right">
                    <p>` + message + ` <br><span class="time-span">` + time + `</span></p>
                </div>
            `
            messageContainer.appendChild(newMessage)
            messageTextarea.value = ""
            setTimeout(() => {
                messageContainer.scrollTop = messageContainer.scrollHeight
            }, 200)

            // WebSocket: servernek üzenet elküldése
            if (socket.readyState === WebSocket.OPEN) {
                const messageData = {
                    type: "new_message",
                    roomId: roomId,
                    message: message,
                    time: time,
                    username: username
                };
                socket.send(JSON.stringify(messageData));
            }
        }
    }

    let params = "send_message=1&room_id=" + roomId + "&message=" + message
    xhr.send(params)
})
