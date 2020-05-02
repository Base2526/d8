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

import NumericInput from 'react-numeric-input';

import { headers, showToast, isEmpty } from '../Utils/Config';
import { loadingOverlayActive } from '../../actions/huay'

class YeekeePage extends Component {
  constructor(props) {
    super(props);
    this.state = {
      error: false,
      error_message:'',
      book_makers:[],
      total: 0,
      equal_price:0,

      is_active: false,
    }

    this.handleItemClick = this.handleItemClick.bind(this);
    this.renderItem = this.renderItem.bind(this);
    // this.handleItemOnInput = this.handleItemOnInput.bind(this);
    this.handlerItemChange = this.handlerItemChange.bind(this);

    // this.handlerEqualOnInput = this.handlerEqualOnInput.bind(this);
    this.handlerEqualChange = this.handlerEqualChange.bind(this);

    this.handleBetClick = this.handleBetClick.bind(this);
    this.handleClearClick = this.handleClearClick.bind(this);

    this.onCloseAlert  = this.onCloseAlert.bind(this);
  }

  componentDidMount() {
  }

  handleItemClick = (index) => {
    var tmp_book_makers = [...this.state.book_makers]; 
    if (index !== -1) {
      tmp_book_makers.splice(index, 1);
      this.setState({book_makers: tmp_book_makers});

      showToast('success', "ลบรายการ เรียบร้อย");
    }
  }

  /*
  handleItemOnInput(event) {
    let {user} = this.props;
    let {book_makers} = this.state;
    book_makers[event.target.id] = {...book_makers[event.target.id], quantity: (isEmpty(event.target.value) || event.target.value < 1) ? 1 : event.target.value}
    
    let total = 0;
    book_makers.map(function(item, i){
      let quantity = parseInt(item.quantity);
      if(quantity < 1){
        quantity = parseInt(1);
      }
      console.log(quantity)
      total+=quantity
    })
    console.log(total)

    if(total> user.credit_balance){
      this.setState({book_makers, total, error:true, error_message: 'เครดิตคงเหลือไม่พอ'});
    }else{
      this.setState({book_makers, total});
    }
  }    
  */

  handlerItemChange(newVal, valStr, event){
    // console.log(newVal, valStr, event.id);

    let {user} = this.props;
    let {book_makers} = this.state;
    book_makers[event.id] = {...book_makers[event.id], quantity: newVal}
    
    let total = 0;
    book_makers.map(function(item, i){
      let quantity = parseInt(item.quantity);
      if(quantity < 1){
        quantity = parseInt(1);
      }
      console.log(quantity)
      total+=quantity
    })
    console.log(total)

    if(total> user.credit_balance){
      // this.setState({book_makers, total, error:true, error_message: 'เครดิตคงเหลือไม่พอ'});

      this.setState({book_makers, total});

      showToast('warn', "เครดิตคงเหลือไม่พอ", 3000);
    }else{
      this.setState({book_makers, total});
    }
  }

  renderItem(index, key) {
    console.log(this.state.book_makers[index])
    return <div key={key}>
              <div>{this.state.book_makers[index].number}</div>
              <div>
                {/* <input 
                  type="number"
                  placeholder="จำนวนเงิน" 
                  // key={index} 
                  // data-id={index}
                  min="1"
                  id={index}
                  onInput={this.handleItemChange} 
                  defaultValue={this.state.book_makers[index].quantity} 
                  /> */}
                <NumericInput 
                  min={1} 
                  id={index}
                  // className="form-control" 
                  // onInput={this.handleItemOnInput} 
                  onChange={this.handlerItemChange}
                  // defaultValue={this.state.book_makers[index].quantity}
                  value={this.state.book_makers[index].quantity} 
                  />
                <div>
                  ชนะ : {this.state.book_makers[index].quantity*this.state.book_makers[index].price}
                </div>
                <button key={index} data-id={index} style={{backgroundColor: 'red'}} onClick={e => this.handleItemClick(e.target.getAttribute('data-id'))}>
                X
                </button>
              </div>
            </div>;
  }

  handleClick(){
    let {user} = this.props;
    let {book_makers} = this.state;

    const rand = Math.floor(Math.random() * 100) + 1 ;

    let item =  {
                  type:1,
                  number:rand,
                  quantity:1,
                  price:90
                }

    book_makers = [...book_makers, item];
    console.log(book_makers);


    let total = 0;
    book_makers.map(function(item, i){
      total+=parseInt(item.quantity)
    })

    if(total> user.credit_balance){
      // this.setState({book_makers, total, error:true, error_message: 'เครดิตคงเหลือไม่พอ'});
    
      this.setState({book_makers, total,});

      showToast('warn', "เครดิตคงเหลือไม่พอ", 3000);
    }else{
      this.setState({book_makers, total});
    }
  } 

  handleBetClick(){
    console.log('handleBetClick');

    this.setState({is_active: true})
  }

  handleClearClick(){
    console.log('handleClearClick');
  }

  loadingOverlayActive(){
    this.props.loadingOverlayActive(this.state.is_active);
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
    this.setEqualPrice(newVal)
  }

  setEqualPrice(price){
    let {book_makers} = this.state;
    let tmp_book_makers = book_makers;
    book_makers.map(function(item, i){
      tmp_book_makers[i] = {...item, quantity:price}
    })

    this.setState({book_makers:tmp_book_makers, equal_price: price})
  }

  render() {
    let {user} = this.props;
    let {error, error_message, book_makers, total, equal_price} = this.state;

    console.log(equal_price)
    this.loadingOverlayActive();
    return (
      <Container style={{minHeight: 600}}>
        <Row>
          <Col style={{backgroundColor: 'green'}} md={12} xs={12}>
          { error ? <Alert variant={'warning'} dismissible onClose={this.onCloseAlert}>{error_message}</Alert> : '' }
          </Col>
        </Row>
        <Row style={{ border: '1px solid #61dafb' }}>
          <Col style={{ border: '1px solid #61dafb' }} md={8} xs={12}>
            <div>
            เครดิตคงเหลือ : {user.credit_balance} ฿
              </div>
            <div>
            ยอดเดิมพันทั้งหมด : {total} ฿
            </div>
          </Col>
          <Col style={{ border: '1px solid #61dafb' }} md={4} xs={12}>
            <Button
              variant="primary"
              onClick={() =>this.handleBetClick()}>
              แทงพนัน
            </Button>

            <Button
              variant="outline-danger"
              onClick={() =>this.handleClearClick()}>
              ล้างข้อมูล
            </Button>
          </Col>
        </Row>
        <Row>
          <Col style={{ border: '1px solid #BF553F' }} md={4} xs={12}>
            <div>รายการแทง ({this.state.book_makers.length})</div>
            <div style={{overflow: 'auto', maxHeight: 400, minHeight: 400, border: '1px solid #708024'}}>
              <ReactList
                itemRenderer={this.renderItem}
                length={this.state.book_makers.length}
                type='uniform'
              />
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
            <div>
              <Button variant="outline-primary">3 ตัวบน</Button>
              <Button variant="outline-primary">3 ตัวโต๊ด</Button>
              <Button variant="outline-primary">2 ตัวบน</Button>
              <Button variant="outline-primary">2 ตัวล่าง</Button>
              <Button variant="outline-primary">3 ตัวกลับ</Button> 
              <Button variant="outline-primary">2 ตัวกลับ</Button> 
              <Button variant="outline-primary">วิ่งบน</Button> 
              <Button variant="outline-primary">วิ่งล่าง</Button> 
            </div>
            <div>
              {/* cursor: default */}
              {/* <label style={{width: 50, height: 50, cursor: 'default', border: '1px solid #61dafb'}}></label> */}
              <input type="text" style={{width: 50, height: 50}} />
              <input type="text" style={{width: 50, height: 50}} />
              <input type="text" style={{width: 50, height: 50}} />
            </div>
          </Col>
        </Row>
      </Container> 
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
    loadingOverlayActive: (isActivie) =>{
      dispatch(loadingOverlayActive(isActivie))
    }
	}
}

export default connect(mapStateToProps, mapDispatchToProps)(YeekeePage)