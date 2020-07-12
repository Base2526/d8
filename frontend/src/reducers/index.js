import { combineReducers } from 'redux'

import auth from './auth'
import todos from './todos'
import visibilityFilter from './visibilityFilter'

import loading_overlay  from './loading_overlay'
import huay_list_bank   from './huay_list_bank'
import transfer_method  from './transfer_method'
import contact_us       from './contact_us'
import list_bank        from './list_bank'
import yeekee_round     from './yeekee_round'
import lotterys         from './lotterys'
import shoot_numbers    from './shoot_numbers'

import deposit_status   from './deposit_status'

import socket_io        from './socket_io'

export default combineReducers({
  auth,
  todos,
  visibilityFilter,
  
  loading_overlay,
  huay_list_bank,
  transfer_method,
  contact_us,
  list_bank,

  yeekee_round,
  lotterys,
  shoot_numbers,

  deposit_status,

  socket_io,
})