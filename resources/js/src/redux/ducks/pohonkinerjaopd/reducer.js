import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    formatedData: null
}

export default function pohonKinerjaOpdReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_POHONKINERJA_OPD_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_POHONKINERJA_OPD_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data
            }
        case types.GET_LIST_POHONKINERJA_OPD_FAILED:
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