import * as types from "../actions/types";

const initialState = {
    data: []
};

const awards = (state = initialState, action) => {
    switch (action.type) {
        case types.ADD_AWARD:
            console.log(state.data);
            console.log(action);
            return {
                ...state,
                data:[action.data],
            };
        default:
        return state;
    }
};

export default awards;
