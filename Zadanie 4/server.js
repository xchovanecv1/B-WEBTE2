var server = require('websocket').server, http = require('http');

var socket = new server({
    httpServer: http.createServer().listen(5500)
});

//sudo apt-get install nodejs npm
//nakopirovat subory index.php a server.js
//npm install

var counter = 0;
var clients = [];

socket.on('request', function(request) {
    var connection = request.accept(null, request.origin);
    clients[counter] = connection;
    connection.id = counter;
    counter++;

    connection.on('message', function(message) {
        console.log(message.utf8Data);

        for (index in clients){
            if(clients[index].id != connection.id){
                clients[index].send(JSON.stringify(message));
            }
        }
    });

    connection.on('close', function(connection) {
        delete clients[connection.id];
    });
}); 