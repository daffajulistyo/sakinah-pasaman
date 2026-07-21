import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    list: [],
    pagination: {
        page: 1,
        per_page: 10,
        total_records: 0,
        total_page: 1,
        search: ""
    }
}

export default function misiKdhReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_MISIKDH_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_MISIKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list: actions.payload.data,
                pagination: {
                    page: actions.payload.pagination.page,
                    per_page: actions.payload.pagination.per_page,
                    total_records: actions.payload.pagination.total_records,
                    total_page: actions.payload.pagination.total_page,
                    search: actions.payload.pagination.search,
                }
            }
        case types.GET_LIST_MISIKDH_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.CREATE_MISIKDH_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_MISIKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_MISIKDH_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.GET_MISIKDH_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_MISIKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
        case types.GET_MISIKDH_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.UPDATE_MISIKDH_START:
            return {
                ...state,
                loading: true
            }
        case types.UPDATE_MISIKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.UPDATE_MISIKDH_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.DELETE_MISIKDH_START:
            return {
                ...state,
                loading: true,
            }
        case types.DELETE_MISIKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.DELETE_MISIKDH_FAILED:
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