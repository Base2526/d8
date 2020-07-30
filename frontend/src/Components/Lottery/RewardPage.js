import React, { Component } from 'react';
import { connect } from 'react-redux'

import axios from 'axios';
import { Base64 } from 'js-base64';

import {isEmpty} from '../Utils/Config';

// addAward

import { addAward } from '../../actions/huay'
import awards from '../../reducers/awards';

class RewardPage extends Component {
  constructor(props) {
    super(props);

    this.state = {
       p1: {},
      p16: {},
      sum: '',
    }

  }

  componentDidMount = async() => {
    let {award} = this.props;

    if(isEmpty(award)){
      let {params, type_lotterys} = this.props.location.state
      params = JSON.parse(params);
    
      let response  = await axios.post('/api/get_yeekee_answer', 
                      { uid: this.props.user.uid,
                        type_lotterys: type_lotterys,
                        date: String(params.date), 
                        round_tid: params.tid }, 
                      {headers:JSON.parse(Base64.decode(Base64.decode(localStorage.getItem('headers'))))});
      
      // console.log(response);
      if( response.status==200 && response.statusText == "OK" ){
        if(response.data.result){
          let {data} = response.data.data;
  
          console.log(response.data.data)
  
          let p1  = JSON.parse(data.p1);
          let p16 = JSON.parse(data.p16);
          let sum = data.sum;
  
          // console.log(p1);
          // console.log(p16);
          // console.log(sum);
  
          // this.setState({p1, p16, sum});
  
          this.props.addAward(response.data.data)
        }
      }
    }
  }

  render() {
    let {params, type_lotterys} = this.props.location.state

    // let {p1, p16, sum} = this.state;

    // console.log(p1);
    // console.log(p16);
    // console.log(sum);

    let {award} = this.props;

    if(isEmpty(award)){
      return <div/>;
    }

    let {p1, p16, sum} = award.data
    
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
      
      // console.log(award);
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
  }
}
export default connect(mapStateToProps, mapDispatchToProps)(RewardPage)