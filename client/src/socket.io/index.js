import io from 'socket.io-client';

let socket = 'ttti';

export function connect_socketIO(user){
    console.log(user);
    console.log(socket);
    if(!socket.connected){
      // this.props.user
      socket = io('/', { query:"platform=web&uid=" + user.uid})
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