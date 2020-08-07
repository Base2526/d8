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

            if(action.data.type_lotterys == '67'){
                let find = _.find(state_data,  function(v, k) {return v.round_tid == action.data.round_tid &&  v.date == action.data.date })
                if(isEmpty(find)){
                    return {
                        ...state,
                        data:[...state_data, action.data],
                    };
                }
            }else{
                let find = _.find(state_data,  function(v, k) {return v.type_lotterys == action.data.type_lotterys })
            
                if(isEmpty(find)){
                    return {
                        ...state,
                        data:[...state_data, action.data],
                    };
                }
            }
        
            return state;

        case types.DELETE_AWARD:{
            return initialState;
        }
        default:{
            return state;
        }
    }
};

export default awards;
