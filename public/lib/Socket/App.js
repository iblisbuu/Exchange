var app = require('express')();
var server = require('http').createServer(app);
var io = require('socket.io')(server);

// https://www.zerocho.com/category/NodeJS/post/57edfcf481d46f0015d3f0cd
io.on('connection', function(socket) {
    socket.on('transfer', function (data) {
        var msg = {
            from: data.from,
            to: data.to,
            rbto: data.rbto,
            txid: data.txid,
            datetime: data.datetime,
            memo: data.memo
        };

        socket.broadcast.emit('getTransfer', msg);
    })
});

server.listen(8080, function() {
    console.log('Socket IO server listening on port 8080');
});