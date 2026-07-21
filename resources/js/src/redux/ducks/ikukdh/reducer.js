import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: false,
    data: null
}


export default function ikuKdhReducer (state = initialState, actions){
    switch(actions.type) {
        case types.UPDATE_IKU_KDH_START:
            return {
                ...state,
                loading: true
            }
        case types.UPDATE_IKU_KDH_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message
            }
        case types.UPDATE_IKU_KDH_FAILED:
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