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
    
    if(this.state.destination != null) {
      this.handleBooking();
      this.props.history.push(path);
    }
  }

  nextPath2(path) {
    this.props.history.push(path);
  }

  render() {

    return (
      <Card className={ useStyles.root }>
        <CardContent>
          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath2('/deposit')}>
              ฝากเงิน
            </Button>
          </div>
          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath2('/withdraw')}>
              ถอนเงิน
            </Button>
          </div>
          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath2('/add-bank')}>
            เพิ่มบัญชีธนาคาร
            </Button>
          </div>
          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath2('/lottery-list')}>
              แทงหวย
            </Button>
          </div>

          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath2('/affiliate-page')}>
              แนะนําเพือน
            </Button>
          </div>

          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath2('/statement')}>
              รายงานการเงิน
            </Button>
          </div>

          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath2('/request-all')}>
              สถานะ ฝากเงิน
            </Button>
          </div>

          <div style={{padding:'5px'}}>
            <Button type="primary" size="large" onClick={ () => this.nextPath2('/contact-us')}>
              ติดต่อเรา
            </Button>
          </div> 
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
    return { logged_in: true };
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