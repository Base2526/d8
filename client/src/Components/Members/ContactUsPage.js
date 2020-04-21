import React, { Component } from 'react';
import Alert from 'react-bootstrap/Alert'
import Button from 'react-bootstrap/Button'
import Container from 'react-bootstrap/Container'
import Row from 'react-bootstrap/Row'
import Col from 'react-bootstrap/Col'

import Form from 'react-bootstrap/Form'
import InputGroup from 'react-bootstrap/InputGroup'
import Image from 'react-bootstrap/Image'

var styles = {
    simage: {
        width: '200px',
        height: '200px'
    }
}

class ContactUsPage extends Component {
  constructor(props) {
    super(props);
    this.state = {
      validated:false
    }
  }

  componentDidMount() {
  }

  render() {
    let { validated } = this.state;
    return (<Container>
                <Row>
                <Col>
                    <Image style={styles.simage} src="data:image/png;base64,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" rounded />
                </Col>
                </Row>
            </Container>)
  }
}

export default ContactUsPage;