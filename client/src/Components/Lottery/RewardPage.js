import React, { Component } from 'react';
import { connect } from 'react-redux'

class RewardPage extends Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {
  }

  render() {
    let {user, match} = this.props;

    console.log(match)
  return (<div>RewardPage type > {match.params.type}, id > {match.params.id}</div>);
  }
}

const mapStateToProps = (state, ownProps) => {
	if(!state._persist.rehydrated){
		return {};
    }
    
    if(state.auth.isLoggedIn){
        return { loggedIn: true, user:state.auth.user };
    }else{
        return { loggedIn: false };
    }
}
export default connect(mapStateToProps, null)(RewardPage)