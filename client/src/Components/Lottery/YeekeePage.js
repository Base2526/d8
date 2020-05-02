import React, { Component } from 'react';
import Alert from 'react-bootstrap/Alert'
import { connect } from 'react-redux'
import Container from 'react-bootstrap/Container'
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'
import Button from 'react-bootstrap/Button'
import Form from 'react-bootstrap/Form'
import ReactList from 'react-list';

import { headers, showToast } from '../Utils/Config';
import { loadingOverlayActive } from '../../actions/huay'

class YeekeePage extends Component {
  constructor(props) {
    super(props);
    this.state = {
      error: false,
      error_message:'',
      book_makers:[],
      total: 0,
    }

    this.handleItemClick = this.handleItemClick.bind(this);
    this.renderItem = this.renderItem.bind(this);
    this.handleItemChange = this.handleItemChange.bind(this);
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

  handleItemChange(event) {

    let {user} = this.props;
    let {book_makers} = this.state;
    book_makers[event.target.id] = {...book_makers[event.target.id], quantity: event.target.value}
    
    let total = 0;
    book_makers.map(function(item, i){
      total+=parseInt(item.quantity)
    })
    console.log(total)

    if(total> user.credit_balance){
      this.setState({book_makers, total, error:true, error_message: 'เครดิตคงเหลือไม่พอ'});
    }else{
      this.setState({book_makers, total});
    }
  }    

  renderItem(index, key) {
    console.log(this.state.book_makers[index])
    return <div key={key}>
              <div>{this.state.book_makers[index].number}</div>
              <div>
                <input 
                  type="number"
                  placeholder="จำนวนเงิน" 
                  // key={index} 
                  // data-id={index}
                  min="1"
                  id={index}
                  onInput={this.handleItemChange} 
                  defaultValue={this.state.book_makers[index].quantity} 
                  />
                <div>
                  {this.state.book_makers[index].quantity*this.state.book_makers[index].price}
                </div>
                <button key={index} data-id={index} style={{backgroundColor: 'red'}} onClick={e => this.handleItemClick(e.target.getAttribute('data-id'))}>
                ยกเลิก
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
      total+=item.quantity
    })

    if(total> user.credit_balance){
      this.setState({book_makers, total, error:true, error_message: 'เครดิตคงเหลือไม่พอ'});
    }else{
      this.setState({book_makers, total});
    }
  } 


  /*
  
  เครดิตคงเหลือ : 0 ฿
ยอดเดิมพันทั้งหมด : 80,200 ฿
  */
  render() {
    let {user} = this.props;
    let {error, error_message, book_makers, total} = this.state;

    return (
      <Container>
        <Row>
          <Col md={12} xs={12}>
            { error ? <Alert variant={'warning'}>{error_message}</Alert> : '' }
          </Col>
        </Row>
        <Row>
          <Col style={{backgroundColor: '#20c997'}} md={12} xs={12}>
          <div>
          เครดิตคงเหลือ : {user.credit_balance} ฿
            </div>
          <div>
          ยอดเดิมพันทั้งหมด : {total} ฿
          </div>
          </Col>
        </Row>
        <Row>
          <Col style={{backgroundColor: 'red'}} md={4} xs={12}>
            <div>
              <div>รายการแทง ({this.state.book_makers.length})</div>
              <div style={{overflow: 'auto', maxHeight: 400}}>
                <ReactList
                  itemRenderer={this.renderItem}
                  length={this.state.book_makers.length}
                  type='uniform'
                />
              </div>
            </div>
          </Col>
          <Col style={{backgroundColor: 'green'}} md={8} xs={12}>
          <Button
            variant="primary"
            // disabled={isLoading}
            onClick={() =>this.handleClick()}>
            เพิ่มตัวเลข
          </Button>
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