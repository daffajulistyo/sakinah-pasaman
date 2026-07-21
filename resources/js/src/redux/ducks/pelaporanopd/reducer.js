import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data_kinerja: [],
    capaian_kinerja: []
}

export default function pelaporanOpdReducer(state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_PELAPORAN_DATAKINERJAOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PELAPORAN_DATAKINERJAOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_kinerja: actions.payload.data
            }
        case types.GET_LIST_PELAPORAN_DATAKINERJAOPD_FAILED: 
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        case types.GET_LIST_PELAPORAN_CAPAIANKINERJAOPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PELAPORAN_CAPAIANKINERJAOPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                capaian_kinerja: actions.payload.data
            }
        case types.GET_LIST_PELAPORAN_CAPAIANKINERJAOPD_FAILED: 
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