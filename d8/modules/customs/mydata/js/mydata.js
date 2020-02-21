(function ($, Drupal, drupalSettings) {
    // init part
    // alert('init part');
    // console.log(Drupal.behaviors);
    // console.log(drupalSettings);

    // var user = drupalSettings.user;
    // console.log(user);

    var foo = drupalSettings.fluffiness.cuddlySlider;
    console.log(foo);


    // var io = require('socket.io');
    var socket = io('http://localhost:3000', {query:"platform=web"});
    console.log(socket);

    socket.on('connect', function(data) {
        socket.emit('chat_message', 'Hello World from client (web)');
        console.log('0000');
    });
    // alert('ab');
    // socket.on('connect', function(data) {
    //     // socket.emit('joined', 'Hello World from client');

    //     console.log(data);
    //     data.on('disconnect', () => {
    //         console.log(`Socket ${socket.id} disconnected.`);
    //     });
    // });
    // socket.on('acknowledge', function(data) {
    //     // alert(data);
    //     console.log(data);
    // });
    // $('form').submit(function(){
    //     socket.emit('chat message', $('#m').val());
    //     $('#messages').append($('<li>').text($('#m').val()));
    //     $('#m').val('');
    //     return false;
    // });
    // socket.on('response message', function(data) {
    //     $('#messages').append($('<li>').text(data));
    // });

    // var http = require('http');
    // var app = express();
    // var server = http.createServer(app);

    // var io = require('socket.io').listen(server);


})(jQuery, Drupal, drupalSettings);