const express = require('express');
const app = express();
const server = require('http').createServer(app);
const io = require('socket.io')(server, {
    cors: { origin: "*" }
});

io.use((socket, next) => {
    socket.voteID = socket.handshake.query.voteID;
    next();
});

io.on('connection', (socket) => {

    console.log('A user connected with voteID:', socket.voteID);

    socket.join(socket.voteID);

    socket.on('pollVoteServer', (chooseID) => {
        io.to(socket.voteID).emit('pollVoteClient', chooseID);
    });

    socket.on('disconnect', (socket) => {
        console.log('Disconnected msg');
    });

});

server.listen(3000, () => {
    console.log('Server is running');
});
