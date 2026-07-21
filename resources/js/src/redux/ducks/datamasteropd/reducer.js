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
    options: []
}

export default function datamasterOpdReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_DATAMASTEROPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_DATAMASTEROPD_SUCCESS:
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
        case types.GET_LIST_DATAMASTEROPD_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.CREATE_DATAMASTEROPD_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_DATAMASTEROPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_DATAMASTEROPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.GET_DATAMASTEROPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_DATAMASTEROPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
        case types.GET_DATAMASTEROPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.UPDATE_DATAMASTEROPD_START:
            return {
                ...state,
                loading: true
            }
        case types.UPDATE_DATAMASTEROPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.UPDATE_DATAMASTEROPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.DELETE_DATAMASTEROPD_START:
            return {
                ...state,
                loading: true,
            }
        case types.DELETE_DATAMASTEROPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.DELETE_DATAMASTEROPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        case types.GET_OPTIONSOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_OPTIONSOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                options: actions.payload.data
            }
        case types.GET_OPTIONSOPD_FAILED:
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