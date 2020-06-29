import io from 'socket.io-client';

let socket;

export function connect_socketIO(props){
    let {user} = props

    console.log(props);
    if (typeof(socket) === 'undefined' || socket == null || !socket.connected ) {
      console.log(user);
      socket = io('/', { query:"platform=web&uid=" + user.uid + "&session=" + user.session })

      if (socket.connected === false && socket.connecting === false) {
        // use a connect() or reconnect() here if you want
        socket.connect()
        console.log('reconnected!');
      }else{
        socket.on('connect', () => {
          console.log('Successfully connected!');
        });
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
        // 
        // socket.on('yeekee_round', (data) => {
        //   console.log(JSON.parse(data));
        //   props.updateYeekeeRound(JSON.parse(data));
        // })
  
        socket.on('lotterys', (data) => {
          console.log(JSON.parse(data));
          props.updateLotterys(JSON.parse(data));
        })
  
        socket.on('shoot_numbers', (data) => {
          console.log(JSON.parse(data));
          props.updateShootNumbers(JSON.parse(data));
        })

        // deposit_status
        socket.on('deposit_status', (data) => {
          console.log(JSON.parse(data));
          props.updateDepositStatus(JSON.parse(data));
        })

        socket.on('force_logout', (data) => {
          console.log(JSON.parse(data));
          props.userLogout();
        })
      }
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