const WebSocket = require('ws');

const wss = new WebSocket.Server({ port: 3000 });

const rooms = new Map(); // Melyik szobákban kik vannak online jelenleg
const clientRooms = new Map(); // Ki melyik szobában online

wss.on('connection', (ws) => {
    ws.on('message', (msg) => {
        let data = JSON.parse(msg);

        if (data.type === "join_room") {
            const roomId = data.roomId;
            if (!rooms.has(roomId)) {
                rooms.set(roomId, new Set());
            }
            rooms.get(roomId).add(ws);
            clientRooms.set(ws, roomId);
        }

        if (data.type === "new_message") {
            const roomId = clientRooms.get(ws);
            if (!roomId) return;

            const clientsInRoom = rooms.get(roomId);

            for (let i of clientsInRoom) {
                if (i !== ws && i.readyState === WebSocket.OPEN) {
                    i.send(JSON.stringify({
                        message: data.message,
                        username: data.username,
                        time: data.time
                    }));
                }
            }
        }
    });

    ws.on('close', () => {
        const roomId = clientRooms.get(ws);
        if (roomId && rooms.has(roomId)) {
            rooms.get(roomId).delete(ws);
            if (rooms.get(roomId).size === 0) {
                rooms.delete(roomId);
            }
        }
        clientRooms.delete(ws);
    });
});
