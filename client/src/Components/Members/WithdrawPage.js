import React, { Component } from 'react';
import Alert from 'react-bootstrap/Alert'
import Button from 'react-bootstrap/Button'
import Container from 'react-bootstrap/Container'
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'

import Form from 'react-bootstrap/Form'
import InputGroup from 'react-bootstrap/InputGroup'

class WithdrawPage extends Component {
  constructor(props) {
    super(props);
    this.state = {
      validated:false
    }
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  componentDidMount() {
  }

  handleSubmit = (event) => {
    const form = event.currentTarget;
    if (form.checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    }
    this.setState({validated:true});
  };
  
  render() {
    let { validated, 
          number_withdraw} = this.state;

    return (<Form noValidate validated={validated} onSubmit={this.handleSubmit}>
              <Container>
                <Row>
                  <div>เลือกบัญชีธนาคารของท่าน</div>
                  <div>*กรุณากดคลิกบัญชีด้านล่างตามที่ท่านต้องการ</div>
                </Row>
                <Row>
                  <div>จำนวนเงินที่ถอนได้</div>
                  <div>3000.00 บาท</div>
                </Row>
                <Row>
                  <Form.Group controlId="number_withdraw">
                    <Form.Label>จำนวนเงินที่ต้องการถอน</Form.Label>
                    <Form.Control 
                      type="number" 
                      placeholder="0.00" 
                      required 
                      value={number_withdraw} onChange={this.handleChange} />
                    <Form.Control.Feedback type="invalid">
                    จำนวนเงินที่ต้องการถอน
                    </Form.Control.Feedback>
                  </Form.Group>
                </Row>    
                <Row>
                  <Form.Group controlId="annotation">
                    <Form.Label>หมายเหตุ</Form.Label>
                      <Form.Control 
                        as="textarea" 
                        rows="3" 
                        onChange={this.handleChange} />
                  </Form.Group>
                </Row>
                <Row>
                  <Button type="submit">ถอนเงิน</Button>
                </Row>
              </Container>
            </Form>);
  }
}

export default WithdrawPage;