import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null
}

export default function atasanReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_ATASAN_START:
            return {
                ...state,
                loading: true
            }
    
        case types.GET_LIST_ATASAN_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
    
        case types.GET_LIST_ATASAN_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.CREATE_ATASAN_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_ATASAN_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_ATASAN_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        default:
            return state
    }
}