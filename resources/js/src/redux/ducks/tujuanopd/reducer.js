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
    },
    list_sasaranDiampu: []
}

export default function tujuanOpdReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_SASARANDIAMPU_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_SASARANDIAMPU_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list_sasaranDiampu: actions.payload.data
            }
        case types.GET_LIST_SASARANDIAMPU_OPD_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_LIST_TUJUANOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_TUJUANOPD_SUCCESS:
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
        case types.GET_LIST_TUJUANOPD_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_TUJUANOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_TUJUANOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
        case types.GET_TUJUANOPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.UPDATE_TUJUANOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.UPDATE_TUJUANOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.UPDATE_TUJUANOPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.DELETE_TUJUANOPD_START:
            return {
                ...state,
                loading: true,
            }
        case types.DELETE_TUJUANOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.DELETE_TUJUANOPD_FAILED:
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
