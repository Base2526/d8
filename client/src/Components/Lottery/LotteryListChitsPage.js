import React, { Component } from 'react';
import ReactList from 'react-list';
import { connect } from 'react-redux'

class LotteryListChitsPage extends Component {
  constructor(props) {
    super(props);
    this.state = {};

    this.renderSquareShareItem = this.renderSquareShareItem.bind(this);
  }

  componentDidMount() {
  }

  renderSquareShareItem(index, key){
  return <div key={key}>{index}</div>
  }
  
  render() {
    let {chits} = this.props
    

    let lotterys = [{'tid':66, 'name':'หวยรัฐบาลไทย'}, {'tid':67, 'name':'จับยี่กี VIP'}, {'tid':68, 'name':'หวยฮานอย'}]
    return lotterys.map((value, key) =>{
      switch(value.tid){
        // หวยรัฐบาลไทย
        case 66:{
          return (
            <div key={key}>
              <p>{value.name}</p>
              <ReactList
                key={key}
                itemRenderer={this.renderSquareShareItem}
                length={10}
                type='uniform'/>
            </div>
          );
        }

        // จับยี่กี VIP
        case 67:{
          return (
            <div key={key}>
              <p>{value.name}</p>
              <ReactList
                key={key}
                itemRenderer={this.renderSquareShareItem}
                length={10}
                type='uniform'/>
            </div>
          );
        }

        // หวยฮานอย
        case 68:{
          return (
            <div key={key}>
              <p>{value.name}</p>
              <ReactList
                key={key}
                itemRenderer={this.renderSquareShareItem}
                length={10}
                type='uniform'/>
            </div>
          );
        }
      }
    });
  }
}

const mapStateToProps = (state, ownProps) => {
  console.log(state)
  if(!state._persist.rehydrated){
    return {};
  }

  if(state.auth.isLoggedIn){
    let chits = state.auth.user.chits;
    console.log(chits)
    return {  loggedIn: true, chits};
  }else{
    return { loggedIn: false };
  }
}

export default connect(mapStateToProps, null)(LotteryListChitsPage)