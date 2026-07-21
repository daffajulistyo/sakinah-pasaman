import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: [],
}

export default function programAnggaranReducer (state = initialState, actions){
    switch(actions.type){
        case types.GET_PROGRAM_ANGGARAN_START:
            return {
                ...state,
                loading: true
            }
        case types.GET_PROGRAM_ANGGARAN_SUCCESS:
            return {
                ...state,
                loading: false,
                error: false,
                message: actions.payload.message,
                data: actions.payload.data.data_program
            }
        case types.GET_PROGRAM_ANGGARAN_FAILED:
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