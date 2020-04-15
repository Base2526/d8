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

class WithdrawPage extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  componentDidMount() {
   
  }
  
  render() {
    return (
        <div style={{minWidth: 275}}>
            <div>
            WithdrawPage
            </div>
        </div>
    );
  }
}

export default WithdrawPage;