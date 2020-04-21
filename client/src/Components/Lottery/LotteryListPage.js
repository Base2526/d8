import React, { Component } from 'react';

import Container from 'react-bootstrap/Container'
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'

var styles = {
  root: {
    display: "block"
  },
  pStyle: {
      fontSize: '30px',
      textAlign: 'center',
      backgroundColor: 'red',
      padding: '20px',
      borderStyle: 'ridge',
      borderWidth: '1px',
      borderColor: 'coral',
      marginBottom: '10px'
  },
  item: {
    color: "black",
    complete: {
      textDecoration: "line-through"
    },
    due: {
      color: "red"
    }
  },
}

class LotteryListPage extends Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {
  }

  handleClick = (i) => {
    let {history} = this.props
    console.log(i);

    switch(parseInt(i)){
      case 1:{
        history.push('/lottery-list/government')
        break;
      }
      case 2:{
        history.push('/lottery-list/yeekee-list')
        break;
      }
    }

    
  }

  getItemList (items) {
    var divList = [];
    for (var i = 1; i <= items.length; i++) {
      divList.push(<Col xs={6} md={3} key={i}>
                  <div >
                    <button data-id={i} onClick={e => this.handleClick(e.target.getAttribute('data-id'))}>รอบที่ {i}</button>
                  </div>
                </Col>);
    }
    return divList;
  }

  render() {
    let {history} = this.props
    // return (<div style={{minWidth: 275}}>
    //           <div>
    //             <button data-id='9' onClick={ () => history.push('/lottery-list/government')}>Government page</button>
    //           </div>
    //           <div>
    //             <button data-id='9' onClick={ () => history.push('/lottery-list/yeekee-list')}>Yeekee list page</button>
    //           </div>  
    //         </div>);

    let lotterys = [{'id':1, 'name': 'หวยรัฐบาลไทย'}, 
                    {'id':2, 'name': 'จับยี่กี VIP'}, 
                    /*{'id':3, 'name': 'หวยลาว'}, 
                    {'id':4, 'name': 'หวยฮานอย'}, 
                    {'id':5, 'name': 'หวยฮานอย VIP'}, 
                    {'id':6, 'name': 'หวยมาเลย์'}, 
                    {'id':7, 'name': 'หวย ธกส.'}, 
                    {'id':8, 'name': 'หวย ออมสิน'}*/ ];

    return( <Container>
              <div>หวย</div>
              <Row style={styles.pStyle}>
                {
                lotterys.map((item, i) => {
                  return  <Col xs={6} md={3} key={item.id}>
                            <div >
                              <button data-id={item.id} onClick={e => this.handleClick(e.target.getAttribute('data-id'))}>{item.name}</button>
                            </div>
                          </Col>
                })
                }
              </Row>
            </Container>);
  }
}

export default LotteryListPage;