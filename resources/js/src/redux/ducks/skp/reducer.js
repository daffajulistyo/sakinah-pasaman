import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    list_periode: [],
    periode_skp: null,
    list_sasaran_yang_diampu: [],
    list_skp: [],
    list_skp_realisasi: [],
    list_rencana_aksi: [],
    list_rencana_aksi_realisasi: [],
    indikator: null
}

export default function skpReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_PERIODE_SKP_START:
            return {
                ...state,
                loading: true,
                list_periode: []
            }
        case types.GET_LIST_PERIODE_SKP_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list_periode: actions.payload.data
            }
        case types.GET_LIST_PERIODE_SKP_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        

        case types.GET_PERIODE_SKP_START:
            return {
                ...state,
                loading: true,
                data: null
            }
        case types.GET_PERIODE_SKP_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                periode_skp: actions.payload.data
            }
        case types.GET_PERIODE_SKP_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                periode_skp: null
            }


        case types.CREATE_PERIODE_SKP_START:
            return {
                ...state,
                loading: true,
                data: null
            }
        case types.CREATE_PERIODE_SKP_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
        case types.CREATE_PERIODE_SKP_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_LIST_SASARAN_YANG_DIAMPU_START:
            return {
                ...state,
                loading: true,
                list_sasaran_yang_diampu: []
            }
        case types.GET_LIST_SASARAN_YANG_DIAMPU_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list_sasaran_yang_diampu: actions.payload.data
            }
        case types.GET_LIST_SASARAN_YANG_DIAMPU_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_LIST_SKP_START:
            return {
                ...state,
                loading: true,
                list_skp: []
            }
        case types.GET_LIST_SKP_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list_skp: actions.payload.data
            }
        case types.GET_LIST_SKP_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.CREATE_SKP_START:
            return {
                ...state,
                loading: true,
                data: null
            }
        case types.CREATE_SKP_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.CREATE_SKP_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.UPDATE_SKP_START:
            return {
                ...state,
                loading: true,
                data: null
            }
        case types.UPDATE_SKP_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
        case types.UPDATE_SKP_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.DELETE_SKP_START:
            return {
                ...state,
                loading: true,
                data: null
            }
        case types.DELETE_SKP_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.DELETE_SKP_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_INDIKATOR_SKP_START:
            return {
                ...state,
                loading: true,
                indikator: null,
            }
        case types.GET_INDIKATOR_SKP_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                indikator: actions.payload.data
            }
        case types.GET_INDIKATOR_SKP_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.GET_LIST_RENCANA_AKSI_START:
            return {
                ...state,
                loading: true,
                list_rencana_aksi: []
            }
        case types.GET_LIST_RENCANA_AKSI_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list_rencana_aksi: actions.payload.data
            }
        case types.GET_LIST_RENCANA_AKSI_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.CREATE_RENCANA_AKSI_START:
            return {
                ...state,
                loading: true,
                data: null
            }
        case types.CREATE_RENCANA_AKSI_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
        case types.CREATE_RENCANA_AKSI_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        case types.UPDATE_RENCANA_AKSI_START:
            return {
                ...state,
                loading: true,
                data: null
            }
        case types.UPDATE_RENCANA_AKSI_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
        case types.UPDATE_RENCANA_AKSI_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }


        case types.DELETE_RENCANA_AKSI_START:
            return {
                ...state,
                loading: true,
                data: null
            }
        case types.DELETE_RENCANA_AKSI_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
        case types.DELETE_RENCANA_AKSI_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        
        case types.GET_LIST_REALISASI_SKP_START:
            return {
                ...state,
                loading: true,
                list_skp_realisasi: []
            }
        case types.GET_LIST_REALISASI_SKP_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                list_skp_realisasi: actions.payload.data
            }
        case types.GET_LIST_REALISASI_SKP_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }

        
        case types.UPDATE_REALISASI_SKP_START:
            return {
                ...state,
                loading: true,
                error: false,
                data: null
            }
        case types.UPDATE_REALISASI_SKP_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                data: actions.payload.data,
                message: actions.payload.message
            }
        case types.UPDATE_REALISASI_SKP_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        
        case types.GET_LIST_RENCANA_AKSI_REALISASI_START:
            return {
                ...state,
                loading: true,
                list_rencana_aksi_realisasi: [],
            }
        case types.GET_LIST_RENCANA_AKSI_REALISASI_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                list_rencana_aksi_realisasi: actions.payload.data,
                message: actions.payload.message
            }
        case types.GET_LIST_RENCANA_AKSI_REALISASI_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        
        case types.UPDATE_RENCANA_AKSI_REALISASI_START:
            return {
                ...state,
                loading: true,
                data: null
            }
        case types.UPDATE_RENCANA_AKSI_REALISASI_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                data: actions.payload.data,
                message: actions.payload.message
            }
        case types.UPDATE_RENCANA_AKSI_REALISASI_FAILED:
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