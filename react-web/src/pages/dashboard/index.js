import React from 'react';
// import axios from 'axios';

import { connect } from 'react-redux'
import { addTodo } from '../../actions'

import { config } from '../../utils/Config';

class Dashboard extends React.Component {

	constructor(props) {
		super(props);

		this.state = {
			suggestions: [],
			searchedPlaces: []
		};

		this.getSearchedPlaces();

		this.addTodo = this.addTodo.bind(this);
		this.addTodo2 = this.addTodo2.bind(this);

		this.logout = this.logout.bind(this);
		this.suggestLocations = this.suggestLocations.bind(this);
	}

	addTodo(e){
		this.props.addTodo('addTodo');
	}

	addTodo2(e){
		this.props.addTodo2('addTodo2', 'xxxx');
	}

	logout(e) {
		e.preventDefault();
		
		
        
        // this.props.authenticate({isLoggedIn: false});

		// axios.post(config.baseUrl + 'logout', {})
		//     .then(response => {
		//     	if (response.data && response.data.success) {
		//     		this.props.authenticate({isLoggedIn: false});
		//     	} 
		//     }).catch(err => {
		//     	console.error(err);
		//     });
	}

	suggestLocations(e) {
		const params = {
			input: e.target.value
		};

		// check if user has selected some place
		for (let i = 0; i < this.state.suggestions.length; i++) {
			let place = this.state.suggestions[i];
			if (place == params.input) {
				this.saveSearch(place);
				break;
			}
		}

		// axios.post(config.baseUrl + 'dashboard/get/placeSuggestions', params)
		//     .then(response => {
		//     	if (response.data && response.data.success) {
		//     		this.setState({
		//     			suggestions: response.data.suggestions
		//     		});
		//     	} 
		//     }).catch(err => {
		//     	console.error(err);
		//     });
	}

	saveSearch(place) {
		const params = {
			name: place
		};

		// axios.post(config.baseUrl + 'dashboard/save/searchedPlace', params)
		//     .then(response => {
		//     	if (response.data && response.data.success) {
		//     		console.log(response.data.place, ' Saved');
		//     	} 
		//     }).catch(err => {
		//     	console.error(err);
		//     });
	}

	getSearchedPlaces() {
		// axios.post(config.baseUrl + 'dashboard/get/searchedPlaces', {})
		//     .then(response => {
		//     	if (response.data && response.data.success) {
		//     		this.setState({
		//     			searchedPlaces: response.data.searchedPlaces
		//     		});
		//     	} 
		//     }).catch(err => {
		//     	console.error(err);
		//     });
	}

	render() {

		// this.props;
		// this.state;
		console.log('Dashboard');

		let {user} = this.props
		console.log(user);
		return (
			<div class="container-fluid">
			    <div class="jumbotron">
			        <p>
						<button class="btn btn-primary" onClick={this.addTodo}>addTodo</button>
						<button class="btn btn-primary" onClick={this.addTodo2}>addTodo 2</button>
			            <button class="btn btn-primary" onClick={this.logout}>Logout</button>
			        </p>
			        <h1>Hello {this.props.user.name}</h1>
			        <form>
			            <div class="form-group">
				            <label>Choose your favourite place:</label>
				            <input type="text" class="form-control" onChange={this.suggestLocations} list="suggestions" />
				        </div>
				        <datalist id="suggestions">
				            {this.state.suggestions.map((place, index) => (
				            	<option key={index}>{place}</option>
				            ))}
				        </datalist>
			        </form>
			        <br />

			        <h2>Your Searched Places:</h2>
			        <ol>
			            {this.state.searchedPlaces.map((place, index) => (
			            	<li key={index}>{place.name} at {new Date(place.timestamp).toLocaleString()}</li>
			            ))}
			        </ol>
			    </div>
			</div>   
		);
	}
}

function mapStateToProps(state, ownProps) {
	console.log(state);
	return { user: 'somkid' };
}

// function mapDispatchToProps(state){
// 	console.log(state);
// 	return {addTodo}
// };

const mapDispatchToProps = (dispatch) => {
	console.log(dispatch);

	return {
		addTodo: (id) => {
							dispatch(addTodo(id))
						},
		addTodo2: (id, val) => {
							dispatch(addTodo(val))
						}

	}
}

export default connect(mapStateToProps, mapDispatchToProps)(Dashboard)