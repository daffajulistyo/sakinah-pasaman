import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    list: [],
    list_langkah: []
}

export default function realisasiRenaksiOpdReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_REALISASI_RENAKSIOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_REALISASI_RENAKSIOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list: actions.payload.data
            }
        case types.GET_LIST_REALISASI_RENAKSIOPD_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.CREATE_REALISASI_RENAKSIOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_REALISASI_RENAKSIOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_REALISASI_RENAKSIOPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_LIST_REALISASI_RENAKSIOPD_LANGKAH_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_REALISASI_RENAKSIOPD_LANGKAH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list_langkah: actions.payload.data
            }
        case types.GET_LIST_REALISASI_RENAKSIOPD_LANGKAH_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
            
        case types.CREATE_REALISASI_RENAKSIOPD_LANGKAH_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_REALISASI_RENAKSIOPD_LANGKAH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_REALISASI_RENAKSIOPD_LANGKAH_FAILED:
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