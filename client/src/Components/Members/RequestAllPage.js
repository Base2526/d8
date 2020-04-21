import React, { Component } from 'react';
import Tabs from 'react-bootstrap/Tabs'
import Tab from 'react-bootstrap/Tab'

class RequestAllPage extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  componentDidMount() {
  }
  
  render() {
    return (
      <Tabs defaultActiveKey="all" id="uncontrolled-tab-example">
        <Tab eventKey="all" title="วันนี้">
          <div>
          ทั้งหมด
          </div>
        </Tab>
        <Tab eventKey="deposit" title="ฝาก">
          <div>
          ฝาก
          </div>
        </Tab>
        <Tab eventKey="withdraw" title="ถอน">
          <div>
          ถอน
          </div>
        </Tab>
      </Tabs>
    );
  }
}

export default RequestAllPage;