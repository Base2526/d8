import React, { Component } from 'react';
import Container from 'react-bootstrap/Container'
import Button from 'react-bootstrap/Button'
import { connect } from 'react-redux'

import { Base64 } from 'js-base64';

import {loadingOverlayActive} from '../../actions/huay'
import { headers, isEmpty } from '../Utils/Config';

class HomePage extends Component {
  constructor(props) {
    super(props);
    
    this.state = {
      type: 'ow',
      from : 'Bangalore',
      journeyDate: null,
      destination: null,
      lat:null,
      lng:null,
      distance: null,
      journeyTime: null,
      
    };
  }

  componentDidMount() {
    // let en_password = Base64.encode(Base64.encode( JSON.stringify({ 'Content-Type': 'application/json' }) ));
    // console.log(en_password);

    // let de_password = Base64.decode(Base64.decode('ZXlKRGIyNTBaVzUwTFZSNWNHVWlPaUpoY0hCc2FXTmhkR2x2Ymk5cWMyOXVJbjA9'));
    // console.log(de_password);
    // 

    // let local_storage_headers =;
    // console.log( JSON.parse(Base64.decode(Base64.decode(localStorage.getItem('headers')))) );
  
  }

  nextPath(path) {
    this.props.history.push(path);
  }

  render() {
    let {user} = this.props
    
    console.log(user);
    let is_dealer = undefined;
    if(!isEmpty(user)){
      is_dealer = user.roles.find((element) => { return element === 'lottery_dealer' })

      console.log(user);

     
      let chit_length = 0;
      if(!isEmpty(user.chits)){
        chit_length =user.chits.length;
      }
      // user.chits.length;
      // console.log(user.chits.length);
      return (
        <Container style={{minHeight: 600}}>
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/deposit')}>
                ฝากเงิน
              </Button>
            </div>
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/withdraw')}>
                ถอนเงิน
              </Button>
            </div>
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/add-bank')}>
              เพิ่มบัญชีธนาคาร
              </Button>
            </div>
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/lottery-list')}>
                แทงหวย
              </Button>
            </div>
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/list-chit-customer')}>
              รายการโพยทั้งหมด (สำหรับลูกค้า) ( { chit_length } )
              </Button>
            </div>
  
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/affiliate-page')}>
                แนะนําเพือน
              </Button>
            </div>
  
            {/* <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/statement')}>
                รายงานการเงิน
              </Button>
            </div>
   */}
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/request-all')}>
              รายงานการ ฝาก/ถอนเงิน
              </Button>
            </div>
  
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/contact-us')}>
                ติดต่อเรา
              </Button>
            </div> 
  
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/help')}>
              วิธีการใช้งาน
              </Button>
            </div> 
  
            {
              !is_dealer ?'':<div style={{padding:'5px'}}>
                                <Button type="primary" size="large" onClick={ () => this.nextPath('/setting-dealers')}>
                                ตั้งค่าสำหรับเจ้ามือหวย
                                </Button>
                              </div> 
            }
          </Container> 
      );
    }else{
      return (
          <Container style={{minHeight: 600}}>
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/contact-us')}>
                ติดต่อเรา
              </Button>
            </div> 
            <div style={{padding:'5px'}}>
              <Button type="primary" size="large" onClick={ () => this.nextPath('/help')}>
              วิธีการใช้งาน
              </Button>
            </div> 
           </Container> 
      )
    }
  }
}

const mapStateToProps = (state, ownProps) => {
	if(!state._persist.rehydrated){
		return {};
  }
  
  if(state.auth.isLoggedIn){

    console.log(state.auth.user);
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

export default connect(mapStateToProps, mapDispatchToProps)(HomePage)