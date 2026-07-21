import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    selected_opd: null,
    data_pohonkinerja: null,
    data_cascading: [],
    data_renstra: [],
    data_iku: [],
    data_renja: [],
    data_pk: [],
    data_rencanaaksi: [],
    data_realisasirenaksi: [],
    data_kinerja: [],
    data_capaian: []
}

export default function monitoringReducer (state = initialState, actions){
    switch(actions.type){

        case types.SET_SELECTED_OPD:
            return {
                ...state,
                selected_opd: actions.payload
            }
        case types.CLEAR_SELECTED_OPD:
            return {
                ...state,
                selected_opd: null
            }
        case types.GET_LIST_MONITORING_POHONKINERJA_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_MONITORING_POHONKINERJA_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_pohonkinerja: actions.payload.data
            }
        case types.GET_LIST_MONITORING_POHONKINERJA_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        case types.GET_LIST_MONITORING_CASCADING_OPD_START:
            return {
                ...state,
                loading: true,
                data_cascading: []
            }
        case types.GET_LIST_MONITORING_CASCADING_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_cascading: actions.payload.data
            }
        case types.GET_LIST_MONITORING_CASCADING_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
            }

        
        case types.GET_LIST_MONITORING_RENSTRA_OPD_START:
            return {
                ...state,
                loading: true,
                data_renstra: []
            }
        case types.GET_LIST_MONITORING_RENSTRA_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_renstra: actions.payload.data
            }
        case types.GET_LIST_MONITORING_RENSTRA_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
            }

        case types.GET_LIST_MONITORING_IKU_OPD_START:
            return {
                ...state,
                loading: true,
                data_iku: []
            }
        case types.GET_LIST_MONITORING_IKU_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_iku: actions.payload.data
            }
        case types.GET_LIST_MONITORING_IKU_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
            }

        case types.GET_LIST_MONITORING_RENJA_OPD_START:
            return {
                ...state,
                loading: true,
                data_renja: []
            }
        case types.GET_LIST_MONITORING_RENJA_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_renja: actions.payload.data
            }
        case types.GET_LIST_MONITORING_RENJA_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
            }

        case types.GET_LIST_MONITORING_PK_OPD_START:
            return {
                ...state,
                loading: true,
                data_pk: []
            }
        case types.GET_LIST_MONITORING_PK_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_pk: actions.payload.data
            }
        case types.GET_LIST_MONITORING_PK_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
            }

        case types.GET_LIST_MONITORING_RENCANAAKSI_OPD_START:
            return {
                ...state,
                loading: true,
                data_rencanaaksi: []
            }
        case types.GET_LIST_MONITORING_RENCANAAKSI_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_rencanaaksi: actions.payload.data
            }
        case types.GET_LIST_MONITORING_RENCANAAKSI_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
            }

        case types.GET_LIST_MONITORING_REALISASIRENAKSI_OPD_START:
            return {
                ...state,
                loading: true,
                data_realisasirenaksi: []
            }
        case types.GET_LIST_MONITORING_REALISASIRENAKSI_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_realisasirenaksi: actions.payload.data
            }
        case types.GET_LIST_MONITORING_REALISASIRENAKSI_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
            }

        case types.GET_LIST_MONITORING_DATAKINERJA_OPD_START:
            return {
                ...state,
                loading: true,
                data_kinerja: []
            }
        case types.GET_LIST_MONITORING_DATAKINERJA_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_kinerja: actions.payload.data
            }
        case types.GET_LIST_MONITORING_DATAKINERJA_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
            }

        case types.GET_LIST_MONITORING_CAPAIANKINERJA_OPD_START:
            return {
                ...state,
                loading: true,
                data_capaian: []
            }
        case types.GET_LIST_MONITORING_CAPAIANKINERJA_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_capaian: actions.payload.data
            }
        case types.GET_LIST_MONITORING_CAPAIANKINERJA_OPD_FAILED:
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message,
            }

        default:
            return state
    }
}