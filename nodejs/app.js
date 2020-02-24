// refer : https://github.com/nathanpeck/socket.io-chat-fargate/blob/master/services/client/server.js#L40

// const express = require("express");
// const app = express();
// const connectDb = require("./src/connection");
// const User = require("./src/User.model");

const PORT = 3000;

/*
app.get('/', (req, res) => {
  res.send('Chat Server is running on port 8080');
});

app.get("/users", async (req, res) => {
  console.log(`/user`);
  const users = await User.find();

  res.json(users);
});

app.get("/user-create", async (req, res) => {
  console.log(`/user-create`);
  const user = new User({ username: "userTest" });

  await user.save().then(() => console.log("User created"));

  res.send("User created \n");
});

app.listen(PORT, function() {
  console.log(`Listening on ${PORT}`);

  connectDb().then(() => {
    console.log("MongoDb connected");
  });
});
*/

const connectDb = require("./src/connection");
const User = require("./src/User.model");

const app   = require('express')();
app.get('/', (req, res) => {
  res.send('OOP Chat Server is running on port 8080');
});

app.get("/users", async (req, res) => {
  console.log(`/user`);
  const users = await User.find();

  res.json(users);
});

app.get("/user-create", async (req, res) => {
  console.log(`/user-create`);
  const user = new User({ username: "userTest" });

  await user.save().then(() => console.log("User created"));

  res.send("User created \n");
});

const http  = require('http');
const server= http.createServer(app);
let io = require('socket.io')(server);

var request = require('request');
io.on('connection', (socket) => { 
  let handshake = socket.handshake;
  console.log(socket);
  console.log(handshake);
  console.log(socket.id);
  console.log(handshake.time);
  console.log(handshake.query);
  console.log(`Socket ${socket.id} connected.`);

  console.log("user-agent: "+socket.request.headers['user-agent']);


  // request('http://localhost/rest/api/get?_format=json', function (error, response, body) {
  //     // if (!error && response.statusCode == 200) {
  //         console.log(response) // Print the google web page.
  //     // }
  // })

  // request.get("http://www.google.com", (error, response, body) => {
  //     if(error) {
  //       // return console.dir(error);
  //       console.log(error) 
  //     }
  //     // console.dir(JSON.parse(body));

  //     console.log(body) 
  // });

  // request('http://web/rest/api/get?_format=json', function (error, response, body) {
  //      // if (!error && response.statusCode == 200) {
  //      console.log(response) // Print the google web page.
  //      console.log(body)
  //      // }
  // })

  socket.conn.on('heartbeat', function() {
    // console.log('#1');
    if (!socket.authenticated) {
      // Don't start counting as present until they authenticate.
      return;
    }

    console.log('#2');
    //Presence.upsert(socket.id, {
    //  username: socket.username
    //});
  });

  /*
  สร้าง event ไว้รอรับข้อความจาก react-native
  */
  socket.on("chat_message", msg => {
    console.log(msg);
    // io.emit("chat_message", 'bbu');

    io.sockets.in('room1').emit('response_message', 'what is going on, party people?');
  });

  socket.on('create', function(room) {
    socket.join(room);
    console.log(this);
  });
  
  socket.on('disconnect', () => {
    
    console.log(`Socket ${socket.id} disconnected.`);
  });

  socket.on("get_list_of_clients_in_specific_room", msg => {

    console.log(io.sockets.connected);
    // var roster = io.sockets.clients('room1');

    // roster.forEach(function(client) {
    //     console.log('Username: ' + client.nickname);
    // }); 
  });


  /*
  
  */
});

// io.on('disconnect', () => {
//   console.log("disconnection");
// });
// server.listen(PORT);
server.listen(PORT, function (err) {
  console.log('listening on port 8080')

  connectDb().then(() => {
    console.log("MongoDb connected");
  });
})
