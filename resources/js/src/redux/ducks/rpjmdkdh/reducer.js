import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: []
}
export default function rpjmdKdhReducer(state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_RPJMD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_RPJMD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.actions,
            }
        case types.GET_LIST_RPJMD_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        default: 
            return state
    }
}