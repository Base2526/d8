
var socket;



(function ($, Drupal, drupalSettings) {
    // init part
    // alert('init part');
    // console.log(Drupal.behaviors);
    // console.log(drupalSettings);

    // function searchsomething(){
    //     alert("hello");
    // }

   


    var user = drupalSettings.user;
    console.log(user);

    if(user.uid > 0){
        console.log(drupalSettings);
        console.log(drupalSettings.path);

        var currentPath = drupalSettings.path.currentPath
        console.log(currentPath);

        var configs = drupalSettings.configs;
        console.log(configs);
    
        // var io = require('socket.io');
        console.log(socket);
        socket = io(configs.nodejs_url, {query:"platform=web&uid=" + user.uid});
        console.log(socket);
        
    
        socket.on('connect', function(data) {
            // socket.emit('chat_message', 'Hello World from client (web)');
            // console.log(socket);

            console.log(socket.id);

            // socket.emit('create', 'room1');
        });

        socket.on('response_message', function(data) {
            console.log(data);
        });
    }else{
        console.log('user anonymous');
    }

    // $('#edit-clear').on("click", function() {
    //     // $('.tableFilter .fieldset-wrapper').toggle('fast', 'swing');
    //     alert('edit-clear');
    // });

    // $( "#edit-clear" ).click(function() {
    //     alert( "Handler for .click() called." );
    // });

    // Drupal.behaviors.mydata = {
    //     attach: function (context, settings) {
      
    //       // Attach a click listener to the clear button.
    //       var clearBtn = document.getElementById('edit-clear');
    //       clearBtn.addEventListener('click', function() {
      
    //           // Do something!
    //           console.log('Clear button clicked!');
      
    //       }, false);
      
    //     }
    //   };

    // Attach a click listener to the clear button.
    // var clearBtn = document.getElementById('edit-chect-socketio');
    // clearBtn.addEventListener('click', function() {

    //     // Do something!
    //     hideShowTotalCreditLimitCreditTerm();

    // }, false);

    let chect_socketio = $('#edit-chect-socketio');
    let create_room     = $('#edit-create-room');

    let list_client = $('#edit-list-client');
    let send_message_to_client = $('#edit-send-message-to-client');

    chect_socketio.on("click", function() {
        chectSocketIO();
    });

    create_room.on("click", function() {
        createRoom();
    });

    list_client.on("click", function() {
        listClient();
    });

    send_message_to_client.on("click", function() {
        sendMessageToClient();
    });

    function chectSocketIO() {
        console.log('chect_socketio');
    }

    function createRoom(){        
        // console.log(socket);
        if (socket !== undefined) {
            socket.emit('create', 'room2');
            console.log('create_room');
        }else{
            console.log('socket === undefined');
        }
    }

    function listClient(){ 
        socket.emit('get_list_of_clients_in_specific_room', 'from client');
    }

    function sendMessageToClient(){
        // console.log('sendMessageToClient');

        if (socket !== undefined) {
            socket.emit('chat_message', 'from client');
            // console.log('chat_message');
        }else{
            console.log('socket === undefined');
        }
    }

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