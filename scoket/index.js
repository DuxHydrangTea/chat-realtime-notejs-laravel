import express from 'express';
import http from 'http';
import { Server } from 'socket.io';

const app = express();
const server = http.createServer(app)

const io = new Server(server, {
    cors: {
        origin: "http://localhost:8000",
        methods: ["GET", "POST"],
    }
})

io.on('connection', socket => {
    console.log('connection');
    socket.on('send', data => {
        console.log(data);
    })

    socket.on('private-send', ({sendUserId, recieveUserId, message}) => {
        console.log({sendUserId, recieveUserId, message});
        socket.broadcast.emit(`message_from_${sendUserId}_to_${recieveUserId}`, {sendUserId, recieveUserId, message})
    })
    
})

const port = 3000;
server.listen(port, () => {
    console.log('server is running... in port: ' + port);
})