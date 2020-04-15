import React, { Component } from 'react';
import { BrowserRouter as BR, Route, Switch } from 'react-router-dom'; 

import axios from 'axios';

import RideSelect from './Components/RideSelect/RideSelect';
import SecondPage from './Components/SecondPage/SecondPage';
import AppBar from './Components/AppBar/AppBar';
import Footer from './Components/Footer/Footer';
import Container from '@material-ui/core/Container';
import LoginPage from './Components/LoginPage/LoginPage';
import FinalPage from './Components/FinalPage/FinalPage';
import ProfilePage from './Components/ProfilePage/ProfilePage';
import ForgetPasswordPage from './Components/ForgetPasswordPage/ForgetPasswordPage';
import RegisterPage from './Components/RegisterPage/RegisterPage';

import LotteryListPage from './Components/Lottery/LotteryListPage';
import GovernmentPage from './Components/Lottery/GovernmentPage';

import YeekeeListPage from './Components/Lottery/YeekeeListPage';
import YeekeePage from './Components/Lottery/YeekeePage';

import DepositPage from './Components/Members/DepositPage';
import WithdrawPage from './Components/Members/WithdrawPage';
import AddBankPage from './Components/Members/AddBankPage'

import ls from 'local-storage';
import './App.css';

const API_URL = 'http://jsonplaceholder.typicode.com';

class App extends Component {

state = {
    rideType: 'ow'
  };

  componentDidMount() {
    ls.clear();
    ls.set('confirmedDriver', null);

    const url = `${API_URL}/users/`;
    axios.get(url).then(response => response.data)
    .then((data) => {
      // this.setState({ users: data })
      console.log(data)
     })

     console.log(process.env); 
  }  

  render() {    
    return (
      <BR>
        <div className="App">
          <AppBar></AppBar>
          <br />
          <Container>
            <Switch>
              <Route path="/" exact component={ RideSelect } />
              <Route path="/login" component={ LoginPage }/>
              <Route path="/second-page" component={ SecondPage } />
              <Route path="/final-page" component={ FinalPage } />
              <Route path="/profile-page" component={ ProfilePage } />
              <Route path="/forget-password-page" component={ ForgetPasswordPage } />
              <Route path="/register-page" component={ RegisterPage } />

              <Route path="/lottery-list" component={ LotteryListPage } />
              <Route path="/government" component={ GovernmentPage } />
              <Route path="/yeekee-list" component={ YeekeeListPage } />
              <Route path="/yeekee" component={ YeekeePage } />

              <Route path="/deposit" component={ DepositPage } />
              <Route path="/withdraw" component={ WithdrawPage } />
              <Route path="/add-bank" component={ AddBankPage } />
            </Switch>
          </Container>   
          <Footer>
            <span>footer content</span>  
          </Footer>       
        </div>
      </BR>
    );
  }
}

export default App;
