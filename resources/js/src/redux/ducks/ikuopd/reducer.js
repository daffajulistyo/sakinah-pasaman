import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: false,
    list: [],
    data: null
}


export default function ikuOpdReducer (state = initialState, actions){
    switch(actions.type) {
        case types.UPDATE_IKU_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.UPDATE_IKU_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.UPDATE_IKU_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        case types.GET_LIST_IKU_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_IKU_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list: actions.payload.data
            }
        case types.GET_LIST_IKU_OPD_FAILED:
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