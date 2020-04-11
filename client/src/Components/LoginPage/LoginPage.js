import React, { Component } from 'react';
import TextField from '@material-ui/core/TextField';
import Card from '@material-ui/core/Card';
import CardContent from '@material-ui/core/CardContent';
import Typography from '@material-ui/core/Typography';
import Container from '@material-ui/core/Container';
import Button from '@material-ui/core/Button';
import { Redirect } from 'react-router-dom';

import { connect } from 'react-redux'
import { addTodo, userLogin } from '../../actions'

// import { doLogin, doLogout } from "../../actions/auth";

import ls from 'local-storage';

import axios from 'axios';

const layout = {
  labelCol: { span: 8 },
  wrapperCol: { span: 8 },
};

const useStyles = {
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
};

class LoginPage extends Component {
  constructor(props) {
    super(props);

    this.state = {
      name: '',
      pass: '',
      loggedIn: false,
      error: false,
      error_message:'',
    };

    this.onChange = this.onChange.bind(this);
    this.submitForm = this.submitForm.bind(this);
  }

  componentDidMount() {
    this.callApi()
      .then(res => console.log(res.express) )
      .catch(err => console.log(err));
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
 
    const response = await fetch('/api/world', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ user, pass}),
    });
    const body = await response.text();
    console.log(body);
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

  render() {
    if(this.state.loggedIn){
      return <Redirect to="/" />
    }

    let message_error = <div></div>;
    if(this.state.error){
      message_error = <div>{this.state.error_message}</div>;
    }
    return (
      <div>
        <Container>
        <Card>
          <CardContent>
            <Typography variant="h5" component="h2">
                Login
            </Typography>
            <br />
          {message_error}
          <form onSubmit={this.submitForm}>
            <TextField
              margin="normal"
              onChange={this.onChange}
              label="Username"
              id="outlined-size-normal"
              // defaultValue={this.state.userName}
              variant="outlined"
              name="user"
            />
            <br />
              <TextField
              margin="normal"
              onChange={this.onChange}
              label="Password"
              id="outlined-size-normal"
              variant="outlined"
              type="password"
              name="pass"
            />
            <br />
            <Button type="submit" size="large" variant="contained" color="primary">
              Login
            </Button>
          </form>
          </CardContent>
        </Card>
        </Container>
      </div>
    );
  }
};


/*
	จะเป็น function ที่จะถูกเรียกตลอดเมือ ข้อมูลเปลี่ยนแปลง
	เราสามารถดึงข้อมูลทั้งหมดที่อยู่ใน redux ได้เลย
*/
const mapStateToProps = (state, ownProps) => {
	console.log(state);

	if(!state._persist.rehydrated){
		return {};
	}
	return { user: 'somkid' };
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
		addTodo: (id) => {
							dispatch(addTodo(id))
						},
		addTodo2: (id, val) => {
							dispatch(addTodo(val))
            },
    userLogin: (user, pass) =>{
      dispatch(userLogin(user, pass))
    }

	}
}

export default connect(mapStateToProps, mapDispatchToProps)(LoginPage)