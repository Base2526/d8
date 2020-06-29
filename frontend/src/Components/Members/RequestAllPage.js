import React, { Component } from 'react';
import { connect } from 'react-redux'
import Tabs from 'react-bootstrap/Tabs'
import Tab from 'react-bootstrap/Tab'
import axios from 'axios';

import ReactList from 'react-list';

import moment from 'moment'

import { loadingOverlayActive } from '../../actions/huay'
import { headers, showToast } from '../Utils/Config';

class RequestAllPage extends Component {
  constructor(props) {
    super(props);

    this.state = {
      is_active: false,

      datas: []
    }

    this.renderItem = this.renderItem.bind(this);
  }

  componentDidMount = async () => {
    let response  = await axios.post('/api/request_all', 
                                    { uid: this.props.user.uid },
                                    {headers:headers()});

    // console.log(response);
    if( response.status==200 && response.statusText == "OK" ){
      if(response.data.result){  
        console.log(response.data.data);    
        
        this.setState({datas: response.data.datas.sort(function(a, b) {
                                var dateA = new Date(a.create * 1000);
                                var dateB = new Date(b.create * 1000);
                                return dateB - dateA;
                              })
                      });
      }else{
      }
    }else{
    }
  }

  loadingOverlayActive(){
    this.props.loadingOverlayActive(this.state.is_active);
  }

  renderItem(index, key) {
    return  <div key={key}>
              <div>Amount : {this.state.datas[index].amount}</div>
              <div>Attached_file : {this.state.datas[index].attached_file}</div>
              <div>Deposit_status : {this.state.datas[index].deposit_status}</div>
              <div>Transfer_method : {this.state.datas[index].transfer_method}</div>
              <div>Date : {(new Date(this.state.datas[index].create * 1000)).toString()}</div>
              <hr />
            </div>;
  }

  render() {
    return (<ReactList
              itemRenderer={this.renderItem}
              length={this.state.datas.length}
              type='uniform'/>
            );
  }
}

const mapStateToProps = (state, ownProps) => {
	if(!state._persist.rehydrated){
		return {};
  }

  if(state.auth.isLoggedIn){
    console.log(state);

    let {huay_list_bank} = state;
    return {logged_in:true, 
            user: state.auth.user};
  }else{
    return { logged_in:false };
  }
}

/*
deposit_status
transfer_method
huay_list_bank
list_bank
*/

const mapDispatchToProps = (dispatch) => {
	return {
    loadingOverlayActive: (isActivie) =>{
      dispatch(loadingOverlayActive(isActivie))
    }
	}
}

export default connect(mapStateToProps, mapDispatchToProps)(RequestAllPage)