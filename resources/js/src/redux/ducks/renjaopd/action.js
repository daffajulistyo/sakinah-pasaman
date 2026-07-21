import * as types from "./types"

const getListRenjaOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RENJAOPD_START })

    const response = await Api.getList_renjaOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RENJAOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_RENJAOPD_FAILED, payload: response.error })
    }
    return response
}


const createRenjaOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_RENJAOPD_START })

    const response = await Api.create_renjaOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_RENJAOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_RENJAOPD_FAILED, payload: response.error })

    return response
}



const createProgramRenjaOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PROGRAM_RENJAOPD_START })

    const response = await Api.create_renjaOpdProgram(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_PROGRAM_RENJAOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.CREATE_PROGRAM_RENJAOPD_FAILED, payload: response.error })
    }
    return response
}

const getListRenjaOpdProgram = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PROGRAM_RENJAOPD_START })

    const response = await Api.getList_renjaOpdProgram(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PROGRAM_RENJAOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PROGRAM_RENJAOPD_FAILED, payload: response.error })
    }
    return response
}

export {
    getListRenjaOpd,
    createRenjaOpd,
    createProgramRenjaOpd,
    getListRenjaOpdProgram
}