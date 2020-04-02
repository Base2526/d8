import * as types from "../actions/types";

const initialState = {
  username: "",
  password: "",
  isLoggedIn: false
};

const auth = (state = initialState, action) => {
  switch (action.type) {
    case types.AUTH_LOGIN:
      return {
        ...state,
        username: action.username,
        password: action.password,
        isLoggedIn: true
      };
    case types.AUTH_LOGOUT:
      return initialState;
    default:
      return state;
  }
};

export default auth;
