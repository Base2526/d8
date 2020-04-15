import React, { Component } from 'react';
import { makeStyles } from '@material-ui/core/styles';
import Card from '@material-ui/core/Card';
import CardContent from '@material-ui/core/CardContent';
import Typography from '@material-ui/core/Typography';
// import InputAutocomplete from './InputAutocomplete/InputAutocomplete';
import { Input, DatePicker, Radio, Button, Row, Col } from 'antd';
import { RightCircleOutlined } from '@ant-design/icons';
import 'antd/dist/antd.css';
import '../RideSelect/RideSelect.css';
import ls from 'local-storage';

import Select from '@material-ui/core/Select';
import MenuItem from '@material-ui/core/MenuItem';
import InputLabel from '@material-ui/core/InputLabel';
import FormControl from '@material-ui/core/FormControl';
import TextField from '@material-ui/core/TextField';



import { withStyles } from '@material-ui/styles';

import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import 'react-tabs/style/react-tabs.css';

import history from '../../history';
import { Redirect, Link} from 'react-router-dom';

const useStyles = makeStyles((theme) => ({
  formControl: {
    margin: theme.spacing(1),
    minWidth: 120,
  },
  selectEmpty: {
    marginTop: theme.spacing(2),
  },
}));


class DepositPage extends Component {
  constructor(props) {
    super(props);
    this.state = {bank:""};

    this.handleSelectChange = this.handleSelectChange.bind(this);
  }

  componentDidMount() {
   
  }

  submitForm = async e => {
    e.preventDefault();
    console.log('submitForm');
    // const { user, pass } = this.state;
    console.log(this.state);
  }
  
  handleSelectChange = (event) => {
    this.setState({bank:event.target.value});
  }

  render() {
    const { history, classes } = this.props;
    let {bank} = this.state;
    console.log(bank);
    return (
        <form onSubmit={this.submitForm} >    
        <div style={{minWidth: 275}}>
            <div>
            แจ้งเติมเครดิต
            </div>

            <div>
              <div>
                <div>Step 1 : เลือกบัญชีธนาคารของเว็บฯ</div>
                <FormControl className={classes.formControl}>
                  <InputLabel id="demo-simple-select-label">เลือกธนาคาร</InputLabel>
                  <Select
                      labelId="demo-simple-select-label"
                      id="demo-simple-select"
                      value={this.state.bank}
                      onChange={this.handleSelectChange}>
                      <MenuItem value="">
                          <em>None</em>
                      </MenuItem>
                      <MenuItem value={10}>ธ. กรุงเทพ</MenuItem>
                      <MenuItem value={20}>ธ. กสิกรไทย</MenuItem>
                      <MenuItem value={30}>ธ. กรุงไทย</MenuItem>
                      </Select>
                </FormControl>
                <br />
              </div>
              <div>
                <div>Step 2 : โอนเงินเพื่อเติมเครดิต</div>
                <div>
                  โอนขั้นตํ่า "ครั้งละ 20 บาท" ถ้าโอนตํ่ากว่า 20 บาทเงินของท่านจะไม่เข้าระบบ
                  และไม่สามารถคืนได้
                </div>

                <div>
                  คำเตือน! กรุณาใช้บัญชีที่ท่านผูกกับ XXX ในการโอนเงินเท่านั้น
                </div>

                <div>
                  เมือท่านทำการโอนเงินไปยังบัญชีข้างต้นเรียบร้อยแล้ว (เก็บสลิปการโอนไว้ทุกครั้ง)
                  "คลิกปุ่มด้านล่าง" เพือแจ้งการโอนเงิน
                </div>
                <Button type="submit" variant="contained">เมือโอนเงินแล้วคลิกที่นี้</Button>
              </div>
            
              <div>
                <div>Step 3 : แจ้งรายละเอียดการโอนเงิน</div>
                <div>กรุณาโอนเงินเข้าบัญชีด้านบน ภาญใน 5 นาที</div>
                <div>
                  <div>เลือกบัญชีธนาคารของลูกค้า</div>
                  <FormControl className={classes.formControl}>
                    <InputLabel id="demo-simple-select-label">กรุณาเลือกธนาคาร</InputLabel>
                    <Select
                        labelId="demo-simple-select-label"
                        id="demo-simple-select"
                        value={this.state.bank}
                        onChange={this.handleSelectChange}>
                        <MenuItem value="">
                            <em>None</em>
                        </MenuItem>
                        <MenuItem value={10}>ธ. กรุงเทพ</MenuItem>
                        <MenuItem value={20}>ธ. กสิกรไทย</MenuItem>
                        <MenuItem value={30}>ธ. กรุงไทย</MenuItem>
                        </Select>
                  </FormControl>
                </div>
                <div>
                  <div>เลือกช่องทางการโอนเงิน</div>
                  <FormControl className={classes.formControl}>
                    <InputLabel id="demo-simple-select-label">กรุณาเลือกช่องทางธุรกรรม</InputLabel>
                    <Select
                        labelId="demo-simple-select-label"
                        id="demo-simple-select"
                        value={this.state.bank}
                        onChange={this.handleSelectChange}>
                        <MenuItem value="">
                            <em>None</em>
                        </MenuItem>
                        <MenuItem value={10}>ธ. กรุงเทพ</MenuItem>
                        <MenuItem value={20}>ธ. กสิกรไทย</MenuItem>
                        <MenuItem value={30}>ธ. กรุงไทย</MenuItem>
                        </Select>
                  </FormControl>
                </div>
                <div>
                  {/* <div>จำนวนเงินที่โอน</div> */}
                  <TextField id="outlined-basic" label="จำนวนเงินที่โอน" variant="outlined" />
                </div>
              </div>
            </div>
        </div>
        </form>
    );
  }
}

export default withStyles(useStyles)(DepositPage);