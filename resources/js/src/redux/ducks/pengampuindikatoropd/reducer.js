import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    list: [],
    list_pegawai: []
}

export default function pengampuIndikatorOpdReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_PEGAWAI_PENGAMPUINDIKATOR_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PEGAWAI_PENGAMPUINDIKATOR_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list_pegawai: actions.payload.data
            }
        case types.GET_LIST_PEGAWAI_PENGAMPUINDIKATOR_FAILED: 
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        
        case types.GET_LIST_PENGAMPUINDIKATOR_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PENGAMPUINDIKATOR_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list: actions.payload.data
            }
        case types.GET_LIST_PENGAMPUINDIKATOR_FAILED: 
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.CREATE_PENGAMPUINDIKATOR_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_PENGAMPUINDIKATOR_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_PENGAMPUINDIKATOR_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.UPDATE_PENGAMPUINDIKATOR_START:
            return {
                ...state,
                loading: true
            }
        case types.UPDATE_PENGAMPUINDIKATOR_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.UPDATE_PENGAMPUINDIKATOR_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.DELETE_PENGAMPUINDIKATOR_START:
            return {
                ...state,
                loading: true,
            }
        case types.DELETE_PENGAMPUINDIKATOR_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.DELETE_PENGAMPUINDIKATOR_FAILED:
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