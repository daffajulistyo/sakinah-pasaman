import * as types from "./types"

const getListSasaranOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_SASARANOPD_START })

    const response = await Api.getList_sasaranOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_SASARANOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_SASARANOPD_FAILED, payload: response.error })
    }
    return response
}

const createSasaranOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_SASARANOPD_START })

    const response = await Api.create_sasaranOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_SASARANOPD_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_SASARANOPD_FAILED, payload: response.error })

    return response
}

const getSasaranOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_SASARANOPD_START })

    const response = await Api.get_sasaranOpd(id)
    if(response.error === null){
        dispatch({ type: types.GET_SASARANOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_SASARANOPD_FAILED, payload: response.error })

    return response
}

const updateSasaranOpd = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_SASARANOPD_START })

    const response = await Api.update_sasaranOpd(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_SASARANOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_SASARANOPD_FAILED, payload: response.error })

    return response
}

const deleteSasaranOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_SASARANOPD_START })

    const response = await Api.delete_sasaranOpd(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_SASARANOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_SASARANOPD_FAILED, payload: response.error })

    return response
}

export {
    getListSasaranOpd, createSasaranOpd, getSasaranOpd, updateSasaranOpd, deleteSasaranOpd
}