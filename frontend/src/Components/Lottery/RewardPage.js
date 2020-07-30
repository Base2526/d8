import React, { Component } from 'react';
import { connect } from 'react-redux'

import axios from 'axios';
import { Base64 } from 'js-base64';

import {isEmpty} from '../Utils/Config';

// addAward

import { addAward } from '../../actions/huay'

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

    console.log(this.props.addAward({test:122}))

    let {params, type_lotterys} = this.props.location.state
    params = JSON.parse(params);
    
    let response  = await axios.post('/api/get_yeekee_answer', 
                    { uid: this.props.user.uid,
                      type_lotterys: type_lotterys,
                      date: String(params.date), 
                      round_tid: params.tid }, 
                    {headers:JSON.parse(Base64.decode(Base64.decode(localStorage.getItem('headers'))))});
    
    console.log(response);

    if( response.status==200 && response.statusText == "OK" ){
      if(response.data.result){
        let {data} = response.data.data;

        let p1  = JSON.parse(data.p1);
        let p16 = JSON.parse(data.p16);
        let sum = data.sum;

        console.log(p1);
        console.log(p16);
        console.log(sum);

        this.setState({p1, p16, sum});
      }
    }
  }

  render() {
    let {params, type_lotterys} = this.props.location.state

    let {p1, p16, sum} = this.state;

    // console.log(p1);
    // console.log(p16);
    // console.log(sum);

    if(isEmpty(p1)){
      return <div/>;
    }
    

    // let _params = JSON.parse(params);
    // console.log( _params );

    let reward = sum - p16.number;

    // var str = "Hello world!";

    // var res = str.substring(1, 4);
    // console.log( res );

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
    
  console.log(state);
  if(state.auth.isLoggedIn){
      return { loggedIn: true, user:state.auth.user };
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