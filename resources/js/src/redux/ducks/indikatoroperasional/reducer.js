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
    ref: []
}

export default function indikatorOperasionalOpdReducer (state = initialState, actions){
    switch(actions.type){

        case types.GET_REF_INDIKATOROPERASIONALOPD_START:
            return {
                ...state,
                loading: true,
                ref: []
            }
        case types.GET_REF_INDIKATOROPERASIONALOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                ref: actions.payload.data
            }
        case types.GET_REF_INDIKATOROPERASIONALOPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_LIST_INDIKATOROPERASIONALOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_INDIKATOROPERASIONALOPD_SUCCESS:
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
        case types.GET_LIST_INDIKATOROPERASIONALOPD_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.CREATE_INDIKATOROPERASIONALOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_INDIKATOROPERASIONALOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_INDIKATOROPERASIONALOPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.GET_INDIKATOROPERASIONALOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_INDIKATOROPERASIONALOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
        case types.GET_INDIKATOROPERASIONALOPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.DELETE_INDIKATOROPERASIONALOPD_START:
            return {
                ...state,
                loading: true,
            }
        case types.DELETE_INDIKATOROPERASIONALOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.DELETE_INDIKATOROPERASIONALOPD_FAILED:
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