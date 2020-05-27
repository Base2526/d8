import React, { Component } from 'react';
import ReactList from 'react-list';
import { connect } from 'react-redux'
import Button from 'react-bootstrap/Button'
import Modal from 'react-bootstrap/Modal'

import { isEmpty, convertTimestamp2Date } from '../Utils/Config';

import '../../index.css';

class LotteryListChitsPage extends Component {
  constructor(props) {
    super(props);
    this.state = {
      modal_show_detail:false
    };

    this.renderSquareShareItem = this.renderSquareShareItem.bind(this);
    this.handleShowDetailClick = this.handleShowDetailClick.bind(this);
    this.showDetail = this.showDetail.bind(this);
  }

  componentDidMount() {
  }

  showDetail(){
    return <Modal
      show={true}
      onHide={() => {
        this.setState({modal_show_detail: false})
      }}
      dialogClassName="modal-90w"
      aria-labelledby="example-custom-modal-styling-title"
      backdrop="static"
      scrollable={true}>
      <Modal.Header closeButton>
        <Modal.Title id="example-custom-modal-styling-title">
        รายละเอียด
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
      </Modal.Body>
      <Modal.Footer>
        
      </Modal.Footer>
    </Modal>
  }

  handleShowDetailClick = ()=>{
    this.setState({modal_show_detail:true});
  }

  renderSquareShareItem(index, key){
    let {chits} = this.props

    let chit = chits[index];
    return<div key={key} className={'square-item'} >
            <div >ชื่อ : {chit.chit_type_name}</div>
            <div >รอบ : {chit.round_name}</div>
            <div >สถานะ : {chit.chit_status_name}</div> 
            <div >เวลา : {convertTimestamp2Date(chit.changed)}</div>
            <Button onClick={() =>{ this.handleShowDetailClick() }}>ดูรายละเอียด</Button>
          </div>
  }

  // new Date( 1588719600000 )).format("dd.mm.yyyy hh:MM:ss")

  // var t = new Date( 1588719600000 );
    // var formatted = t.format("dd.mm.yyyy hh:MM:ss");
  
  render() {
    let {chits} = this.props

    var date = new Date(1588719600000*1000);
    console.log(date);

    
    return  <div> 
              { this.state.modal_show_detail ? this.showDetail() : '' }
              <ReactList
                itemRenderer={this.renderSquareShareItem}
                length={chits.length}
                type='uniform'/>
            </div>
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