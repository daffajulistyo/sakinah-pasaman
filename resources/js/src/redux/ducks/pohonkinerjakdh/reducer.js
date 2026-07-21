import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    formatedData: null
}

export default function pohonKinerjaKdhReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_LIST_POHONKINERJA_KDH_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_LIST_POHONKINERJA_KDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.actions
            }
        case types.GET_LIST_POHONKINERJA_KDH_FAILED:
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