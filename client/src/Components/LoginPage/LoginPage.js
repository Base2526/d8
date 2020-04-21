import React, { Component } from 'react';
import TextField from '@material-ui/core/TextField';
import Card from '@material-ui/core/Card';
import CardContent from '@material-ui/core/CardContent';
import Typography from '@material-ui/core/Typography';
import Container from '@material-ui/core/Container';
import { Redirect, Link} from 'react-router-dom';

import { connect } from 'react-redux'
import { userLogin } from '../../actions/auth'

import Form from 'react-bootstrap/Form'
import Button from 'react-bootstrap/Button'
import Alert from 'react-bootstrap/Alert'

import * as types from "../../actions/types";

const axios = require('axios');

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

    this.onChange = this.onChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);

    this.handleChange = this.handleChange.bind(this);
  }

  componentDidMount() {
    // this.callApi()
    //   .then(res => console.log(res.express) )
    //   .catch(err => console.log(err));

    /*
    var self = this;
    axios.post('/api/login', {
      name: 'admin', 
      pass: 'ๅ/_ภ'
    })
    .then(function (response) {
      if( response.status==200 && response.statusText == "OK"){
        if(response.data.result){
          // self.props.userLogin(response.data.data);
        }
      }
      // console.log(response);

      // self.setState({
      //   error: true,
      //   error_message: "xxxxx."
      // });

    })
    .catch(function (error) {
      console.log(error);
    });
    */
  }

  handleChange(event) {
    this.setState({[event.target.id]: event.target.value});
  }    

  onChange(e) {
    this.setState({
      [e.target.name]: e.target.value
    });
  }

  callApi = async () => {
    const response = await fetch('/api/hello');
    const body = await response.json();
    if (response.status !== 200) throw Error(body.message);
    
    return body;
  };

  // submitForm(e) {
  submitForm = async e => {
    e.preventDefault();
    console.log('submitForm');
    const { user, pass } = this.state;

    if(user.trim() == "" && pass.trim() == "" ){
      this.setState({
        error: true,
        error_message: "Username && Pass is empty."
          });
    }else if(user.trim() == ""){
      this.setState({
        error: true,
        error_message: "Username is empty."
          });
    }else if(pass.trim() == ""){
      this.setState({
        error: true,
        error_message: "Password is empty."
          });
    }
 
    const response = await fetch('/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ name:user, pass}),
    });

    let body = await response.text();

    body = JSON.parse(body);
    if(!body.result){
      console.log(body.message);
    }else{
      let data = body.data;
      console.log(data);

      this.props.userLogin(data);
    }

    // if(username === "admin" && password === "admin") {
    //   ls.set("token", "56@cysXs");
    //   this.setState({
    //     loggedIn: true
    //   });
    // } else {
    //   return <p>Invalid Creds</p>
    // }
    // this.props.userLogin('username', 'password');
    // this.props.addTodo('4');
    /*
    const params = {
      name: username,
      pass: password
    };
    const headers = {
      'Content-Type': 'application/json'
    };
    axios.post("http://localhost/api/login.json",{ params }, {headers})
    .then(res => {
      console.log(res);
      // console.log(res.data);
    })
    .catch(function (error) {
      console.log(error);
    });
    */
  }

  handleSubmit = async (event) => {
    const form = event.currentTarget;
    if (form.checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();

      this.setState({validated: true});

      const response  = await axios.post('/api/login', {name: 'admin', pass: 'ๅ/_ภ'});
      // const { data }  = await res;
      // const data      = await response.json();
      console.log(response);

    }else{

      let { email, password } = this.state;

      console.log(email);
      console.log(password);
      
      let response  = await axios.post('/api/login', {name: email, pass: password});
      if( response.status==200 && response.statusText == "OK" ){
        if(response.data.result){
          this.props.userLogin(response.data.data);

          // this.props.dispatch({
          //   type: types.AUTH_LOGIN,
          //   user:response.data.data
          // });
          return;
        }
      }

      this.setState({
        error: true,
        error_message: "Username && password is empty."
      });


    //  async getData(){
    //   const res = await axios.get('url-to-get-the-data');
    //   const { data } = await res;
    //   this.setState({serverResponse: data})
    // }
    }

    /*
    let { email, password } = this.state;
    if(email.trim() == "" && password.trim() == "" ){
      this.setState({
        error: true,
        error_message: "Username && password is empty."
          });
    }else if(email.trim() == ""){
      this.setState({
        error: true,
        error_message: "Username is empty."
          });
    }else if(password.trim() == ""){
      this.setState({
        error: true,
        error_message: "Password is empty."
          });
    }
    */

    // event.preventDefault();
    // event.stopPropagation();

    // this.setState({validated:false});
 
    // const response = await fetch('/api/login', {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //   },
    //   body: JSON.stringify({ name:email, pass:password}),
    // });

    // let body = await response.text();

    // body = JSON.parse(body);
    // if(!body.result){
    //   console.log(body.message);
    // }else{
    //   let data = body.data;
    //   console.log(data);
    //   this.props.userLogin(data);
    // }
    // this.nextPath('/deposit');

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
  console.log(state);
  console.log(ownProps);

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
	console.log(dispatch);

	return {
		// addTodo: (id) => {
		// 					dispatch(addTodo(id))
		// 				},
		// addTodo2: (id, val) => {
		// 					dispatch(addTodo(val))
    //         },
    userLogin: (data) =>{
      dispatch(userLogin(data))
    }

	}
}

export default connect(mapStateToProps, {userLogin})(LoginPage)