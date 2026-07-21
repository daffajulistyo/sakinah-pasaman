import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    list: [],
    options: []
}

export default function cascadingKdhReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_CASCADINGKDH_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_CASCADINGKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list: actions.payload.data
            }
        case types.GET_LIST_CASCADINGKDH_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        case types.CREATE_CASCADINGKDH_START: 
            return {
                ...state,
                loading: true
            }
        case types.CREATE_CASCADINGKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_CASCADINGKDH_FAILED:
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