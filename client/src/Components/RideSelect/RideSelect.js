import React, { Component } from 'react';
import { makeStyles } from '@material-ui/core/styles';
import { connect } from 'react-redux'
import Card from '@material-ui/core/Card';
import CardContent from '@material-ui/core/CardContent';
import { Input, DatePicker, Radio, Button, Row, Col } from 'antd';
import { RightCircleOutlined } from '@ant-design/icons';
import 'antd/dist/antd.css';
import './RideSelect.css';
import ls from 'local-storage';
import history from '../../history';

// import { ToastContainer, toast } from 'react-toastify';
// import 'react-toastify/dist/ReactToastify.css';

import {loadingOverlayActive} from '../../actions/huay'

const useStyles = makeStyles({
  root: {
    minWidth: 275,
  },
  title: {
    fontSize: 14,
  },
  pos: {
    marginBottom: 12,
  },
  box_width: {
    maxWidth: 300,
  }
});

class RideSelect extends Component {
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
    ls.set('rideType', this.state.type);
    ls.set('origin', this.state.from);

    this.props.loadingOverlayActive(false);


  }

  destinationChangeHandler(event) {
    this.setState({ to: event.target.value });
  }

  dateChangeHandler(date, dateStr) {
    ls.set('journeyDate', dateStr);
    this.setState({ journeyDate: dateStr });
  }

  handleBooking = async e => {
    // e.preventDefault();
    const response = await fetch('/api/price', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ lat: this.state.lat, lng: this.state.lng }),
    });
    const body = await response.json();
    
    ls.set('distance', body.distance);
    ls.set('journeyTime', body.days);
  };

  continueHandler = () => {
    history.push('/second-page')
  }

  getDestVal = (val) => {
    this.setState({destination : val[0], lat : val[1].lat, lng : val[1].lng})
    ls.set('destination', this.state.destination)
    ls.set('lat', this.state.lat)
    ls.set('lng', this.state.lng)
      
    console.log(this.state.destination);
  }

  nextPath(path) {
    this.props.history.push(path);
  }

  // notifyA(){
  //   toast.success('Wow so easy !', {containerId: 'A', autoClose: 3000});
  // }

  render() {
    let {user} = this.props

    if(!user){
      this.nextPath('/login')
      return;
    }

    console.log(user)
    let is_dealer = user.roles.find((element) => {
                      return element === 'lottery_dealer';
                    })

    return (
      <Card className={ useStyles.root }>

        <CardContent>
          
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
            <Button type="primary" size="large" onClick={ () => this.nextPath('/lottery-list-chits')}>
            รายการโพยทั้งหมด (สำหรับลูกค้า)
            </Button>
          </div>

          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath('/affiliate-page')}>
              แนะนําเพือน
            </Button>
          </div>

          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath('/statement')}>
              รายงานการเงิน
            </Button>
          </div>

          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath('/request-all')}>
              สถานะ ฝากเงิน
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
        </CardContent>
      </Card>
    );
  }
}

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

export default connect(mapStateToProps, mapDispatchToProps)(RideSelect)