import io from 'socket.io-client';

let socket = 'ttti';

export function connect_socketIO(uid){
    console.log(uid);
    console.log(socket);
    if(!socket.connected){
      socket = io('/', {query:"platform=web&uid=" . uid })
      socket.on('chat_message', (messageNew) => {
        console.log(messageNew);
        // temp.push(messageNew)
        // this.setState({ message: temp })
      })
      socket.on('FromAPI', (messageNew) => {
        console.log(messageNew);
        // temp.push(messageNew)
        // this.setState({ message: temp })
      })
    }
    
    return true;
}

export function disconnect_socketIO(){
  if(socket.connected){
    socket.disconnect()
  }
}

// export function headers() {
//     return { 'Content-Type': 'application/json' };
// }