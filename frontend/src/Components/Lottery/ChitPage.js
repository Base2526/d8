import React, { Component } from 'react';
import Alert from 'react-bootstrap/Alert'
import { connect } from 'react-redux'
import Container from 'react-bootstrap/Container'
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'
import Button from 'react-bootstrap/Button'
import ButtonGroup from 'react-bootstrap/ButtonGroup'
import Form from 'react-bootstrap/Form'
import ReactList from 'react-list';
import InfiniteScroll from "react-infinite-scroll-component";
// import InfiniteScroll from 'react-infinite-scroller';
import Modal from 'react-bootstrap/Modal'
import axios from 'axios';
import _ from 'lodash';
import { Accordion, AccordionItem } from 'react-sanfona';

import NumericInput from 'react-numeric-input';
import OtpInput from '../Utils/OtpInput';
import {headers, 
        showToast, 
        isEmpty, 
        getCurrentDate, 
        getCurrentTime, 
        getTime,
        getTimeWithDate} from '../Utils/Config';
import { loadingOverlayActive } from '../../actions/huay'

import '../../index.css';

const style = {
  height: 30,
  border: "1px solid green",
  margin: 6,
  padding: 8
};

var interval = undefined;
var btn_interval = undefined;
class ChitPage extends Component {
  constructor(props) {
    super(props);
    this.state = {
      date_time: '', // เก็บวัน เวลาทีเปิด page
      error: false,
      error_message:'',
      total: 0,
      equal_price:0,

      is_active: false,
      loading_text: 'รอสักครู่ กำลังส่งโพย',
      confirm_show: false,
      shoot_number_show: false,

      otp: '',
      m: {mode:3, mi:['type_3_up']},
      note:'',
      time:'',
      shoot_number: '',

      items: Array.from({ length: 15 }),
      shoot_number_text: 'เพิ่มเลข',

      data: [
        {
          headerName: 'สามตัวบน',
          isOpened: true,
          type:'type_3_up',
          height: 120,
          items: [],
        },
        {
          headerName: 'สามตัวโต๊ด',
          isOpened: true,
          type:'type_3_toot',
          height: 120,
          items: [],
        },
        {
          headerName: 'สองตัวบน',
          isOpened: true,
          type:'type_2_up',
          height: 120,
          items: [],
        },
        {
          headerName: 'สองตัวล่าง',
          isOpened: true,
          isReactComponent: false,
          type:'type_2_down',
          height: 120,
          items: [],
        },
        {
          headerName: 'สามตัวกลับ',
          isOpened: true,
          type:'type_3_undo',
          height: 120,
          items: [],
        },
        {
          headerName: 'สองตัวกลับ',
          isOpened: true,
          type:'type_2_undo',
          height: 120,
          items: [],
        },
        {
          headerName: 'วิ่งบน',
          isOpened: true,
          type:'type_1_up',
          height: 120,
          items: [],
        },
        {
          headerName: 'วิ่งล่าง',
          isOpened: true,
          type:'type_1_down',
          height: 120,
          items: [],
        }
      ]
    }

    this.handleItemClick = this.handleItemClick.bind(this);
    // this.renderItem = this.renderItem.bind(this);
    // this.handleItemOnInput = this.handleItemOnInput.bind(this);
    this.handlerItemChange = this.handlerItemChange.bind(this);

    this.listBookmakers    = this.listBookmakers.bind(this);

    // this.handlerEqualOnInput = this.handlerEqualOnInput.bind(this);
    this.handlerEqualChange = this.handlerEqualChange.bind(this);

    this.handleBetClick = this.handleBetClick.bind(this);
    this.handleClearClick = this.handleClearClick.bind(this);

    this.onCloseAlert  = this.onCloseAlert.bind(this);
    this.viewType = this.viewType.bind(this);
    this.addNumber = this.addNumber.bind(this);

    this.expanded = this.expanded.bind(this);
    this.showConfirmModal = this.showConfirmModal.bind(this)
    this.showshootNumberShowModal = this.showshootNumberShowModal.bind(this);
    this.handleChange = this.handleChange.bind(this);
    this.chitEqualPrice= this.chitEqualPrice.bind(this);

    this.fetchMoreData = this.fetchMoreData.bind(this);
  }

  componentDidMount() {
    let {match, round} = this.props; 

    console.log(round);

    switch(match.params.type){
      case 'yeekee':{
        this.setState({time: getTime(round), date_time: new Date().getTime()});
        interval = setInterval(() => {
          let {round} = this.props; 
          this.setState({time: getTime(round)});
        }, 1000);
        break;
      }
      default:{
        this.setState({time: getTimeWithDate(round, false), date_time: new Date().getTime()});
        interval = setInterval(() => {
          let {round} = this.props; 
          this.setState({time: getTimeWithDate(round, false)});
        }, 1000);
      }
    }


//    // Convert timestamp to milliseconds
//  var date = new Date(1589569200*1000);

//  // Year
//  var year = date.getFullYear();
//   // Month
//   // var month = months_arr[date.getMonth()];

//   // Day
//   var day = date.getDate();
 
//  console.log(year, day);
    /*
    console.log(match)
    let round = yeekee_round.find((val) => { return val.tid == match.params.id});
    this.setState({time: getTime(round)});

    interval = setInterval(() => {
      console.log('Interval triggered');
      

      let {match, yeekee_round} = this.props; 

      let round = yeekee_round.find((val) => { return val.tid == match.params.id});
      // console.log(find.end_time)
      // console.log(this.getTime(round));
      this.setState({time: getTime(round)});
    }, 1000);
    */
  }

  componentWillUnmount(){
    if(interval){
      clearInterval(interval);
    }
  }

  handleOtpChange = otp => {
    console.log(otp);
    if(otp.toString().length == 3){
      this.setState({ otp });

      this.addNumber(otp);
      setTimeout(function() { 
        this.setState({ otp:'' });
      }.bind(this), 500)
    }else{
      this.setState({ otp });
    }
  }

  handleItemClick = (id) => {
    let mid  = id.split("-");
    let {data, m} = this.state;

    // console.log(data, m)
    let find = data.find((val) => { return val.type === mid[0]});

    let items = find.items;
    items.splice(mid[1], 1);
    let new_find = {...find, items}
    // console.log(new_find)

    let findIndex = data.findIndex((val) => { return val.type === mid[0]});

    let newData = [...data]
    newData[findIndex] = new_find;

    let total = 0;
    newData.map((nv, nk) =>{
      nv.items.map((nvv, nkk) =>{
        total+=parseInt(nvv.quantity)
      })
    })
    
    this.setState({data: newData, total})
  }

  handlerItemChange(newVal, valStr, event){
    console.log(newVal, valStr, event.id, event.value);

    let mid  = event.id.split("-");
    let {data, m} = this.state;

    let find = data.find((val) => { return val.type === mid[0]});

    let items = find.items;

    let item = items[mid[1]]

    item = {...item, quantity:event.value}

    items[mid[1]] = item
    let new_find = {...find, items}

    let findIndex = data.findIndex((val) => { return val.type === mid[0]});
    let newData = [...data]

    newData[findIndex] = new_find;

    let total = 0;
    newData.map((nv, nk) =>{
      nv.items.map((nvv, nkk) =>{
        total+=parseInt(nvv.quantity)
      })
    })
    
    this.setState({data: newData, total})
  }

  addNumber(number){
    let {user} = this.props;
    let {data, m} = this.state;

    m.mi.map((key, i) =>{
      let find = data.find((val) => { return val.type === key});
      let items = find.items;
      if(!items.find((val) => { return val.number === number })){
        let item =  { number,
                      quantity:1,
                      price:90
                    }
        items = [...items, item]
        find  = {...find, items}

        let findIndex = data.findIndex((val) => { return val.type === key});

        let newData = [...data]
        newData[findIndex] = find;

        let total = 0;
        newData.map((nv, nk) =>{
          nv.items.map((nvv, nkk) =>{
            total+=parseInt(nvv.quantity)
          })
        })
        
        this.setState({data: newData, total})
      }else{
        showToast('warn', 'กรอบเลขซํ้า')
      }
    })
  } 

  handleBetClick = async()=>{
    this.setState({confirm_show: false, is_active:true})

    let {match, round} = this.props; 
    let {data, date_time} = this.state;

    data =  data.filter(function(val) { return !isEmpty(val.items)})

    // var b64 = btoa(JSON.stringify(data));
    // console.log(b64, JSON.stringify(data))

    // yeekee_round : รอบ       {match.params.id}
    // chit_type    : ยี่กี(30) หรือ หวยรัฐบาลไทย

    // let round = {};
    let buff;
    switch(match.params.type){
      case 'yeekee':{
        // let child = childs.find((val) => { return val.tid == 67 });
        // round = child.rounds.find((val) => { return val.tid == params.id });
        
        // let yeekees = state.lotterys.data.find((val) => { return val.tid == 67 });
        // round = yeekees.rounds.find((val) => { return val.tid == params.id });

        buff = new Buffer(JSON.stringify({round_tid:round.tid,  chit_type: 67, data}));

        break;
      }
      case 'thai-government':
      case 'hanoi':
      case 'hanoi-vip':
      case 'malay':
      case 'laos':
      case 'baac':
      case 'savings-bank':
      case 'stock-singapore':
      case 'stock-thai':
      case 'stock-india':
      case 'stock-russia':
      case 'stock-german':
      case 'stock-dow':
      case 'stock-egypt':
      case 'stock-en':
      case 'stock-nikkei':
      case 'stock-hangseng':
      case 'stock-taiwan':
      case 'stock-korea':
      case 'stock-china':{
        // round = childs.find((val) => { return val.tid == params.id });

        // round = state.lotterys.data.find((val) => { return val.tid == params.id });
        
        // chit_type
        buff = new Buffer(JSON.stringify({chit_type:round.tid, data}));
        break;
      }
    }

    let response  = await axios.post('/api/bet', 
                                          { uid: this.props.user.uid,
                                            data: buff.toString('base64'),
                                            time: date_time}, 
                                          {headers:headers()});
    console.log(response);

    this.setState({is_active:false})

    if( response.status==200 && response.statusText == "OK" ){
      if(response.data.result){
        this.nextPath('/');

        showToast('success', 'ส่งโพยเรียบร้อย');
      }else{
        this.setState({
          error: true,
          error_message: response.data.message,
          password:''
        });

        showToast('error', response.data.message);
      }
    }else{
      showToast('error', 'Error');
    }
  }

  /*
  
  let header_row;
    let {history, match, user, round} = this.props; 
    switch(match.params.type){
      case 'yeekee':{
        header_row = <Row>
                <Col style={{border: '1px solid #61dafb'}} md={12} xs={12}>
                  <div>
                    หวยยี่กี รอบที่ {this.getYeekeeRound()}
                  </div>
                  <div>
                    เวลาเหลือ {time}
                  </div>
                </Col>
              </Row>
        break;
      }
      default:{
        header_row = <Row>
                <Col style={{border: '1px solid #61dafb'}} md={12} xs={12}>
                  <div>
                    {round.name}
                  </div>
                  <div>
                    เวลาเหลือ {time}
                  </div>
                </Col>
              </Row>
      }
    }
  */

  handleShootNumberClick(){
    let count  = 10;
    btn_interval = setInterval(async() => {
      this.setState({shoot_number_text: count--});
      if(count < 0){
        this.setState({shoot_number_text: 'เพิ่มเลข'});
        clearInterval(btn_interval);

        btn_interval = undefined;

        let {match, user} = this.props
        let {shoot_number, date_time} = this.state
        let response  = await axios.post('/api/shoot_number', 
                                        { uid: user.uid,
                                          data: shoot_number,
                                          round_tid: match.params.id,
                                          time: date_time
                                        }, 
                                        {headers:headers()});

        console.log(response);
        // if( response.status==200 && response.statusText == "OK" ){
        //   if(response.data.result){
              this.setState({shoot_number: ''})
        //     showToast('success', 'เพิ่มเลข เรียบร้อย');
        //   }else{
        //     showToast('error', response.data.message);
        //   }
        // }else{
        //   showToast('error', 'Error');
        // }

        
      }
    }, 1000);
  }

  nextPath(path) {
    this.props.history.push(path);
  }

  handleClearClick(){
    console.log('handleClearClick');
  }

  loadingOverlayActive(){
    this.props.loadingOverlayActive(this.state.is_active, this.state.loading_text);
  }

  onCloseAlert(){
    this.setState({error:false});
  }

  handleEqualPrice(id){
    switch(id){
      // 5
      case 1:{
        this.setEqualPrice(5)
        break;
      }
      // 10
      case 2:{
        this.setEqualPrice(10)
        break;
      }
      // 20
      case 3:{
        this.setEqualPrice(20)
        break;
      }
      // 50
      case 4:{
        this.setEqualPrice(50)
        break;
      }
      // 100
      case 5:{
        this.setEqualPrice(100)
        break;
      }
    }
  }

  handlerEqualChange(newVal, valStr, event){
    console.log(newVal, valStr, event)
    this.setEqualPrice(newVal)

    // 
  }

  setEqualPrice(quantity){
    let total = 0;
    let {data} = this.state
    let new_data = [...data]
    data.map((v, k) =>{
      // let tmp_v = {...v}
      let items = [...v.items]
      v.items.map((vv, kk) =>{
        // total+=parseInt(nvv.quantity)
        items[kk] = {...vv, quantity}

        total+=parseInt(quantity)
      })
      new_data[k] = {...v, items}
    })
    this.setState({data:new_data, total, equal_price: quantity})
  }

  handleNumPad(key){
    let {otp, m} = this.state;
    console.log(m.mode)
    switch(key){
      case 0:
      case 1:
      case 2:
      case 3:
      case 4:
      case 5:
      case 6:
      case 7:
      case 8:
      case 9:{
        otp = otp + key;
        console.log(key, otp)
        if(otp.toString().length == m.mode){
          this.setState({ otp });

          this.addNumber(otp);

          setTimeout(function() { 
            this.setState({ otp:'' });
          }.bind(this), 500)
        }else{
          this.setState({ otp });
        }
        break;
      }
      case 'C':{
        otp = otp;
        if(otp.toString().length > 0){
          this.setState({ otp:'' });
        }
        break;
      }
      case 'D':{
        otp = otp;
        if(otp.toString().length > 0){
          otp = otp.slice(0, -1); 
          this.setState({ otp});
        }
        break;
      }
    }
  }

  handleType(type){
    let {m} = this.state
    let tmp_m = {}
    switch(type){
      case 'type_3_up':{
        if(m.mode === 3){
          if(m.mi.find((val) => { return val === type })){
            let mi =  m.mi.filter(function(val) { return val !== type })
            tmp_m = {...m, mi}
          }else{
            let mi    = [...m.mi, type]
            tmp_m  = {...m, mi}
          }
        }else{
          tmp_m = {mode:3, mi:['type_3_up']}
        }
        console.log(tmp_m);
        break;
      }
      case 'type_3_toot':{
        if(m.mode === 3){
          if(m.mi.find((val) => { return val === type })){
            let mi =  m.mi.filter(function(val) { return val !== 'type_3_undo' })
                mi =  m.mi.filter(function(val) { return val !== type })
             tmp_m = {...m, mi}
          }else{
            let mi =  m.mi.filter(function(val) { return val !== 'type_3_undo' })
                mi = [...mi, type]
            tmp_m  = {...m, mi}
          }
        }else{
          tmp_m = {mode:3, mi:['type_3_toot']}
        }
        console.log(tmp_m);
        break;
      }
      case 'type_3_undo':{
        if(m.mode === 3){
          if(m.mi.find((val) => { return val === type })){
            let mi =  m.mi.filter(function(val) { return val !== 'type_3_toot' })
                mi =  m.mi.filter(function(val) { return val !== type })
             tmp_m = {...m, mi}
          }else{
            let mi =  m.mi.filter(function(val) { return val !== 'type_3_toot' })
                mi = [...mi, type]
            tmp_m  = {...m, mi}
          }
        }else{
          tmp_m = {mode:3, mi:['type_3_undo']}
        }
        console.log(tmp_m);
        break;
      }
      // 2 ตัวบน
      case 'type_2_up':{
        if(m.mode === 2){
          if(m.mi.find((val) => { return val === type })){
            let  mi =  m.mi.filter(function(val) { return val !== type })
            tmp_m = {...m, mi}
          }else{
            let mi = [...m.mi, type]
            tmp_m  = {...m, mi}
          }
        }else{
          tmp_m = {mode:2, mi:['type_2_up']}
        }
        break;
      }

      // 2 ตัวล่าง
      case 'type_2_down':{
        if(m.mode === 2){
          if(m.mi.find((val) => { return val === type })){
            let  mi =  m.mi.filter(function(val) { return val !== type })
            tmp_m = {...m, mi}
          }else{
            let mi = [...m.mi, type]
            tmp_m  = {...m, mi}
          }
        }else{
          tmp_m = {mode:2, mi:['type_2_down']}
        }
        break;
      }

      // 2 ตัวกลับ
      case 'type_2_undo':{
        if(m.mode === 2){
          if(m.mi.find((val) => { return val === type })){
            let  mi =  m.mi.filter(function(val) { return val !== type })
            tmp_m = {...m, mi}
          }else{
            let mi = [...m.mi, type]
            tmp_m  = {...m, mi}
          }
        }else{
          tmp_m = {mode:2, mi:['type_2_undo']}
        }
        break;
      }

      // วิ่งบน
      case 'type_1_up':{
        if(m.mode === 1){
          if(m.mi.find((val) => { return val === type })){
            let  mi =  m.mi.filter(function(val) { return val !== type })
            tmp_m = {...m, mi}
          }else{
            let mi = [...m.mi, type]
            tmp_m  = {...m, mi}
          }
        }else{
          tmp_m = {mode:1, mi:['type_1_up']}
        }
        break;
      }

      // วิ่งล่าง
      case 'type_1_down':{
        if(m.mode === 1){
          if(m.mi.find((val) => { return val === type })){
            let  mi =  m.mi.filter(function(val) { return val !== type })
            tmp_m = {...m, mi}
          }else{
            let mi = [...m.mi, type]
            tmp_m  = {...m, mi}
          }
        }else{
          tmp_m = {mode:1, mi:['type_1_down']}
        }
        break;
      }
    }
    // console.log(tmp_m)
    this.setState({m: tmp_m})
  }

  variant(mode, type){
    let {m} = this.state
    return ( m.mode === mode ? ( (m.mi.find((val) => { return val === type })) ? 'primary' : 'outline-primary' ) : 'outline-primary' )
  }

  viewType(){
    return( <div>
            <div>
              <Button 
                variant={this.variant(3, 'type_3_up')}
                onClick={() =>this.handleType("type_3_up")}>3 ตัวบน</Button>
              <Button 
                variant={this.variant(3, 'type_3_toot')}
                onClick={() =>this.handleType("type_3_toot")}>3 ตัวโต๊ด</Button>
              <Button 
                variant={this.variant(2, 'type_2_up')}
                onClick={() =>this.handleType("type_2_up")}>2 ตัวบน</Button>
              <Button 
                variant={this.variant(2, 'type_2_down')}
                onClick={() =>this.handleType("type_2_down")}>2 ตัวล่าง</Button>
            </div>
            <div>
              <Button 
                variant={this.variant(3, 'type_3_undo')}
                onClick={() =>this.handleType("type_3_undo")}>3 ตัวกลับ</Button> 
              <Button 
                variant={this.variant(2, 'type_2_undo')}
                onClick={() =>this.handleType("type_2_undo")}>2 ตัวกลับ</Button> 
              <Button 
                variant={this.variant(1, 'type_1_up')}
                onClick={() =>this.handleType("type_1_up")}>วิ่งบน</Button> 
              <Button 
                variant={this.variant(1, 'type_1_down')}
                onClick={() =>this.handleType("type_1_down")}>วิ่งล่าง</Button>
              </div>
            </div> )
  }

  expanded(index, isOpened){
    let {data} = this.state;
    let item = data[index]

    // isOpened
    item = {...item, isOpened}

    let new_data = [...data]
    new_data[index] = item;

    this.setState({data: new_data})
  }

  listBookmakers(){
    let { data } = this.state;

    data =  data.filter(function(val) { return !isEmpty(val.items)})
    console.log(data)
    return(
      <Accordion allowMultiple>
        {
        data.map((value, i) =>{
          return (
            <AccordionItem 
              key={i} 
              title={`${value.headerName} (${value.items.length})`} 
              expanded={value.isOpened}
              onExpand={(index) =>{
                console.log("onExpand : " + index);
                this.expanded(index, true);
              }}
              onClose={(index) =>{
                console.log("onClose : " + index);
                this.expanded(index, false);
              }}>
              {
                value.items.map((v, key) =>{
                  return (<div key={key}>
                          <div>{v.number}</div>
                          <div>
                            <NumericInput 
                              min={1} 
                              id={value.type +"-"+ key}
                              onChange={this.handlerItemChange}
                              value={v.quantity} />
                            <div>
                              ชนะ : {v.quantity*v.price}
                            </div>
                            <button key={key} data-id={value.type +"-"+ key} style={{backgroundColor: 'red'}} onClick={e => this.handleItemClick(e.target.getAttribute('data-id'))}>X</button>
                          </div>
                        </div>)
                })
              }
            </AccordionItem>
          );
        })
        }
      </Accordion>
    )
  }

  chitEqualPrice(){
    return<div style={{ border: '1px solid #61dafb' }}>
            <div>
            เดิมพันราคาเท่ากัน  
            </div>
            <Button
              variant="primary"
              // disabled={isLoading}
              onClick={() =>this.handleEqualPrice(1)}>
              5 ฿
            </Button>
            <Button
              variant="primary"
              // disabled={isLoading}
              onClick={() =>this.handleEqualPrice(2)}>
              10 ฿
            </Button>
            <Button
              variant="primary"
              // disabled={isLoading}
              onClick={() =>this.handleEqualPrice(3)}>
              20 ฿
            </Button>
            <Button
              variant="primary"
              // disabled={isLoading}
              onClick={() =>this.handleEqualPrice(4)}>
              50 ฿
            </Button>
            <Button
              variant="primary"
              // disabled={isLoading}
              onClick={() =>this.handleEqualPrice(5)}>
              100 ฿
            </Button>
            <NumericInput 
              min={1} 
              // id={}
              // className="form-control" 
              // onInput={this.handlerEqualOnInput} 
              onChange={this.handlerEqualChange}
              value={this.state.equal_price} 
              />
          </div>
  }

  handleChange(event) {
    // console.log(event.target.value);
    this.setState({[event.target.id]: event.target.value});
  }

  showConfirmModal(){
    let {match, round} = this.props; 
    let {data, note} = this.state;
    data =  data.filter(function(val) { return !isEmpty(val.items)})

    let header_row;
    switch(match.params.type){
      case 'yeekee':{
        header_row = <p> หวยยี่กี รอบที่ {this.getYeekeeRound()}</p>
        break;
      }
      default:{
        header_row = <p>{round.name}</p>
        break;
      }
    }

    let total = 0;
    return <Modal
            show={this.state.confirm_show}
            onHide={() => {
              this.setState({confirm_show: false})
            }}
            dialogClassName="modal-90w"
            aria-labelledby="example-custom-modal-styling-title"
            backdrop="static"
            scrollable={true}>
            <Modal.Header closeButton>
              <Modal.Title id="example-custom-modal-styling-title">
                ยืนยันการส่งโพย
              </Modal.Title>
            </Modal.Header>
            <Modal.Body>
              <p>
              {header_row}
              วันที่	{getCurrentDate('/')}
              เวลา	{getCurrentTime()}
              </p>
              <p>
              รายการแทง
              </p>
              {
                data.map((nv, nk) =>{  
                    let items =  nv.items.map((nvv, nkk) =>{
                      total+=parseInt(nvv.quantity)
                      return <div key={nkk}>{nvv.number} - {nvv.quantity} ฿</div>      
                    })
                    return  <div key={nk}>
                              <div>{ nv.headerName }</div>
                              <div>{ items }</div>
                            </div>
                })
              }
              <div>ยอดรวมทั้งหมด {total} ฿</div>
              <Form.Group controlId="note">
                <Form.Label>หมายเหตุ</Form.Label>
                <Form.Control as="textarea" rows="2" value={note} onChange={this.handleChange}/>
              </Form.Group>
            </Modal.Body>
            <Modal.Footer>
              <Button onClick={() =>{ this.handleBetClick() }}>กดยืนยันการส่งโพย</Button>
            </Modal.Footer>
          </Modal>
  }

  renderItem(index, key) {
    return <div key={key}>{index}</div>;
  }

  fetchMoreData(){

    // let {shoot_number} = 

    // a fake async api call like which sends
    // 20 more records in 1.5 secs
    // setTimeout(() => {
    //   this.setState({
    //     items: this.state.items.concat(Array.from({ length: 15 }))
    //   });
    //   console.log('fetchMoreData #1');
    // }, 1500);
    console.log('fetchMoreData #0');

  }

  shootNumberHeader(){
    
    if(btn_interval === undefined){
      // console.log(this.state.shoot_number);
      let {shoot_number} = this.state;

      let btn_shoot_number = <Button onClick={() =>{ this.handleShootNumberClick() }}>{this.state.shoot_number_text}</Button>;
      console.log(shoot_number)
      if(isEmpty(shoot_number) || shoot_number.length < 5 ){
        btn_shoot_number = <Button disabled onClick={() =>{ this.handleShootNumberClick() }}>{this.state.shoot_number_text}</Button>
      }
      return<Modal.Header closeButton>
              <Modal.Title id="example-custom-modal-styling-title">ยิงเลข</Modal.Title>
              <Form.Group controlId="formGroupEmail">
                <Form.Control 
                  type="number"  
                  value={shoot_number}
                  onInput = {(e) =>{
                    e.target.value = Math.max(0, parseInt(e.target.value) ).toString().slice(0,5)
                    console.log(e.target.value);

                    this.setState({shoot_number:e.target.value})
                  }} 
                  placeholder="กรอบตัวเลข 5 หลัก" />
              </Form.Group>
              {btn_shoot_number}
            </Modal.Header>
    }

    return  <Modal.Header closeButton>
              <Modal.Title id="example-custom-modal-styling-title">ยิงเลข</Modal.Title>
              <Form.Group controlId="formGroupEmail">
                <Form.Control 
                  disabled
                  type="number"  
                  onInput = {(e) =>{
                          e.target.value = Math.max(0, parseInt(e.target.value) ).toString().slice(0,5)
                  }} 
                  placeholder="กรอบตัวเลข 5 หลัก" />
              </Form.Group>
              <Button disabled onClick={() =>{ this.handleShootNumberClick() }}>{this.state.shoot_number_text}</Button>
            </Modal.Header>
  }

  showshootNumberShowModal(){
    let {numbers} = this.props
    console.log(numbers)


    // const sorted = _.sortBy(shoot_number.numbers,['nid'], ['asc']);
    // console.log(shoot_number.numbers, sorted)

    // let lotterys = state.lotterys.data.sort(function(obj1, obj2) {return obj1.weight - obj2.weight;});
    
    let modal_body = <div/>
    if(!isEmpty(numbers)){
      modal_body =  <Modal.Body>
                      <InfiniteScroll
                        dataLength={ isEmpty(numbers)? 0 :numbers.length}
                        // next={this.fetchMoreData}
                        // hasMore={true}
                        loader={<h4>Loading...</h4>}
                        height={400}
                        endMessage={
                          <p style={{ textAlign: "center" }}>
                            <b>Yay! You have seen it all</b>
                          </p>
                        }>
                        {numbers.map((i, index) => {

                          var date = new Date(i.update*1000);
                          // Year
                          var year = date.getFullYear();
                          // Month
                          var month = date.getMonth();
                          // Day
                          var day = date.getDate();
                          // Hours
                          var hours = date.getHours();
                          // Minutes
                          var minutes = "0" + date.getMinutes();
                          // Seconds
                          var seconds = "0" + date.getSeconds();
                          // Display date time in MM-dd-yyyy h:m:s format
                          var convdataTime = day +'-'+month+'-'+year+' '+hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

                          return<div style={style} key={index}>
                                  ลำดับที่ {index} หมายเลข {i.number}
                                  ผู้ส่งเลข {i.name}
                                  เมื่อ {convdataTime}
                                </div>
                          }
                        )}
                        
                      </InfiniteScroll>
                    </Modal.Body>
    }
    
    return  <Modal
              show={this.state.shoot_number_show}
              onHide={() => {
                this.setState({shoot_number_show: false})
              }}
              dialogClassName="modal-90w"
              aria-labelledby="example-custom-modal-styling-title"
              backdrop="static"
              scrollable={true}>
              {this.shootNumberHeader()}
              {modal_body}
            </Modal>
  }

  getYeekeeRound(){
    let {match, yeekee_round} = this.props; 

    let find = yeekee_round.find((val) => { return val.tid == match.params.id});
    if(find){
      return find.name;
    }else{
      return '';
    }
  }

  gotoPageReward(){
    let {history, match} = this.props; 
    let {time} = this.state;
    console.log(history);
    if(time == -1){
      history.push('/lottery-list/reward/'+ match.params.type +'/' + match.params.id)
    }

    // เราต้องคิดกรณีที่ user เปิดหน้าค้างข้างวันกรณีเรายังไม่ได้ นําเอามาคิด
  }

  render() {

    let {history, match, user, round} = this.props; 
    let {error, error_message, total, equal_price, otp, m, time} = this.state;

    // this.gotoPageReward();
    this.loadingOverlayActive();

    let btnC = <Button variant="outline-primary" onClick={() =>this.handleNumPad("C")} disabled>C</Button>;
    let btnD = <Button variant="outline-primary" onClick={() =>this.handleNumPad("D")} disabled>D</Button>;
    if(otp.toString().length > 0){
      btnC = <Button variant="outline-primary" onClick={() =>this.handleNumPad("C")}>C</Button> ;
      btnD = <Button variant="outline-primary" onClick={() =>this.handleNumPad("D")}>D</Button>;
    }
    
    let header_row;
    let view_shoot_number_show = <div />;
    switch(match.params.type){
      case 'yeekee':{
        header_row = <Row>
                <Col style={{border: '1px solid #61dafb'}} md={12} xs={12}>
                  <div>
                    หวยยี่กี รอบที่ {this.getYeekeeRound()}
                  </div>
                  <div>
                    เวลาเหลือ {time}
                  </div>
                </Col>
              </Row>;

        view_shoot_number_show =  <Button
                                    variant="outline-primary"
                                    onClick={() =>{
                                      this.setState({shoot_number_show: true})
                                    }}>
                                    ยิงเลข
                                  </Button>;
        break;
      }
      default:{
        header_row = <Row>
                <Col style={{border: '1px solid #61dafb'}} md={12} xs={12}>
                  <div>
                    {round.name}
                  </div>
                  <div>
                    เวลาเหลือ {time}
                  </div>
                </Col>
              </Row>
      }
    }

    return (
      <div>
        { this.state.confirm_show ? this.showConfirmModal() : '' }
        { this.state.shoot_number_show ? this.showshootNumberShowModal() : ''}
        <Container style={{minHeight: 600}}>
          {header_row}
          <Row style={{ border: '1px solid #61dafb' }}>
            <Col style={{ border: '1px solid #61dafb' }} md={8} xs={12}>
              <div>
              ยอดเดิมพันทั้งหมด : {total} ฿
              </div>
            </Col>
            <Col style={{ border: '1px solid #61dafb' }} md={4} xs={12}>
              <Button
                variant="primary"
                onClick={() =>{
                  this.setState({confirm_show: true})
                } }>
                กดส่งโพย
              </Button>
            { view_shoot_number_show }
            </Col>
          </Row>
          <Row>
            <Col style={{ border: '1px solid #BF553F' }} md={4} xs={12}>
              <div>รายการแทง</div>
              <div style={{overflow: 'auto', maxHeight: 400, minHeight: 400, border: '1px solid #708024'}}>
                {/* <ReactList
                  itemRenderer={this.renderItem}
                  length={this.state.book_makers.length}
                  type='uniform'
                /> */}
                {this.listBookmakers()}
              </div>

              { this.chitEqualPrice() }
              
            </Col>
            <Col style={{ border: '1px solid #61dafb' }} md={8} xs={12}>
              {this.viewType()}
              <div>
                <OtpInput
                  inputStyle="inputStyle"
                  onChange={this.handleOtpChange}
                  numInputs={m.mode}
                  value={otp}
                />
              </div>
              <div>
                <div>
                    <Button variant="outline-primary" onClick={() =>this.handleNumPad(1)}>1</Button> 
                    <Button variant="outline-primary" onClick={() =>this.handleNumPad(2)}>2</Button>
                    <Button variant="outline-primary" onClick={() =>this.handleNumPad(3)}>3</Button>
                </div>
                <div>
                    <Button variant="outline-primary" onClick={() =>this.handleNumPad(4)}>4</Button> 
                    <Button variant="outline-primary" onClick={() =>this.handleNumPad(5)}>5</Button> 
                    <Button variant="outline-primary" onClick={() =>this.handleNumPad(6)}>6</Button> 
                </div>
                <div>
                    <Button variant="outline-primary" onClick={() =>this.handleNumPad(7)}>7</Button> 
                    <Button variant="outline-primary" onClick={() =>this.handleNumPad(8)}>8</Button> 
                    <Button variant="outline-primary" onClick={() =>this.handleNumPad(9)}>9</Button> 
                </div>
                <div>
                    {btnC}
                    <Button variant="outline-primary" onClick={() =>this.handleNumPad(0)}>0</Button> 
                    {btnD}
                </div>
              </div>
              <div>
                <div>เงื่อนไขการแทง</div>
                <div>
                <div>วิ่งบน จ่าย : 3.20</div>
                <ul>
                  <li>แทงขั้นต่ำต่อครั้ง : 10.00</li>
                  <li>แทงสูงสุดต่อครั้ง : 20000.00</li>
                  <li>แทงสูงสุดต่อเลข : 20000.00</li>
                </ul>
                <div>วิ่งล่าง จ่าย : 4.20</div>
                <ul>
                  <li>แทงขั้นต่ำต่อครั้ง : 10.00</li>
                  <li>แทงสูงสุดต่อครั้ง : 20000.00</li>
                  <li>แทงสูงสุดต่อเลข : 20000.00</li>
                </ul>
                </div>
              </div>
            </Col>
          </Row>
        </Container> 
      </div>
    )
  }
}

const mapStateToProps = (state, ownProps) => {
  console.log(state)
  console.log(ownProps)
  console.log(state.yeekee_round.data)
	if(!state._persist.rehydrated){
		return {};
  }
  
  if(state.auth.isLoggedIn){
    /*
      // หวยรัฐบาลไทย  : thai-government : 66 
      // หวยยี่กี่        : yeekee          : 67
      // หวยฮานอย     : hanoi           : 68
      // หวยฮานอย VIP : hanoi-vip       : 69
      // หวยมาเลย์     : malay           : 70
      // หวยลาว       : laos            : 71
      // หวยธกส.      : baac            : 72
      // หวยออมสิน     : savings-bank    : 73

      // หุ้นสิงคโปร์     : stock-singapore : 75
      // หุ้นไทย        : stock-thai      : 76
      // หุ้นอินเดีย      : stock-india     : 77
      // หุ้นรัสเซีย      : stock-russia    : 78
      // หุ้นเยอรมัน     : stock-german    : 79
      // หุ้นดาวโจนส์    : stock-dow       : 80
      // หุ้นอียิปต์       : stock-egypt     : 81
      // หุ้นอังกฤษ      : stock-en        : 82
      // นิเคอิ         : stock-nikkei    : 88
      // หุ้นฮั่งเส็ง      : stock-hangseng  : 85
      // หุ้นไต้หวัน      : stock-taiwan    : 86
      // หุ้นเกาหลี      : stock-korea     : 87
      // หุ้นจีน         : stock-china     : 84
    */

    // params: {type: "yeekee", id: "63"}
    let {params} = ownProps.match
    // console.log(params);

    console.log(state.lotterys.data)
    let lotterys = state.lotterys.data.sort(function(obj1, obj2) {return obj1.weight - obj2.weight;});

    // let childs = [];
    // lotterys.map((v, k) =>{ childs = [...childs, ...v.childs] })

    let round = {};
    switch(params.type){
      case 'yeekee':{
        // let child = childs.find((val) => { return val.tid == 67 });
        // round = child.rounds.find((val) => { return val.tid == params.id });
        
        let yeekees = state.lotterys.data.find((val) => { return val.tid == 67 });
        console.log(yeekees);
        round = yeekees.rounds.find((val) => { return val.tid == params.id });
        break;
      }
      case 'thai-government':
      case 'hanoi':
      case 'hanoi-vip':
      case 'malay':
      case 'laos':
      case 'baac':
      case 'savings-bank':
      case 'stock-singapore':
      case 'stock-thai':
      case 'stock-india':
      case 'stock-russia':
      case 'stock-german':
      case 'stock-dow':
      case 'stock-egypt':
      case 'stock-en':
      case 'stock-nikkei':
      case 'stock-hangseng':
      case 'stock-taiwan':
      case 'stock-korea':
      case 'stock-china':{
        // round = childs.find((val) => { return val.tid == params.id });

        round = state.lotterys.data.find((val) => { return val.tid == params.id });
        break;
      }
    }

    
    let numbers = state.shoot_numbers.data.find((val) => { return val.round_id == params.id });
    // let yeekees = state.lotterys.data.find((val) => { return val.tid == 67 });
    // console.log(shoot_number)
    // console.log(params)

    // 

    // console.log(state.shoot_numbers[0].numbers, numbers);
    // console.log(state.shoot_numbers);

    if(!isEmpty(numbers)){
      numbers = numbers.numbers.sort(function(obj1, obj2) {return obj2.nid - obj1.nid;});
    }

    return {  logged_in: true, 
              user: state.auth.user,
              lotterys,
              yeekee_round: state.yeekee_round.data,
              round,
              numbers};
  }else{
    return { logged_in: false };
  }
}

const mapDispatchToProps = (dispatch) => {
	return {
    loadingOverlayActive: (isActivie, loadingText) =>{
      dispatch(loadingOverlayActive(isActivie, loadingText))
    }
	}
}

export default connect(mapStateToProps, mapDispatchToProps)(ChitPage)