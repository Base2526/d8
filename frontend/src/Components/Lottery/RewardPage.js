import React, { Component } from 'react';
import { connect } from 'react-redux'

import axios from 'axios';
import { Base64 } from 'js-base64';

import {isEmpty} from '../Utils/Config';

// addAward

import { addAward, loadingOverlayActive } from '../../actions/huay'
import awards from '../../reducers/awards';

class RewardPage extends Component {
  constructor(props) {
    super(props);

    this.state = {
      is_active: false,
      loading_text: 'รอสักครู่',
    }
  }

  componentDidMount = async() => {
    let {award} = this.props;

    this.setState({is_active:false});

    if(isEmpty(award)){

      this.setState({is_active:true})

      let {params, type_lotterys} = this.props.location.state
      params = JSON.parse(params);
    
      let response  = await axios.post('/api/get_yeekee_answer', 
                      { uid: this.props.user.uid,
                        type_lotterys: type_lotterys,
                        date: String(params.date), 
                        round_tid: params.tid }, 
                      {headers:JSON.parse(Base64.decode(Base64.decode(localStorage.getItem('headers'))))});
      
      if( response.status==200 && response.statusText == "OK" ){
        if(response.data.result){  
          this.props.addAward(response.data.data);
        }
      }

      this.setState({is_active:false});
    }
  }

  loadingOverlayActive(){
    let {is_active, loading_text} = this.state
    this.props.loadingOverlayActive(is_active, loading_text);
  }

  render() {
    let {params, type_lotterys} = this.props.location.state

    // let {p1, p16, sum} = this.state;

    // console.log(p1);
    // console.log(p16);
    // console.log(sum);

    this.loadingOverlayActive();

    let {award} = this.props;

    if(isEmpty(award)){
      return <div/>;
    }

    var {p1, p16, sum} = award.data

    p1  = JSON.parse(p1);
    p16 = JSON.parse(p16);
    
    let reward = sum - p16.number;
    reward = reward.toString();

    let _3up   = reward.substring(reward.length- 3, reward.length);
    let _2down = reward.substring(reward.length- 5, reward.length- 3);
    return (<div>
              <div>ผลรางวัล : {reward}</div>
              <div>3ตัวบน : {_3up}</div>
              <div>2ตัวล่าง : {_2down}</div>
              <div>ผลรวมยิงเลข : {sum}</div>
              <div>เลขแถวที่ 16 : {p16.number}</div>
              <div>สมาชิกยิงเลขได้ อันดับ 1 : {p1.uid}</div>
              <div>สมาชิกยิงเลขได้ อันดับ 16 : {p16.uid}</div>
            </div>);
  }
}

const mapStateToProps = (state, ownProps) => {
	if(!state._persist.rehydrated){
		return {};
  }
    
  if(state.auth.isLoggedIn){
    let awards = state.awards;
    // console.log(awards);

    var award ={};
    if(!isEmpty(awards.data)){

      // let {params, type_lotterys} = this.props.location.state
      // console.log(ownProps.location.state)

      let {params, type_lotterys} = ownProps.location.state
      params = JSON.parse(params);

      // {"tid":"98","name":"30","is_close":true,"date":1596089700000,"weight":"29"}

      award = awards.data.find(item => item.type_lotterys === type_lotterys && item.round_tid === params.tid && item.date === String(params.date) )
      
      console.log(award);
    }
    

    return { loggedIn: true, user:state.auth.user, award};
  }else{
      return { loggedIn: false };
  }
}

const mapDispatchToProps = (dispatch) => {
	return {
    addAward:(data)=>{
      dispatch(addAward(data))
    },
    loadingOverlayActive: (isActivie, loadingText) =>{
      dispatch(loadingOverlayActive(isActivie, loadingText))
    },
  }
}
export default connect(mapStateToProps, mapDispatchToProps)(RewardPage)