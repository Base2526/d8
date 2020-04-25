import * as types from "../actions/types";

import {disconnect_socketIO} from '../socket.io'

const initialState = {
  data:{},
  isLoggedIn: false
};

const auth = (state = initialState, action) => {
  switch (action.type) {
    case types.AUTH_LOGIN:
      console.log(action);
      return {
        ...state,
        user:action.user,
        // username: action.username,
        // password: action.password,
        isLoggedIn: true
      };
    case types.AUTH_LOGOUT:

      console.log(disconnect_socketIO());
      
      return initialState;
    default:
      return state;
  }
};

export default auth;
