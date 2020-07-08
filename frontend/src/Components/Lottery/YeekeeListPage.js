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
    this.handleClick      = this.handleClick.bind(this);
    this.renderItem       = this.renderItem.bind(this);
  }

  componentDidMount() {
    let {rounds} = this.props

    // rounds = rounds.map((v, k) =>{return {...v, time:getTime(v)}})
    // this.setState({rounds})

    // interval = setInterval(() => {
    //   let {rounds} = this.props
    //   rounds = rounds.map((v, k) =>{ return {...v, time:getTime(v)} })
    //   this.setState({rounds})
    // }, 1000);
  }

  componentWillUnmount(){
    if(interval){
      clearInterval(interval);
    }
  }

  handleClick = (e, round) => {
    let {history} = this.props
    // if(round.time == -1){
    //   history.push({pathname: '/lottery-list/reward',
    //                 state: { type:'yeekee', tid:round.tid } })
    // }else{
    //   history.push({pathname: '/lottery-list/yeekee-list/chit',
    //                 state: { type:'yeekee', tid:round.tid } })
    // }
  }

  renderItem(index, key){
    let {rounds} = this.props
    let round = rounds[index];
    // if(round.time == -1){
    //   return<a key={key} onClick={((e) => this.handleClick(e, round))}>
    //         <div className={'square-item' + (index % 2 ? '' : ' even')}>
    //           <p>รอบที่ {round.name} เวลาปิด {round.end_time}</p>
    //           <p>ปิดรับแทง</p>
    //         </div>
    //         </a>
    // }else{
      return<a key={key} onClick={((e) => this.handleClick(e, round))}>
              <div key={key} className={'square-item' + (index % 2 ? '' : ' even')}>
                  <p>รอบที่ {round.name} เวลาปิด { /*round.end_time*/ }</p>
                  <div>เวลาที่เหลือ : { /*round.time*/ }</div>
              </div>
            </a>
    // }
  }

  render(){
    let {rounds} = this.props
    return  ( <div>
                <ReactList
                  useTranslate3d={true}
                  itemRenderer={this.renderItem}
                  length={rounds.length}
                  updateWhenThisValueChanges={rounds}
                  type='uniform'/>
              </div>);
  }
}

const mapStateToProps = (state, ownProps) => {
  if(!state._persist.rehydrated){
    return {};
  }
  
  if(state.auth.isLoggedIn){
    let yeekees = state.lotterys.data.find((val) => { return val.tid == 67 });
    let rounds =  yeekees.rounds.sort(function(a, b) {
                    return a.weight - b.weight;
                  });

    console.log(rounds)
    return {  loggedIn: true, 
              user:state.auth.user, 
              rounds};
  }else{
    return { loggedIn: false };
  }
}
export default connect(mapStateToProps, null)(YeekeeListPage)