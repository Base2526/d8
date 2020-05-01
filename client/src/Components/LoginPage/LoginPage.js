import React, { Component } from 'react';
import { Redirect, Link} from 'react-router-dom';
import { connect } from 'react-redux'
import Form from 'react-bootstrap/Form'
import Button from 'react-bootstrap/Button'
import Alert from 'react-bootstrap/Alert'
import axios from 'axios';

import { headers } from '../Utils/Config';
import { userLogin } from '../../actions/auth'

import {  loadingOverlayActive, 
          updateHuayListBank, 
          updateTransferMethod, 
          updateContactUs,
          updateListBank} from '../../actions/huay'

class LoginPage extends Component {
  constructor(props) {
    super(props);

    this.state = {
      validated: false,
      email: '',
      password: '',

      error: false,
      error_message:'',
    };

    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleChange = this.handleChange.bind(this);
  }

  componentDidMount(){
    // this.props.loadingOverlayActive(false);
  }

  handleChange(event) {
    this.setState({[event.target.id]: event.target.value});
  }    

  handleSubmit = async (event) => {
    const form = event.currentTarget;
    event.preventDefault();
    if (form.checkValidity() === false) {
      event.stopPropagation();
      this.setState({validated: true});
    }else{
      this.props.loadingOverlayActive(true);

      let { email, password } = this.state;   
      let response  = await axios.post('/api/login', 
                                      {name: email, pass: password}, 
                                      {headers:headers()});
      console.log(response);
      if( response.status==200 && response.statusText == "OK" ){
        if(response.data.result){
          let { userLogin, 
                updateHuayListBank,
                updateTransferMethod,
                updateContactUs,
                updateListBank} = this.props;

          let { session, 
                user, 
                huay_list_bank,
                transfer_method, 
                contact_us,
                list_bank} = response.data;

          userLogin(user);
          updateHuayListBank(huay_list_bank);
          updateTransferMethod(transfer_method);
          updateContactUs(contact_us);
          updateListBank(list_bank);
        }else{
          this.setState({
            error: true,
            error_message: response.data.message,

            password:''
          });
        }
      }

      this.props.loadingOverlayActive(false);
    } 
  }

  nextPath(path) {
    this.props.history.push(path);
  }

  render(){
    let {validated, email, password, error, error_message} = this.state;
    let {logged_in} = this.props;
    if(logged_in){
      return <Redirect to="/" />
    }

    return( <Form noValidate validated={validated} onSubmit={this.handleSubmit}>   
              { error ? <Alert variant={'danger'}>{error_message}</Alert> : '' }
              <Form.Group controlId="email">
                <Form.Label>อีเมลล์</Form.Label>
                <Form.Control 
                  type="email" 
                  placeholder="อีเมลล์" 
                  required 
                  value={email} onChange={this.handleChange}/>
                <Form.Control.Feedback type="invalid">
                    กรุณากรอบอีเมลล์ หรือ invalid email address.
                </Form.Control.Feedback>
              </Form.Group>
              <Form.Group controlId="password">
                <Form.Label>รหัสผ่าน</Form.Label>
                <Form.Control 
                  type="password" 
                  placeholder="รหัสผ่าน" 
                  required
                  value={password} onChange={this.handleChange}/>
                <Form.Control.Feedback type="invalid">
                    กรุณากรอบรหัสผ่าน.
                </Form.Control.Feedback>
              </Form.Group>
              <Button variant="primary" type="submit">
                เข้าสู่ระบบ
              </Button>
              <Button variant="light" onClick={()=>this.nextPath('/forget-password')}>ลืมรหัสผ่าน</Button>
            </Form>
    );
  }
};

/*
	จะเป็น function ที่จะถูกเรียกตลอดเมือ ข้อมูลเปลี่ยนแปลง
	เราสามารถดึงข้อมูลทั้งหมดที่อยู่ใน redux ได้เลย
*/
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

/*
	การที่เราจะเรียก function ที่อยู่ใน actions ได้
	การใช้
	แบบที่ 1.
	const mapDispatchToProps = (dispatch) => {
		return {
			function1: (id) => {
								// function ที่อยู่ใน actions
								dispatch(addTodo(param1))
							},
			function2: (id, val) => {
								// function ที่อยู่ใน actions
								dispatch(addTodo(param1, param2))
							},

		}
	}

	export default connect(null, mapDispatchToProps)(function)

	แบบที่ 2.
	export default connect(null, { doFunction1, doFunction2 })(function)

	การเรียกใช้
	แบบที่ 1 
	this.props.addTodo(param1, param2);

	แบบที่ 2
	let {function1, function2} = this.props;
*/
const mapDispatchToProps = (dispatch) => {
	return {
    userLogin: (data) =>{
      dispatch(userLogin(data))
    },
    loadingOverlayActive: (isActivie) =>{
      dispatch(loadingOverlayActive(isActivie))
    },
    updateHuayListBank: (data) =>{
      dispatch(updateHuayListBank(data))
    },
    updateTransferMethod: (data) =>{
      dispatch(updateTransferMethod(data))
    },
    updateContactUs: (data) =>{
      dispatch(updateContactUs(data))
    },
    updateListBank:  (data) =>{
      dispatch(updateListBank(data))
    },
	}
}

export default connect(mapStateToProps, mapDispatchToProps)(LoginPage)