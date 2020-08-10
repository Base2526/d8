import React, { Component } from 'react';
import { connect } from 'react-redux'

import axios from 'axios';
import { Base64 } from 'js-base64';
import ReactList from 'react-list';

import {isEmpty, format_email} from '../Utils/Config';
import { addAward, loadingOverlayActive } from '../../actions/huay'

class RewardPage extends Component {
  constructor(props) {
    super(props);

    this.state = {
      is_active: false,
      loading_text: 'รอสักครู่',
    }

    this.renderSquareItem = this.renderSquareItem.bind(this)
  }

  componentDidMount = async() => {
    let {award} = this.props;

    // this.setState({is_active:false});
    console.log(localStorage.getItem('headers'))
    if(isEmpty(award)){

      this.setState({is_active:true})

      let {params, type_lotterys} = this.props.location.state
      params = JSON.parse(params);

      console.log(params)

      let response  = await axios.post('/api/get_yeekee_answer', 
                      { uid: this.props.user.uid,
                        type_lotterys: type_lotterys, // tid: '67'
                        date: String(params.date), 
                        round_tid: params.tid }, 
                      {headers:JSON.parse(Base64.decode(Base64.decode(localStorage.getItem('headers'))))});
      
      console.log( response )
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

  renderSquareItem(index, key){
    let {sn_data} = this.props.award

    let item = sn_data[index];
    if(isEmpty(item)){
      return <div />
    }

    var date = new Date(item.createdAt);

    // console.log(date.toString())
    // console.log(date.getUTCMonth())

    const months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

    // Year
    var year = date.getFullYear();
    // Month
    var month = months[date.getMonth()];//date.getMonth() + 1;
    // Day
    var day = date.getDate();
    // Hours
    var hours = date.getHours();
    // Minutes
    var minutes = "0" + date.getMinutes();
    // Seconds
    var seconds = "0" + date.getSeconds();
    // Display date time in MM-dd-yyyy h:m:s format
    var time = day +'-'+month+'-'+year+' '+hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

    // console.log(sn_data)

    return( <div key={key} className={ (index == 0 || index == 15) ? 'square-item-close' : '' }>
              อันดับ {index + 1} : {item.number} : {isEmpty(item.user) ? '*' : format_email(item.user.email)} : {time}
            </div>)  
  }

  render() {
    // this.loadingOverlayActive();

    let {award} = this.props;

    if(isEmpty(award)){
      return <div/>;
    }

    
    let {params} = this.props.location.state

    params = JSON.parse(params);

    let {p1, p16, sum, sn_data} = award

    console.log( sn_data );
    
    let reward = sum - p16.number;
    reward = reward.toString();

    let _3up   = reward.substring(reward.length- 3, reward.length);
    let _2down = reward.substring(reward.length- 5, reward.length- 3);
    return (<div>
              <div>หวยยี่กี - รอบที่ {params.name}</div>
              <div>ผลรางวัล : {reward}</div>
              <div>3ตัวบน : {_3up}</div>
              <div>2ตัวล่าง : {_2down}</div>
              <div>ผลรวมยิงเลข : {sum}</div>
              <div>เลขแถวที่ 16 : {p16.number}</div>
              <div>สมาชิกยิงเลขได้ อันดับ 1 : {isEmpty(p1.user) ? '' : format_email(p1.user.email)}</div>
              <div>สมาชิกยิงเลขได้ อันดับ 16 : {isEmpty(p16.user) ? '' : format_email(p16.user.email)}</div>

              <div className={'even'}>
              <ReactList
                  // useTranslate3d={true}
                  itemRenderer={this.renderSquareItem}
                  length={100}
                  type='simple'/>
              </div>
            </div>);
  }
}

const mapStateToProps = (state, ownProps) => {
	if(!state._persist.rehydrated){
		return {};
  }
    
  if(state.auth.isLoggedIn){
    let awards = state.awards;
    console.log(awards);

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