import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    list: [],
    sasaran: null
}

export default function pkOpdReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_PKOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PKOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list: actions.payload.data
            }
        case types.GET_LIST_PKOPD_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        
        case types.CREATE_PKOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_PKOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_PKOPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        
        case types.CREATE_PROGRAM_PKOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_PROGRAM_PKOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_PROGRAM_PKOPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_LIST_PROGRAM_PKOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PROGRAM_PKOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                sasaran: actions.payload.data,
                message: actions.payload.message
            }
        case types.GET_LIST_PROGRAM_PKOPD_FAILED:
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