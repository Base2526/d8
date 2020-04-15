import React, { Component } from 'react';
import { makeStyles } from '@material-ui/core/styles';
import Card from '@material-ui/core/Card';
import CardContent from '@material-ui/core/CardContent';
import Typography from '@material-ui/core/Typography';
// import InputAutocomplete from './InputAutocomplete/InputAutocomplete';
import { Input, DatePicker, Radio, Button, Row, Col } from 'antd';
import { RightCircleOutlined } from '@ant-design/icons';
import 'antd/dist/antd.css';
import '../RideSelect/RideSelect.css';
import ls from 'local-storage';


import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import 'react-tabs/style/react-tabs.css';

import history from '../../history';
import { Redirect, Link} from 'react-router-dom';

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

class LotteryListPage extends Component {
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
      selected: [],



      tabIndex: 1
    };

  }

  componentDidMount() {
    ls.set('rideType', this.state.type);
    ls.set('origin', this.state.from);


    this.handleClick = this.handleClick.bind(this);
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

  handleClick = (i) => {
    // console.log('this is:', i);
    let find = this.state.selected.find(element => element == i);
    if(!find){
        var newArray = this.state.selected.slice();    
        newArray.push(i);   
        this.setState({selected:newArray})
    }else{
        let filter = this.state.selected.filter(j => i != j);
        this.setState({selected:filter})
    }
  }

  render() {

    console.log(this.state.selected);

    let {history} = this.props
    return (
    //   <Card className={ useStyles.root }>
    //     <CardContent>

        <div style={{minWidth: 275}}>
          <div>
            <button data-id='9' onClick={ () => history.push('/government')}>Government page</button>
          </div>
          <div>
            <button data-id='9' onClick={ () => history.push('/yeekee-list')}>Yeekee list page</button>
          </div>  
        </div>
    //     </CardContent>
    //   </Card>
    );
  }
}

export default LotteryListPage;