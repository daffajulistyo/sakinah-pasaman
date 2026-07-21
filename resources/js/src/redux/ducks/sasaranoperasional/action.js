import * as types from "./types"

const getRefSasaranOperasionalOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_REF_SASARANOPERASIONALOPD_START })

    const response = await Api.getRef_sasaranOperasionalOpd()
    if(response.error === null){
        dispatch({ type: types.GET_REF_SASARANOPERASIONALOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_REF_SASARANOPERASIONALOPD_FAILED, payload: response.error })
    }
    return response
}

const getListSasaranOperasionalOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_SASARANOPERASIONALOPD_START })

    const response = await Api.getList_sasaranOperasionalOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_SASARANOPERASIONALOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_SASARANOPERASIONALOPD_FAILED, payload: response.error })
    }
    return response
}

const createSasaranOperasionalOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_SASARANOPERASIONALOPD_START })

    const response = await Api.create_sasaranOperasionalOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_SASARANOPERASIONALOPD_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_SASARANOPERASIONALOPD_FAILED, payload: response.error })

    return response
}

const getSasaranOperasionalOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_SASARANOPERASIONALOPD_START })

    const response = await Api.get_sasaranOperasionalOpd(id)
    if(response.error === null){
        dispatch({ type: types.GET_SASARANOPERASIONALOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_SASARANOPERASIONALOPD_FAILED, payload: response.error })

    return response
}


const deleteSasaranOperasionalOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_SASARANOPERASIONALOPD_START })

    const response = await Api.delete_sasaranOperasionalOpd(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_SASARANOPERASIONALOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_SASARANOPERASIONALOPD_FAILED, payload: response.error })

    return response
}

export {
    getRefSasaranOperasionalOpd,
    getListSasaranOperasionalOpd, createSasaranOperasionalOpd, getSasaranOperasionalOpd, deleteSasaranOperasionalOpd
}