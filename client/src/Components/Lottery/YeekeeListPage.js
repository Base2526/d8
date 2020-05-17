import React, { Component } from 'react';
import { connect } from 'react-redux'
import ReactList from 'react-list';

import { getTime } from '../Utils/Config';

import '../../index.css';

var interval = undefined;
class YeekeeListPage extends Component {
  constructor(props) {
    super(props);
    
    this.state = {
      rounds:[]
    };
    this.handleClick = this.handleClick.bind(this);
    this.renderSquareItem = this.renderSquareItem.bind(this);
  }

  componentDidMount() {
    let {rounds} = this.props

    rounds = rounds.map((v, k) =>{return {...v, time:getTime(v)}})
    this.setState({rounds})

    interval = setInterval(() => {
      let {rounds} = this.props
      rounds = rounds.map((v, k) =>{ return {...v, time:getTime(v)} })
      this.setState({rounds})
    }, 1000);
  }

  componentWillUnmount(){
    if(interval){
      clearInterval(interval);
    }
  }

  handleClick = (e, round) => {
    let {history} = this.props
    if(round.time == -1){
      this.props.history.push('/lottery-list/reward/yeekee/' + round.tid)
    }else{
      history.push('/lottery/yeekee-list/yeekee/' + round.tid)
    }
  }

  renderSquareItem(index, key){
    let {rounds} = this.state
    let round = rounds[index];
    if(round.time == -1){
      return<a key={key} onClick={((e) => this.handleClick(e, round))}>
            <div className={'square-item' + (index % 2 ? '' : ' even')}>
              {/* <button data-id={round.tid} onClick={e => this.handleClick(e.target.getAttribute('data-id'))}></button>
               */}
              <p>รอบที่ {round.name} เวลาปิด {round.end_time}</p>
              <p>ปิดรับแทง</p>
            </div>
            </a>
    }else{
      return<a key={key} onClick={((e) => this.handleClick(e, round))}>
              <div key={key} className={'square-item' + (index % 2 ? '' : ' even')}>
                  {/* <button data-id={round.tid} onClick={e => this.handleClick(e.target.getAttribute('data-id'))}>รอบที่ {round.name} เวลาปิด {round.end_time}</button> */}
                  <p>รอบที่ {round.name} เวลาปิด {round.end_time}</p>
                  <div>เวลาที่เหลือ : {round.time}</div>
              </div>
            </a>
    }
  }

  render(){
    return  <ReactList
              itemRenderer={this.renderSquareItem}
              length={this.state.rounds.length}
              type='uniform'/>;
  }
}

const mapStateToProps = (state, ownProps) => {
  console.log(state)
  if(!state._persist.rehydrated){
    return {};
  }
  
  if(state.auth.isLoggedIn){
    let yeekees = state.lotterys.data.find((val) => { return val.tid == 67 });
    return {  loggedIn: true, 
              user:state.auth.user, 
              rounds: yeekees.rounds};
  }else{
    return { loggedIn: false };
  }
}
export default connect(mapStateToProps, null)(YeekeeListPage)