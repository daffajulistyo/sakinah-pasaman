import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",

    data_pohonkinerja_pemda: null,
    data_visi_pemda: null,
    data_rencanakinerja_pemda: null,
    data_rpjmd_pemda: null,
    data_pk_pemda: null,
    data_renaksi_pemda: null,
    data_realisasirenaksi_pemda: null,

    daftar_opd: null,
    data_pohonkinerja_opd: null,
    data_rencanakinerja_opd: null,
    data_renstra_opd: null,
    data_pk_opd: null,
    data_renaksi_opd: null,
    data_realisasirenaksi_opd: null,
}

export default function publicDataReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_PUBLIC_POHONKINERJA_PEMDA_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_POHONKINERJA_PEMDA_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_pohonkinerja_pemda: actions.payload.data
            }
        case types.GET_PUBLIC_POHONKINERJA_PEMDA_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_pohonkinerja_pemda: []
            }

        case types.GET_PUBLIC_VISI_PEMDA_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_VISI_PEMDA_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_visi_pemda: actions.payload.data
            }
        case types.GET_PUBLIC_VISI_PEMDA_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_visi_pemda: {}
            }

        case types.GET_PUBLIC_RENCANAKINERJA_PEMDA_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_RENCANAKINERJA_PEMDA_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_rencanakinerja_pemda: actions.payload.data
            }
        case types.GET_PUBLIC_RENCANAKINERJA_PEMDA_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_rencanakinerja_pemda: {}
            }   
            
        case types.GET_PUBLIC_RPJMD_PEMDA_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_RPJMD_PEMDA_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_rpjmd_pemda: actions.payload.actions
            }
        case types.GET_PUBLIC_RPJMD_PEMDA_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_rpjmd_pemda: {}
            }   

        case types.GET_PUBLIC_PK_PEMDA_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_PK_PEMDA_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_pk_pemda: actions.payload.data
            }
        case types.GET_PUBLIC_PK_PEMDA_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_pk_pemda: {}
            }    

        case types.GET_PUBLIC_RENAKSI_PEMDA_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_RENAKSI_PEMDA_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_renaksi_pemda: actions.payload.data
            }
        case types.GET_PUBLIC_RENAKSI_PEMDA_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_renaksi_pemda: {}
            }  
            
        case types.GET_PUBLIC_REALISASIRENAKSI_PEMDA_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_REALISASIRENAKSI_PEMDA_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_realisasirenaksi_pemda: actions.payload.data
            }
        case types.GET_PUBLIC_REALISASIRENAKSI_PEMDA_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_realisasirenaksi_pemda: {}
            }  
        
        case types.GET_PUBLIC_DAFTAR_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_DAFTAR_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                daftar_opd: actions.payload.data
            }
        case types.GET_PUBLIC_DAFTAR_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                daftar_opd: {}
            }  
        
        case types.GET_PUBLIC_POHONKINERJA_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_POHONKINERJA_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_pohonkinerja_opd: actions.payload.data
            }
        case types.GET_PUBLIC_POHONKINERJA_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_pohonkinerja_opd: []
            }

        case types.GET_PUBLIC_RENCANAKINERJA_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_RENCANAKINERJA_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_rencanakinerja_opd: actions.payload.data
            }
        case types.GET_PUBLIC_RENCANAKINERJA_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_rencanakinerja_opd: {}
            }  
            
        case types.GET_PUBLIC_RENSTRA_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_RENSTRA_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_renstra_opd: actions.payload.data
            }
        case types.GET_PUBLIC_RENSTRA_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_renstra_opd: {}
            }  

        case types.GET_PUBLIC_PK_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_PK_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_pk_opd: actions.payload.data
            }
        case types.GET_PUBLIC_PK_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_pk_opd: {}
            }    

        case types.GET_PUBLIC_RENAKSI_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_RENAKSI_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_renaksi_opd: actions.payload.data
            }
        case types.GET_PUBLIC_RENAKSI_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_renaksi_opd: {}
            }  
            
        case types.GET_PUBLIC_REALISASIRENAKSI_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PUBLIC_REALISASIRENAKSI_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_realisasirenaksi_opd: actions.payload.data
            }
        case types.GET_PUBLIC_REALISASIRENAKSI_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
                data_realisasirenaksi_opd: {}
            }
        
        default:
            return state
    }
}