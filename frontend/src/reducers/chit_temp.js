import * as types from "../actions/types";
import _, { find } from 'lodash';

import {isEmpty}  from "../Components/Utils/Config"

const initialState = {
    m: {mode:3, mi:['type_3_up']},
    datas: [],
};

const chit_temp = (state = initialState, action) => {
    switch (action.type) {
        case types.CHIT_TEMP_SET_TYPE:{
            
            /*
            let {m} = state

            // console.log(action)
            
            let tmp_m = {}

            let {type, props}= action.data

            // console.log( props.location.state )

            let { type_lotterys, tid } = props.location.state
            console.log(type, props)

            switch(type){
              case 'type_3_up':{
                if(m.mode === 3){
                  if(m.mi.find((val) => { return val === type })){
                    let mi =  m.mi.filter(function(val) { return val !== type })
                    tmp_m = {...m, mi}
                  }else{
                    let mi    = [...m.mi, type]
                    tmp_m  = {...m, mi}
                  }
                }else{
                  tmp_m = {mode:3, mi:['type_3_up']}
                }
                console.log(tmp_m);
                break;
              }
              case 'type_3_toot':{
                if(m.mode === 3){
                  if(m.mi.find((val) => { return val === type })){
                    let mi =  m.mi.filter(function(val) { return val !== 'type_3_undo' })
                        mi =  m.mi.filter(function(val) { return val !== type })
                     tmp_m = {...m, mi}
                  }else{
                    let mi =  m.mi.filter(function(val) { return val !== 'type_3_undo' })
                        mi = [...mi, type]
                    tmp_m  = {...m, mi}
                  }
                }else{
                  tmp_m = {mode:3, mi:['type_3_toot']}
                }
                console.log(tmp_m);
                break;
              }
              case 'type_3_undo':{
                if(m.mode === 3){
                  if(m.mi.find((val) => { return val === type })){
                    let mi =  m.mi.filter(function(val) { return val !== 'type_3_toot' })
                        mi =  m.mi.filter(function(val) { return val !== type })
                     tmp_m = {...m, mi}
                  }else{
                    let mi =  m.mi.filter(function(val) { return val !== 'type_3_toot' })
                        mi = [...mi, type]
                    tmp_m  = {...m, mi}
                  }
                }else{
                  tmp_m = {mode:3, mi:['type_3_undo']}
                }
                console.log(tmp_m);
                break;
              }
              // 2 ตัวบน
              case 'type_2_up':{
                if(m.mode === 2){
                  if(m.mi.find((val) => { return val === type })){
                    let  mi =  m.mi.filter(function(val) { return val !== type })
                    tmp_m = {...m, mi}
                  }else{
                    let mi = [...m.mi, type]
                    tmp_m  = {...m, mi}
                  }
                }else{
                  tmp_m = {mode:2, mi:['type_2_up']}
                }
                break;
              }
        
              // 2 ตัวล่าง
              case 'type_2_down':{
                if(m.mode === 2){
                  if(m.mi.find((val) => { return val === type })){
                    let  mi =  m.mi.filter(function(val) { return val !== type })
                    tmp_m = {...m, mi}
                  }else{
                    let mi = [...m.mi, type]
                    tmp_m  = {...m, mi}
                  }
                }else{
                  tmp_m = {mode:2, mi:['type_2_down']}
                }
                break;
              }
        
              // 2 ตัวกลับ
              case 'type_2_undo':{
                if(m.mode === 2){
                  if(m.mi.find((val) => { return val === type })){
                    let  mi =  m.mi.filter(function(val) { return val !== type })
                    tmp_m = {...m, mi}
                  }else{
                    let mi = [...m.mi, type]
                    tmp_m  = {...m, mi}
                  }
                }else{
                  tmp_m = {mode:2, mi:['type_2_undo']}
                }
                break;
              }
        
              // วิ่งบน
              case 'type_1_up':{
                if(m.mode === 1){
                  if(m.mi.find((val) => { return val === type })){
                    let  mi =  m.mi.filter(function(val) { return val !== type })
                    tmp_m = {...m, mi}
                  }else{
                    let mi = [...m.mi, type]
                    tmp_m  = {...m, mi}
                  }
                }else{
                  tmp_m = {mode:1, mi:['type_1_up']}
                }
                break;
              }
        
              // วิ่งล่าง
              case 'type_1_down':{
                if(m.mode === 1){
                  if(m.mi.find((val) => { return val === type })){
                    let  mi =  m.mi.filter(function(val) { return val !== type })
                    tmp_m = {...m, mi}
                  }else{
                    let mi = [...m.mi, type]
                    tmp_m  = {...m, mi}
                  }
                }else{
                  tmp_m = {mode:1, mi:['type_1_down']}
                }
                break;
              }
            }

            console.log(action, m, tmp_m)
            */

            // let s = {...state, m:[...state.m, tmp_m]};
            // console.log(s)

            return state;
        }

        case types.ADD_CHIT_TEMP:
          let {props, data} = action.data
          let {tid, type_lotterys} = props.location.state

          return initialState;
          
          // console.log(state, props, data, tid, type_lotterys);
          // console.log(state)

          if(isEmpty(state.datas)){
            return {...state,
                          datas:[...state.datas, {tid, type_lotterys, data}]}
          }
          let findIndex = state.datas.findIndex( item => (item.tid === tid && item.type_lotterys === type_lotterys) ); 
        

          console.log( state )
          if(findIndex != -1){
            console.log('>1');

            state.datas[findIndex] = {tid, type_lotterys, data}

            let tstate = {...state,
                            datas:{
                              ...state.datas, 
                                [findIndex] : {tid, type_lotterys, data}}}

            console.log(tstate, state, data, tid, type_lotterys);
            return state;
          }else{
            console.log('<1');

            let tstate = {...state,
                            datas:[...state.datas, {tid, type_lotterys, data}]}
                            
            console.log(tstate);
            return tstate
          }

          /*
          profile: {
                            ...state.user.profile,
                            gender:action.gender_id
                        }*/
          
          // var found = array.find(function (element) { 
          //               return element > 0; 
          //             }); 

          // let state_data = state.data;
          // if(isEmpty(state_data)){
          //     return {
          //         ...state,
          //         data:[action.data],
          //     };
          // }
          // if(action.data.type_lotterys == '67'){
          //     let find = _.find(state_data,  function(v, k) {return v.round_tid == action.data.round_tid &&  v.date == action.data.date })
          //     if(isEmpty(find)){
          //         return {
          //             ...state,
          //             data:[...state_data, action.data],
          //         };
          //     }
          // }else{
          //     let find = _.find(state_data,  function(v, k) {return v.type_lotterys == action.data.type_lotterys })
          
          //     if(isEmpty(find)){
          //         return {
          //             ...state,
          //             data:[...state_data, action.data],
          //         };
          //     }
          // }        
          return state;
        case types.DELETE_CHIT_TEMP:{
            return initialState;
        }
        default:{
            return state;
        }
    }
};

export default chit_temp;
