import * as types from "../actions/types";
import _ from 'lodash';

import {isEmpty}  from "../Components/Utils/Config"

const initialState = {
    data: []
};

const awards = (state = initialState, action) => {
    switch (action.type) {
        case types.ADD_AWARD:
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
        default:{
            return state;
        }
    }
};

export default awards;
