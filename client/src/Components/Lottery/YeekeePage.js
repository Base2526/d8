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
import Modal from 'react-bootstrap/Modal'
import axios from 'axios';
// import btoa from 'btoa';
import { Accordion, AccordionItem } from 'react-sanfona';

import NumericInput from 'react-numeric-input';
import OtpInput from '../Utils/OtpInput';
import { headers, showToast, isEmpty, getCurrentDate, getCurrentTime } from '../Utils/Config';
import { loadingOverlayActive } from '../../actions/huay'

import '../../index.css';

class YeekeePage extends Component {
  constructor(props) {
    super(props);
    this.state = {
      error: false,
      error_message:'',
      total: 0,
      equal_price:0,

      is_active: false,
      loading_text: 'รอสักครู่ กำลังส่งโพย',
      confirm_show: false,

      otp: '',
      m: {mode:3, mi:['type_3_up']},

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
  }

  componentDidMount() {

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

    let {match} = this.props;
    let {data} = this.state;

    data =  data.filter(function(val) { return !isEmpty(val.items)})

    // var b64 = btoa(JSON.stringify(data));
    // console.log(b64, JSON.stringify(data))

    // yeekee_round : รอบ       {match.params.id}
    // chit_type    : ยี่กี(30) หรือ หวยรัฐบาลไทย


    let buff = new Buffer(JSON.stringify({yeekee_round:match.params.id,  chit_type: 30, data}));

    let response  = await axios.post('/api/bet', 
                                      { uid: this.props.user.uid,
                                        data: buff.toString('base64')}, 
                                      {headers:headers()});
    console.log(response);
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

  showConfirmModal(){
    let {match} = this.props;
    let {data, m} = this.state;

    data =  data.filter(function(val) { return !isEmpty(val.items)})

    let total = 0;
    return <Modal
        show={this.state.confirm_show}
        onHide={() => {
          this.setState({confirm_show: false})
        }}
        dialogClassName="modal-90w"
        aria-labelledby="example-custom-modal-styling-title"
        backdrop="static"
        scrollable={true}
      >
        <Modal.Header closeButton>
          <Modal.Title id="example-custom-modal-styling-title">
            ยืนยันการส่งโพย
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <p>
          หวยยี่กี รอบที่ {match.params.id}
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
          <Form.Group controlId="exampleForm.ControlTextarea1">
            <Form.Label>หมายเหตุ</Form.Label>
            <Form.Control as="textarea" rows="2" />
          </Form.Group>
        </Modal.Body>
        <Modal.Footer>
          <Button 
           onClick={() =>{
            this.handleBetClick() 
          } }>กดยืนยันการส่งโพย</Button>
        </Modal.Footer>
      </Modal>
  }

  render() {
    let {user, match} = this.props; 
    let {error, error_message, total, equal_price, otp, m} = this.state;

    let btnC = <Button variant="outline-primary" onClick={() =>this.handleNumPad("C")} disabled>C</Button>;
    let btnD = <Button variant="outline-primary" onClick={() =>this.handleNumPad("D")} disabled>D</Button>;
    if(otp.toString().length > 0){
      btnC = <Button variant="outline-primary" onClick={() =>this.handleNumPad("C")}>C</Button> ;
      btnD = <Button variant="outline-primary" onClick={() =>this.handleNumPad("D")}>D</Button>;
    }

    this.loadingOverlayActive();

    // m: {mode:3, mi:['type_3_up']}
    return (
      <div>
        { this.state.confirm_show ? this.showConfirmModal() : '' }
      <Container style={{minHeight: 600}}>
        <Row>
          <Col style={{border: '1px solid #61dafb'}} md={12} xs={12}>
            <div>
              หวยยี่กี รอบที่ {match.params.id}
            </div>
            <div>
              เวลาเหลือ 10:14:13
            </div>
          </Col>
        </Row>
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

            {/* <Button
              variant="outline-danger"
              onClick={() =>this.handleClearClick()}>
              ล้างข้อมูล
            </Button> */}
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
            <div style={{ border: '1px solid #61dafb' }}>
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
                value={equal_price} 
                />
            </div>
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

// export default YeekeePage;
const mapStateToProps = (state, ownProps) => {
	if(!state._persist.rehydrated){
		return {};
  }
  
  if(state.auth.isLoggedIn){
    return { logged_in: true, user: state.auth.user};
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

export default connect(mapStateToProps, mapDispatchToProps)(YeekeePage)