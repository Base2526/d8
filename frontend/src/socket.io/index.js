import io from 'socket.io-client';

var socket = undefined;
export function socketIO(user){
  if (typeof(socket) === 'undefined' || socket == null || !socket.connected ) {
    socket = io('/', { query:"platform=web&uid=" + user.uid + "&session=" + user.session })

    if (socket.connected === false && socket.connecting === false) {
      // use a connect() or reconnect() here if you want
      socket.connect()
      console.log('reconnected!');
    }
  }

  return socket;
}

export function connect_socketIO(props){
    let {user} = props

    let isocket = socketIO(user);
    if (typeof(isocket) !== 'undefined' || isocket == null || !isocket.connected ) {
      isocket.on('connect', () => {
        console.log('Socket io, connected!');

        props.updateSocketIOStatus({status: true});
      });

      isocket.on('disconnect', function(){
        console.log('Socket io, disconnect!');

        // props.updateSocketIOStatus({status: false});
      });

      isocket.on('chat_message', (messageNew) => {
        console.log(messageNew);
        // temp.push(messageNew)
        // this.setState({ message: temp })
      })
      isocket.on('FromAPI', (messageNew) => {
        console.log(messageNew);
        // temp.push(messageNew)
        // this.setState({ message: temp })
      })

      isocket.on('update_user', (data) => {

        let new_user = {...user, ...JSON.parse(data)};
        props.userUpdate(new_user);

        console.log(new_user);

        // props.loadingOverlayActive(true);
        // setInterval(()=>{
        //   props.loadingOverlayActive(false);
        // },10000) 
      })
  
      isocket.on('huay_list_bank', (data) => {
        console.log(JSON.parse(data));
        props.updateHuayListBank(JSON.parse(data));
      })
      isocket.on('transfer_method', (data) => {
        console.log(JSON.parse(data));
        props.updateTransferMethod(JSON.parse(data));
      })
      isocket.on('contact_us', (data) => {
        console.log(JSON.parse(data));
        props.updateContactUs(JSON.parse(data)[0]);
      })
      // 
      isocket.on('list_bank', (data) => {
        console.log(JSON.parse(data));
        props.updateListBank(JSON.parse(data));
      })
      // 
      // socket.on('yeekee_round', (data) => {
      //   console.log(JSON.parse(data));
      //   props.updateYeekeeRound(JSON.parse(data));
      // })

      isocket.on('lotterys', (data) => {
        console.log(JSON.parse(data));
        props.updateLotterys(JSON.parse(data));
      })

      // isocket.on('shoot_numbers', (data) => {
      //   console.log(JSON.parse(data));

      //   console.log('Socket io, connected! > ChitPage-8000');
      //   // props.updateShootNumbers(JSON.parse(data));
      // })

      // deposit_status
      isocket.on('deposit_status', (data) => {
        console.log(JSON.parse(data));
        props.updateDepositStatus(JSON.parse(data));
      })

      isocket.on('force_logout', (data) => {
        console.log(JSON.parse(data));
        props.userLogout();
      })

      isocket.on('clear_cache', (data) => {
        console.log('clear_cache');

        props.deleteAward({});
      });
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