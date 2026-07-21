import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    list: [],
    sasaran: null
}

export default function rkpdKdhReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_RKPDKDH_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_RKPDKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list: actions.payload.data
            }
        case types.GET_LIST_RKPDKDH_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        
        case types.CREATE_RKPDKDH_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_RKPDKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_RKPDKDH_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        
        case types.CREATE_PROGRAM_RKPD_KDH_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_PROGRAM_RKPD_KDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_PROGRAM_RKPD_KDH_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_LIST_PROGRAM_RKPD_KDH_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PROGRAM_RKPD_KDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                sasaran: actions.payload.data,
                message: actions.payload.message
            }
        case types.GET_LIST_PROGRAM_RKPD_KDH_FAILED:
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