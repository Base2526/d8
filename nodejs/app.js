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

const fetch = require('node-fetch');

const bodyParser = require('body-parser');

const connectDb = require("./src/connection");
const User = require("./src/User.model");

var config = require("./src/utils/config")

const app   = require('express')();
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));


const Product = require('./models/product')

const mongo = require('mongodb').MongoClient

app.get('/api/hello', (req, res) => {
  res.send({ express: 'Hello From Express' });
});

app.get('/', async (req, res) => {

  // console.log(mongo)

  var payload = {
                  "name": "My Product from Postman",
                  "category": "Tool",
                  "price": 0,
                  "tags": ["test1", "test2", "tag1"]
                };
  const product = new Product(payload)
  await product.save()

  res.send('OOP Chat Server is running on port 8080');

  // fetch('http://localhost:8055/api/login.json', {method: 'GET', body: 'a=1'})
	// .then(res => res.json()) // expecting a json response
  // .then(json => console.log(json));
  
  
});

app.post('/api/login', (req, res) => {
  console.log(req.body);
  console.log(config.d8.headers);
  var data = {
    "name": req.body.name,
    "pass": req.body.pass
  }
  fetch(config.d8.api_login, { method: 'POST', headers: config.d8.headers, body: JSON.stringify(data)})
    .then((res) => {
      return res.json()
  })
  .then((json) => {
    console.log(json);
    // Do something with the returned data.

    res.send(json);
  });
});

app.post('/api/list_bank', (req, res) => {
  console.log(req.body);
  console.log(config.d8.headers);
  var data = {}

  fetch(config.d8.api_list_bank, { method: 'POST', headers: config.d8.headers, body: JSON.stringify(data)})
    .then((res) => {
      return res.json()
  })
  .then((json) => {
    console.log(json);
    // Do something with the returned data.

    res.send(json);
  });
});

app.post('/api/add_bank', (req, res) => {
  console.log(req.body);
  console.log(config.d8.headers);
  var data = {
    "uid": req.body.uid,
    "tid_bank": req.body.tid_bank,
    "name_bank": req.body.name_bank,
    "number_bank": req.body.number_bank,
  }

  fetch(config.d8.api_add_bank, { method: 'POST', headers: config.d8.headers, body: JSON.stringify(data)})
    .then((res) => {
      return res.json()
  })
  .then((json) => {
    console.log(json);
    // Do something with the returned data.

    res.send(json);
  });
});

app.post('/api/delete_bank', (req, res) => {
  console.log(req.body);
  console.log(config.d8.headers);
  var data = {
    "uid": req.body.uid,
    "target_id": req.body.target_id,
  }

  fetch(config.d8.api_delete_bank, { method: 'POST', headers: config.d8.headers, body: JSON.stringify(data)})
    .then((res) => {
      return res.json()
  })
  .then((json) => {
    console.log(json);
    // Do something with the returned data.

    res.send(json);
  });
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

// var request = require('request');
io.on('connection', (socket) => { 
  let handshake = socket.handshake;
  console.log(socket);
  console.log(handshake);
  console.log(socket.id);
  console.log(handshake.time);
  console.log(handshake.query);
  console.log(`Socket ${socket.id} connected.`);

  // console.log("user-agent: "+socket.request.headers['user-agent']);
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
    // console.log(msg);
    // io.emit("chat_message", 'bbu');

    // socket.authenticated = true;

    io.sockets.in('room1').emit('response_message', 'what is going on, party people?');
  });

  socket.on('create', function(room) {
    socket.join(room);
    console.log(this);
  });

  interval = setInterval(() => getApiAndEmit(socket), 10000);
  
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


const getApiAndEmit = socket => {
  const response = new Date();
  // Emitting a new message. Will be consumed by the client
  socket.emit("FromAPI", response);
};

// io.on('disconnect', () => {
//   console.log("disconnection");
// });
// server.listen(PORT);
server.listen(PORT, function (err) {
  console.log('listening on port 8080')

  connectDb().then((db) => {
    console.log("MongoDb connected");

    console.log(db);

    // (new Product).watch().on('change', data => console.log(new Date(), data));

    // var conn = db.connection;
    // var ObjectID = require('mongodb').ObjectID;

    // var user = {
    //   a: 'abc',
    //   _id: new ObjectID()
    // };
    // conn.collection('superheroes').insert(user);

    // const collection = db.collection("superheroes");
    // db.collection("sample_collection").insertOne({
    //   field1: "abcderrr"
    // }, (err, result) => {
    //     if(err) console.log(err);
    //     else console.log(result.ops[0].field1)
    // });

    // var dbo = db.db("mydb");
    // var myobj = { name: "Company Inc", address: "Highway 37" };
    // dbo.collection("customers").insertOne(myobj, function(err, res) {
    //   if (err) throw err;
    //   console.log("1 document inserted");
    //   db.close();
    // });
  });

})
