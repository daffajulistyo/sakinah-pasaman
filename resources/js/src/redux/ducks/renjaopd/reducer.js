import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    list: [],
    sasaran: null
}

export default function renjaOpdReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_RENJAOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_RENJAOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list: actions.payload.data
            }
        case types.GET_LIST_RENJAOPD_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        
        case types.CREATE_RENJAOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_RENJAOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_RENJAOPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        
        case types.CREATE_PROGRAM_RENJAOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_PROGRAM_RENJAOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_PROGRAM_RENJAOPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_LIST_PROGRAM_RENJAOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PROGRAM_RENJAOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                sasaran: actions.payload.data,
                message: actions.payload.message
            }
        case types.GET_LIST_PROGRAM_RENJAOPD_FAILED:
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