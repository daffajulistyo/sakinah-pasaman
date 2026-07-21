import * as types from "./types"

const getListIndikatorOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_INDIKATOROPD_START })

    const response = await Api.getList_indikatorOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_INDIKATOROPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_INDIKATOROPD_FAILED, payload: response.error })
    }
    return response
}

const createIndikatorOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_INDIKATOROPD_START })

    const response = await Api.create_indikatorOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_INDIKATOROPD_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_INDIKATOROPD_FAILED, payload: response.error })

    return response
}

const getIndikatorOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_INDIKATOROPD_START })

    const response = await Api.get_indikatorOpd(id)
    if(response.error === null){
        dispatch({ type: types.GET_INDIKATOROPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_INDIKATOROPD_FAILED, payload: response.error })

    return response
}

const updateIndikatorOpd = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_INDIKATOROPD_START })

    const response = await Api.update_indikatorOpd(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_INDIKATOROPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_INDIKATOROPD_FAILED, payload: response.error })

    return response
}

const deleteIndikatorOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_INDIKATOROPD_START })

    const response = await Api.delete_indikatorOpd(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_INDIKATOROPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_INDIKATOROPD_FAILED, payload: response.error })

    return response
}

export {
    getListIndikatorOpd, createIndikatorOpd, getIndikatorOpd, updateIndikatorOpd, deleteIndikatorOpd
}