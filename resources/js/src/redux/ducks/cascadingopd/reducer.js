import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    list: [],
    options: []
}

export default function cascadingOpdReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_CASCADINGOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_CASCADINGOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list: actions.payload.data
            }
        case types.GET_LIST_CASCADINGOPD_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        case types.CREATE_CASCADINGOPD_START: 
            return {
                ...state,
                loading: true
            }
        case types.CREATE_CASCADINGOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_CASCADINGOPD_FAILED:
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