import React, { Component } from 'react';

import Alert from 'react-bootstrap/Alert'
import Button from 'react-bootstrap/Button'
import Container from 'react-bootstrap/Container'
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'

import Form from 'react-bootstrap/Form'
import InputGroup from 'react-bootstrap/InputGroup'


import DatePicker from "react-datepicker";
 import "react-datepicker/dist/react-datepicker.css";

class DepositPage extends Component {
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
          select_bank, 
          select_bank_customer,
          amount_transferred,
          annotation} = this.state
    return (<Form noValidate validated={validated} onSubmit={this.handleSubmit}>
              <Container>
                <Row>
                  <div>
                    <div>Step 1 : เลือกบัญชีธนาคารของเว็บฯ</div>
                    <div>
                      <Form.Group controlId="select_bank">
                        <Form.Label>เลือกธนาคาร</Form.Label>
                        <Form.Control 
                            as="select" 
                            required 
                            value={select_bank}  
                            onChange={this.handleChange}>
                            <option value="">--เลือก--</option>
                            <option value="10">ธ. กรุงเทพ</option>
                            <option value="20">ธ. กสิกรไทย</option>
                            <option value="30">ธ. กรุงไทย</option>
                        </Form.Control>
                        <Form.Control.Feedback type="invalid">
                            กรุณาเลือกธนาคาร
                        </Form.Control.Feedback>
                      </Form.Group>
                    </div>
                  </div>
                </Row>
                <Row>
                  <div>
                    <div>Step 2 : โอนเงินเพื่อเติมเครดิต</div>
                    <div>
                      โอนขั้นตํ่า "ครั้งละ 20 บาท" ถ้าโอนตํ่ากว่า 20 บาทเงินของท่านจะไม่เข้าระบบ
                      และไม่สามารถคืนได้
                    </div>

                    <div>
                      คำเตือน! กรุณาใช้บัญชีที่ท่านผูกกับ XXX ในการโอนเงินเท่านั้น
                    </div>

                    <div>
                      เมือท่านทำการโอนเงินไปยังบัญชีข้างต้นเรียบร้อยแล้ว (เก็บสลิปการโอนไว้ทุกครั้ง)
                      "คลิกปุ่มด้านล่าง" เพือแจ้งการโอนเงิน
                    </div>

                  </div>
                </Row>
                <Row>
                  <div>
                    <div>Step 3 : แจ้งรายละเอียดการโอนเงิน</div>
                    <div>กรุณาโอนเงินเข้าบัญชีด้านบน ภาญใน 5 นาที</div>
                    <div>เลือกบัญชีธนาคารของลูกค้า</div>
                    <div>
                      <Form.Group controlId="select_bank_customer">
                        <Form.Label>เลือกธนาคาร</Form.Label>
                        <Form.Control 
                            as="select" 
                            required 
                            value={select_bank_customer}  
                            onChange={this.handleChange}>
                            <option value="">--เลือก--</option>
                            <option value="10">ธ. กรุงเทพ</option>
                            <option value="20">ธ. กสิกรไทย</option>
                            <option value="30">ธ. กรุงไทย</option>
                        </Form.Control>
                        <Form.Control.Feedback type="invalid">
                            กรุณาเลือกธนาคาร
                        </Form.Control.Feedback>
                      </Form.Group>
                    </div>
                    
                    <div>
                      <Form.Group controlId="select_function">
                        <Form.Label>กรุณาเลือกช่องทางการโอน</Form.Label>
                        <Form.Control 
                            as="select" 
                            required 
                            value={select_bank_customer}  
                            onChange={this.handleChange}>
                            <option value="">--เลือก--</option>
                            <option value="10">ATM โอนจากตู้กดเงินสด</option>
                            <option value="20">CDM</option>
                            <option value="30">Counter Cashier</option>
                            <option value="30">Internet Banking</option>
                            <option value="30">Mobile Banking</option>
                        </Form.Control>
                        <Form.Control.Feedback type="invalid">
                        กรุณาเลือกช่องทางการโอน
                        </Form.Control.Feedback>
                      </Form.Group>
                    </div>
                    <div>
                      <Form.Group controlId="select_function">
                        <Form.Label>จำนวนเงินที่โอน</Form.Label>
                        <Form.Control 
                          type="number" 
                          placeholder="จำนวนเงินที่โอน" 
                          required 
                          value={amount_transferred} onChange={this.handleChange} />
                        <Form.Control.Feedback type="invalid">
                        จำนวนเงินที่โอน
                        </Form.Control.Feedback>
                      </Form.Group>
                    </div>

                    <div>
                      <Form.Group controlId="select_function">
                        <Form.Label>วันที่โอน</Form.Label>
                        {/* <Form.Control 
                          type="number" 
                          placeholder="จำนวนเงินที่โอน" 
                          required 
                          value={amount_transferred} onChange={this.handleChange} /> */}

                        {/* <DatePicker id="example-datepicker" value={new Date().toISOString()} onChange={this.handleChange} /> */}
                        
                        <DatePicker
                            selected={new Date()}
                            onChange={this.handleChange}
                            class="form-control"
                          />
                        <Form.Control.Feedback type="invalid">
                        วันที่โอน
                        </Form.Control.Feedback>
                      </Form.Group>
                    </div>

                    <div>
                      <Form.Group controlId="select_function">
                        <Form.Label>เวลาที่โอน</Form.Label>
                        {/* <Form.Control 
                          type="number" 
                          placeholder="จำนวนเงินที่โอน" 
                          required 
                          value={amount_transferred} onChange={this.handleChange} /> */}

                        {/* <DatePicker id="example-datepicker" value={new Date().toISOString()} onChange={this.handleChange} /> */}
                        
                        <DatePicker
                          selected={new Date()}
                          // onChange={date => setStartDate(date)}
                          showTimeSelect
                          showTimeSelectOnly
                          timeIntervals={15}
                          timeCaption="Time"
                          dateFormat="h:mm aa"
                        />
                        <Form.Control.Feedback type="invalid">
                        เวลาที่โอน
                        </Form.Control.Feedback>
                      </Form.Group>
                    </div>
                    <div>
                      <Form.Group controlId="annotation">
                        <Form.Label>หมายเหตุ</Form.Label>
                          <Form.Control 
                            as="textarea" 
                            rows="3" 
                            value={annotation} 
                            onChange={this.handleChange} />
                        <Form.Control.Feedback type="invalid">
                        วันที่โอน
                        </Form.Control.Feedback>
                      </Form.Group>
                    </div>
                  </div>
                </Row>
                <Row>
                  <Button type="submit">ยืนยันการแจ้งโอนเงิน</Button>
                </Row>
              </Container>
            </Form>);
  }

  /*
  render() {
    const { history, classes } = this.props;
    let {bank} = this.state;
    console.log(bank);
    return (
        <form onSubmit={this.submitForm} >    
        <div style={{minWidth: 275}}>
            <div>
            แจ้งเติมเครดิต
            </div>

            <div>
              <div>
                <div>Step 1 : เลือกบัญชีธนาคารของเว็บฯ</div>
                <FormControl className={classes.formControl}>
                  <InputLabel id="demo-simple-select-label">เลือกธนาคาร</InputLabel>
                  <Select
                      labelId="demo-simple-select-label"
                      id="demo-simple-select"
                      value={this.state.bank}
                      onChange={this.handleSelectChange}>
                      <MenuItem value="">
                          <em>None</em>
                      </MenuItem>
                      <MenuItem value={10}>ธ. กรุงเทพ</MenuItem>
                      <MenuItem value={20}>ธ. กสิกรไทย</MenuItem>
                      <MenuItem value={30}>ธ. กรุงไทย</MenuItem>
                      </Select>
                </FormControl>
                <br />
              </div>
              <div>
                <div>Step 2 : โอนเงินเพื่อเติมเครดิต</div>
                <div>
                  โอนขั้นตํ่า "ครั้งละ 20 บาท" ถ้าโอนตํ่ากว่า 20 บาทเงินของท่านจะไม่เข้าระบบ
                  และไม่สามารถคืนได้
                </div>

                <div>
                  คำเตือน! กรุณาใช้บัญชีที่ท่านผูกกับ XXX ในการโอนเงินเท่านั้น
                </div>

                <div>
                  เมือท่านทำการโอนเงินไปยังบัญชีข้างต้นเรียบร้อยแล้ว (เก็บสลิปการโอนไว้ทุกครั้ง)
                  "คลิกปุ่มด้านล่าง" เพือแจ้งการโอนเงิน
                </div>
                <Button type="submit" variant="contained">เมือโอนเงินแล้วคลิกที่นี้</Button>
              </div>
            
              <div>
                <div>Step 3 : แจ้งรายละเอียดการโอนเงิน</div>
                <div>กรุณาโอนเงินเข้าบัญชีด้านบน ภาญใน 5 นาที</div>
                <div>
                  <div>เลือกบัญชีธนาคารของลูกค้า</div>
                  <FormControl className={classes.formControl}>
                    <InputLabel id="demo-simple-select-label">กรุณาเลือกธนาคาร</InputLabel>
                    <Select
                        labelId="demo-simple-select-label"
                        id="demo-simple-select"
                        value={this.state.bank}
                        onChange={this.handleSelectChange}>
                        <MenuItem value="">
                            <em>None</em>
                        </MenuItem>
                        <MenuItem value={10}>ธ. กรุงเทพ</MenuItem>
                        <MenuItem value={20}>ธ. กสิกรไทย</MenuItem>
                        <MenuItem value={30}>ธ. กรุงไทย</MenuItem>
                        </Select>
                  </FormControl>
                </div>
                <div>
                  <div>เลือกช่องทางการโอนเงิน</div>
                  <FormControl className={classes.formControl}>
                    <InputLabel id="demo-simple-select-label">กรุณาเลือกช่องทางธุรกรรม</InputLabel>
                    <Select
                        labelId="demo-simple-select-label"
                        id="demo-simple-select"
                        value={this.state.bank}
                        onChange={this.handleSelectChange}>
                        <MenuItem value="">
                            <em>None</em>
                        </MenuItem>
                        <MenuItem value={10}>ธ. กรุงเทพ</MenuItem>
                        <MenuItem value={20}>ธ. กสิกรไทย</MenuItem>
                        <MenuItem value={30}>ธ. กรุงไทย</MenuItem>
                        </Select>
                  </FormControl>
                </div>
                <div>
                  <TextField id="outlined-basic" label="จำนวนเงินที่โอน" variant="outlined" />
                </div>
              </div>
            </div>
        </div>
        </form>
    );
  }
  */
}

export default DepositPage;