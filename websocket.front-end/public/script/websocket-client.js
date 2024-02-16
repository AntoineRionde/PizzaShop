const socket = new WebSocket('ws://localhost:3334');

socket.onopen = function () {
    console.log("Connexion au serveur Websocket");
};

socket.onmessage = function (event) {
    const messageContainer = document.getElementById('chat-messages');
    const newMessage = document.createElement('p');
    newMessage.textContent = ">>> " + event.data;
    messageContainer.appendChild(newMessage);
};

socket.onerror = function (error) {
    console.error('WebSocket error :', error);
};

socket.onclose = function () {
    console.log('WebSocket connexion closed');
};

document.getElementById('chat-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const inputField = document.getElementById('msg');
    const message = inputField.value;

    if (message) {
        const messageObject = { orderId: message };
        socket.send(JSON.stringify(messageObject));
        console.log(messageObject);
    } else {
        const messageContainer = document.getElementById('chat-messages');
        const newMessage = document.createElement('p');
        newMessage.textContent = ">>> " + "Message can't be empty";
        messageContainer.appendChild(newMessage);
    }

    inputField.value = '';
});