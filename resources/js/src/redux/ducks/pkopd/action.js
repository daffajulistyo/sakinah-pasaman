import * as types from "./types"

const getListPkOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PKOPD_START })

    const response = await Api.getList_pkOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PKOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PKOPD_FAILED, payload: response.error })
    }
    return response
}


const createPkOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PKOPD_START })

    const response = await Api.create_pkOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_PKOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_PKOPD_FAILED, payload: response.error })

    return response
}



const createProgramPkOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PROGRAM_PKOPD_START })

    const response = await Api.create_pkOpdProgram(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_PROGRAM_PKOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.CREATE_PROGRAM_PKOPD_FAILED, payload: response.error })
    }
    return response
}

const getListPkOpdProgram = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PROGRAM_PKOPD_START })

    const response = await Api.getList_pkOpdProgram(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PROGRAM_PKOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PROGRAM_PKOPD_FAILED, payload: response.error })
    }
    return response
}


export {
    getListPkOpd,
    createPkOpd,
    createProgramPkOpd,
    getListPkOpdProgram
}