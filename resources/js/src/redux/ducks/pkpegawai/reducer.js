import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    list: [],
    sasaran: null
}

export default function pkPegawaiReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_PKPEGAWAI_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PKPEGAWAI_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list: actions.payload.data
            }
        case types.GET_LIST_PKPEGAWAI_FAILED:
            return{
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        
        case types.CREATE_PKPEGAWAI_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_PKPEGAWAI_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_PKPEGAWAI_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        
        case types.CREATE_PROGRAM_PKPEGAWAI_START:
            return {
                ...state,
                loading: true
            }
        case types.CREATE_PROGRAM_PKPEGAWAI_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_PROGRAM_PKPEGAWAI_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_LIST_PROGRAM_PKPEGAWAI_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PROGRAM_PKPEGAWAI_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                sasaran: actions.payload.data,
                message: actions.payload.message
            }
        case types.GET_LIST_PROGRAM_PKPEGAWAI_FAILED:
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