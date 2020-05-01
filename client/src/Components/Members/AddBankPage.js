import React, { Component, useState } from 'react';
import Alert from 'react-bootstrap/Alert'
import Button from 'react-bootstrap/Button'
import { connect } from 'react-redux'
import Container from 'react-bootstrap/Container'
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'
import Form from 'react-bootstrap/Form'
import InputGroup from 'react-bootstrap/InputGroup'
import InputMask from "react-input-mask";

import { headers } from '../Utils/Config';

import { loadingOverlayActive } from '../../actions/huay'

import axios from 'axios';
var _ = require('lodash');

var styles = {
    root: {
      display: "block"
    },
    pStyle: {
        fontSize: '50px',
        textAlign: 'center',
        backgroundColor: 'red'
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

class AddBankPage extends Component {
    // const [show, setShow] = useState(true);
    // const classes = useStyles();
    constructor(props) {
        super(props);

        this.state = {
            select_bank:'',
            name_bank:'',
            number_bank:'',
            number_bank_confirm:'',

            number_bank_confirm2:'',

            validated:false,

            list_bank:{},
            error: false,
            error_message:'',
        }

        console.log(props);

        this.handleChange = this.handleChange.bind(this);
        this.handleClear  = this.handleClear.bind(this);

        this.handleSubmit = this.handleSubmit.bind(this);
    }
    
    componentDidMount = async () =>{
      let response  = await axios.post('/api/list_bank', {}, 
                                      {headers:headers()});
      if( response.status==200 && response.statusText == "OK" ){
        if(response.data.result){
          // console.log(response.data.data);

          this.setState({list_bank: response.data.data});
        }
      }
    }

    handleSubmit = async (event) => {
      const form = event.currentTarget;
      event.preventDefault();
      if (form.checkValidity() === false) {
        event.stopPropagation();
        this.setState({validated: true});
      }else{
        this.props.loadingOverlayActive(true);
        let { select_bank, name_bank, number_bank, number_bank_confirm} = this.state;   

        // console.log(select_bank, name_bank, number_bank, number_bank_confirm);

        if(number_bank.trim() !== number_bank_confirm.trim())
        {
          this.setState({
            error: true,
            error_message: 'เลขที่บัญชี กับ ยืนยันเลขที่บัญชี ไม่เท่ากัน ',
          });
        }else{
          let response  = await axios.post('/api/add_bank', 
                                      { uid: this.props.user.uid,
                                        tid_bank: select_bank, 
                                        name_bank,
                                        number_bank}, 
                                      {headers:headers()});
          console.log(response);
          if( response.status==200 && response.statusText == "OK" ){
            if(response.data.result){
              // this.props.userLogin(response.data.data);
              this.nextPath('/');
            }else{

              // console.log(response.data.message);
              this.setState({
                error: true,
                error_message: response.data.message,

                password:''
              });
            }
          }
        }

        this.props.loadingOverlayActive(false);
      }
    };
  
    view(){
        return (
            <Form noValidate validated={this.state.validated} onSubmit={this.handleSubmit}>
              <Form.Row>
                <Form.Group as={Col} md="4" controlId="validationCustom01">
                  <Form.Label>First name</Form.Label>
                  <Form.Control
                    required
                    type="text"
                    placeholder="First name"
                    defaultValue="Mark"
                  />
                  <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
                </Form.Group>
                <Form.Group as={Col} md="4" controlId="validationCustom02">
                  <Form.Label>Last name</Form.Label>
                  <Form.Control
                    required
                    type="text"
                    placeholder="Last name"
                    defaultValue="Otto"
                  />
                  <Form.Control.Feedback>Looks good!</Form.Control.Feedback>
                </Form.Group>
                <Form.Group as={Col} md="4" controlId="validationCustomUsername">
                  <Form.Label>Username</Form.Label>
                  <InputGroup>
                    <InputGroup.Prepend>
                      <InputGroup.Text id="inputGroupPrepend">@</InputGroup.Text>
                    </InputGroup.Prepend>
                    <Form.Control
                      type="text"
                      placeholder="Username"
                      aria-describedby="inputGroupPrepend"
                      required
                    />
                    <Form.Control.Feedback type="invalid">
                      Please choose a username.
                    </Form.Control.Feedback>
                  </InputGroup>
                </Form.Group>
              </Form.Row>
              <Form.Row>
                <Form.Group as={Col} md="6" controlId="validationCustom03">
                  <Form.Label>City</Form.Label>
                  <Form.Control type="text" placeholder="City" required />
                  <Form.Control.Feedback type="invalid">
                    Please provide a valid city.
                  </Form.Control.Feedback>
                </Form.Group>
                <Form.Group as={Col} md="3" controlId="validationCustom04">
                  <Form.Label>State</Form.Label>
                  <Form.Control type="text" placeholder="State" required />
                  <Form.Control.Feedback type="invalid">
                    Please provide a valid state.
                  </Form.Control.Feedback>
                </Form.Group>
                <Form.Group as={Col} md="3" controlId="validationCustom05">
                  <Form.Label>Zip</Form.Label>
                  <Form.Control type="text" placeholder="Zip" required />
                  <Form.Control.Feedback type="invalid">
                    Please provide a valid zip.
                  </Form.Control.Feedback>
                </Form.Group>
              </Form.Row>
              <Form.Group>
                <Form.Check
                  required
                  label="Agree to terms and conditions"
                  feedback="You must agree before submitting."
                />
              </Form.Group>
              <Button type="submit">Submit form</Button>
            </Form>);
    }

    handleChange(event) {
        console.log(event.target);
        console.log(event.target.value);
        this.setState({[event.target.id]: event.target.value});
    }    

    handleClear(event){
        this.setState({
            select_bank:'',
            name_bank:'',
            number_bank:'',
            number_bank_confirm:'',
        });
    }

    nextPath(path) {
      this.props.history.push(path);
    }
    
    render() {
      console.log(this.state);
      console.log(this.props);
      let {validated, error, error_message} = this.state;
     
      return (
          <Form noValidate validated={validated} onSubmit={this.handleSubmit}>
              { error ? <Alert variant={'danger'}>{error_message}</Alert> : '' }
              <Form.Group controlId="select_bank">
                  <Form.Label>เลือกธนาคาร</Form.Label>
                  <Form.Control 
                      as="select" 
                      required 
                      value={this.state.select_bank}  
                      onChange={this.handleChange}>
                      <option value="">--เลือก--</option>
                      {/* <option value="10">ธ. กรุงเทพ</option>
                      <option value="20">ธ. กสิกรไทย</option>
                      <option value="30">ธ. กรุงไทย</option> */}
                      {
                         _.map(this.state.list_bank, (val, key) => {
                          // console.log(val, key)
                          return <option key={key} value={key}>{val}</option>
                        })                  
                      }
                  </Form.Control>
                  <Form.Control.Feedback type="invalid">
                      กรุณาเลือกธนาคาร
                  </Form.Control.Feedback>
              </Form.Group>
              <Form.Group controlId="name_bank">
                  <Form.Label>ชื่อบัญชี</Form.Label>
                  <Form.Control 
                      type="text" 
                      placeholder="ชื่อบัญชี" 
                      required 
                      value={this.state.name_bank} onChange={this.handleChange}/>
                  <Form.Control.Feedback type="invalid">
                      กรุณากรอบชื่อบัญชี.
                  </Form.Control.Feedback>
              </Form.Group>
              <Form.Group>
                  <Form.Label>เลขที่บัญชี</Form.Label>
                  {/* <Form.Control 
                      type="number" 
                      placeholder="เลขที่บัญชี" 
                      required 
                      value={this.state.number_bank} onChange={this.handleChange}/> */}
                  <InputMask 
                      id='number_bank' 
                      mask="999-9-99999-99999" 
                      onChange={this.handleChange} 
                      value={this.state.number_bank}
                      className="form-control"
                      placeholder="ยืนยันเลขที่บัญชี" 
                      maskPlaceholder={null}
                      required /> 
                  <Form.Control.Feedback type="invalid">
                      กรุณากรอบเลขที่บัญชี.
                  </Form.Control.Feedback>
              </Form.Group>
              {/* <Form.Group controlId="number_bank_confirm">
                  <Form.Label>ยืนยันเลขที่บัญชี</Form.Label>
                  <Form.Control 
                      type="text" 
                      placeholder="ยืนยันเลขที่บัญชี" 
                      required 
                      value={this.state.number_bank_confirm} onChange={this.handleChange}/>
                  <Form.Control.Feedback type="invalid">
                      กรุณากรอบยืนยันเลขที่บัญชี.
                  </Form.Control.Feedback>
              </Form.Group> */}

              <Form.Group>
                  <Form.Label>ยืนยันเลขที่บัญชี</Form.Label>
                  <InputMask 
                      id='number_bank_confirm' 
                      mask="999-9-99999-99999" 
                      onChange={this.handleChange} 
                      value={this.state.number_bank_confirm}
                      className="form-control"
                      placeholder="ยืนยันเลขที่บัญชี" 
                      maskPlaceholder={null}
                      required /> 
                  {/* <MaskedFormControl type='text'  mask='111-111-1111' /> */}
                  <Form.Control.Feedback type="invalid">
                      กรุณากรอบยืนยันเลขที่บัญชี xxx.
                  </Form.Control.Feedback>
              </Form.Group>
              <Button variant="primary" type="submit">
                  เพิ่มบัญชี
              </Button>
              <Button variant="light" onClick={this.handleClear}>Clear</Button>
          </Form>
      );
    }
}

/*
	จะเป็น function ที่จะถูกเรียกตลอดเมือ ข้อมูลเปลี่ยนแปลง
	เราสามารถดึงข้อมูลทั้งหมดที่อยู่ใน redux ได้เลย
*/
const mapStateToProps = (state, ownProps) => {
  console.log(state);
  console.log(ownProps);

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

export default connect(mapStateToProps, mapDispatchToProps)(AddBankPage)