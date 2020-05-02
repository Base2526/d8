import io from 'socket.io-client';

let socket;

export function connect_socketIO(props){
    let {user} = props

    console.log(props);
    if (typeof(socket) === 'undefined' || socket == null || !socket.connected ) {
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

      socket.on('update_user', (data) => {

        let new_user = {...user, ...JSON.parse(data)};
        props.userUpdate(new_user);

        console.log(new_user);

        // props.loadingOverlayActive(true);
        // setInterval(()=>{
        //   props.loadingOverlayActive(false);
        // },10000) 
      })
 
      socket.on('huay_list_bank', (data) => {
        console.log(JSON.parse(data));
        props.updateHuayListBank(JSON.parse(data));
      })
      socket.on('transfer_method', (data) => {
        console.log(JSON.parse(data));
        props.updateTransferMethod(JSON.parse(data));
      })
      socket.on('contact_us', (data) => {
        console.log(JSON.parse(data));
        props.updateContactUs(JSON.parse(data)[0]);
      })
      // 
      socket.on('list_bank', (data) => {
        console.log(JSON.parse(data));
        props.updateListBank(JSON.parse(data));
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