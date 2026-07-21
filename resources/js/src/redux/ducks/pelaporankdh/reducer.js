import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data_kinerja: [],
    capaian_kinerja: []
}

export default function pelaporanKdhReducer(state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_PELAPORAN_DATAKINERJAKDH_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PELAPORAN_DATAKINERJAKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data_kinerja: actions.payload.data
            }
        case types.GET_LIST_PELAPORAN_DATAKINERJAKDH_FAILED: 
            return {
                ...state,
                loading: false,
                error: true,
                message: actions.payload.message
            }
        case types.GET_LIST_PELAPORAN_CAPAIANKINERJAKDH_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_PELAPORAN_CAPAIANKINERJAKDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                capaian_kinerja: actions.payload.data
            }
        case types.GET_LIST_PELAPORAN_CAPAIANKINERJAKDH_FAILED: 
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