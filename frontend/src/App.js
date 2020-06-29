import React, { Component } from 'react';
import { BrowserRouter as BR, Route, Switch } from 'react-router-dom'; 
import { connect } from 'react-redux'

import LoadingOverlay from 'react-loading-overlay';
import ScaleLoader from 'react-spinners/ScaleLoader'
import AppBar from './Components/AppBar/AppBar';
import Footer from './Components/Footer/Footer';
// import Container from '@material-ui/core/Container';
import Container from 'react-bootstrap/Container'

import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

import { Breadcrumbs } from './Components/Breadcrumbs'

import { userUpdate, userLogout } from './actions/auth'
import {  loadingOverlayActive, 
          updateHuayListBank, 
          updateTransferMethod, 
          updateContactUs,
          updateListBank,
          // updateYeekeeRound,
          
          updateLotterys,
          updateShootNumbers,
          updateDepositStatus
        } from './actions/huay'

import './App.css';
import routes from "./routes";
import 'bootstrap/dist/css/bootstrap.min.css';
import {connect_socketIO} from './socket.io'


class App extends Component {
  componentDidMount() {
     console.log(process.env); 
  }  

  render(){
    return(<BR>
              <LoadingOverlay
                active={this.props.isActive}
                spinner={<ScaleLoader color="#00BFFF" />}
                text={this.props.loadingText}>

              <ToastContainer enableMultiContainer containerId={'toast_container_id'} position={toast.POSITION.TOP_RIGHT} />  
              
              <div className="App">
                <AppBar></AppBar>
                <br />
                <Container>
                <Switch>
                  {routes.map(({ path, name, Component }, key) => (
                    <Route
                      exact
                      path={path}
                      key={key}
                      render={props => {
                        const crumbs = routes
                          // Get all routes that contain the current one.
                          .filter(({ path }) => props.match.path.includes(path))
                          // Swap out any dynamic routes with their param values.
                          // E.g. "/pizza/:pizzaId" will become "/pizza/1"
                          .map(({ path, ...rest }) => ({
                            path: Object.keys(props.match.params).length
                              ? Object.keys(props.match.params).reduce(
                                (path, param) => path.replace(
                                  `:${param}`, props.match.params[param]
                                ), path
                                )
                              : path,
                            ...rest
                          }));

                        if(this.props.logged_in){
                          connect_socketIO(this.props)
                        }

                        // console.log();
                        console.log(`Generated crumbs for ${props.match.path}`);
                        crumbs.map(({ name, path }) => console.log({ name, path }));
                        return (
                          <div className="p-8">
                            <Breadcrumbs crumbs={crumbs} />
                            <Component {...props} />

                            <Footer>
                              <span>footer content</span>  
                            </Footer>  
                          </div>
                        );
                      }}
                    />
                  ))}
                </Switch>
                </Container>
              </div>
            </LoadingOverlay>
            </BR> )
  }
}

const mapStateToProps = (state, ownProps) => {
  console.log(state);
  console.log(ownProps);

	if(!state._persist.rehydrated){
		return {};
  }

  // loading_text

  let result = {isActive: state.loading_overlay.isActive, loadingText: state.loading_overlay.loadingText};  
  if(state.auth.isLoggedIn){
    return {...result,  logged_in: true, user: state.auth.user};
  }else{
    return {...result, logged_in: false };
  }
}

const mapDispatchToProps = (dispatch) => {
	return {
    userUpdate:(data)=>{
      dispatch(userUpdate(data))
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
    userLogout:  () =>{
      dispatch(userLogout())
    },
    updateLotterys: (data)=>{
      dispatch(updateLotterys(data))
    },
    updateShootNumbers: (data)=>{
      dispatch(updateShootNumbers(data))
    },
    updateDepositStatus: (data)=>{
      dispatch(updateDepositStatus(data))
    },
	}
}

export default connect(mapStateToProps, mapDispatchToProps)(App)
