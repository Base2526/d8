import _ from 'lodash';

import * as types from "../actions/types";
import {isEmpty}  from "../Components/Utils/Config"

const initialState = {
    data: []
};

const shoot_numbers = (state = initialState, action) => {
  switch (action.type) {
    case types.SHOOT_NUMBERS:
      let state_data = state.data;
      if(isEmpty(state_data)){
          return {
              ...state,
              data:[action.data],
          };
      }

      let find = _.find(state_data,  function(v, k) {return v._id == action.data._id})
      if(isEmpty(find)){
          return {
              ...state,
              data:[...state_data, action.data],
          };
      }

      return state;
    default:
      return state;
  }
};

export default shoot_numbers;
