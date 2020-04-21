import React, { Component } from 'react';
import { makeStyles } from '@material-ui/core/styles';
import Card from '@material-ui/core/Card';
import CardContent from '@material-ui/core/CardContent';
import Typography from '@material-ui/core/Typography';
// import InputAutocomplete from './InputAutocomplete/InputAutocomplete';
import { Input, DatePicker, Radio, Button} from 'antd';
import { RightCircleOutlined } from '@ant-design/icons';
import 'antd/dist/antd.css';
import '../RideSelect/RideSelect.css';
import ls from 'local-storage';

import { Grid, Row, Col } from 'react-flexbox-grid';


import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import 'react-tabs/style/react-tabs.css';

import history from '../../history';
import { Redirect, Link} from 'react-router-dom';

class YeekeeListPage extends Component {
  constructor(props) {
    super(props);
    
    this.state = {};

    this.handleClick = this.handleClick.bind(this);
  }

  componentDidMount() {
  }

  handleClick = (i) => {
    let {history} = this.props
    // console.log(i);

    history.push('/lottery-list/yeekee-list/' + i)
  }

  getItemList () {
    var divList = [];
    for (var i = 1; i <= 88; i++) {
      divList.push(<Col xs={6} md={3} key={i}>
                  <div >
                    <button data-id={i} onClick={e => this.handleClick(e.target.getAttribute('data-id'))}>รอบที่ {i}</button>
                  </div>
                </Col>);
    }
    return divList;
  }

  render() {
    console.log(this.state.selected);
    return (
      <Grid fluid>
        <Row>
         {this.getItemList()}
        </Row>
      </Grid>
    );
  }
}

export default YeekeeListPage;