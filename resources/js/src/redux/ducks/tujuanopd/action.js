import * as types from "./types"

const getListTujuanOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_TUJUANOPD_START })

    const response = await Api.getList_tujuanOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_TUJUANOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_TUJUANOPD_FAILED, payload: response.error })
    }
    return response
}

const createTujuanOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_TUJUANOPD_START })

    const response = await Api.create_tujuanOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_TUJUANOPD_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_TUJUANOPD_FAILED, payload: response.error })

    return response
}

const getTujuanOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_TUJUANOPD_START })

    const response = await Api.get_tujuanOpd(id)
    if(response.error === null){
        dispatch({ type: types.GET_TUJUANOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_TUJUANOPD_FAILED, payload: response.error })

    return response
}

const updateTujuanOpd = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_TUJUANOPD_START })

    const response = await Api.update_tujuanOpd(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_TUJUANOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_TUJUANOPD_FAILED, payload: response.error })

    return response
}

const deleteTujuanOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_TUJUANOPD_START })

    const response = await Api.delete_tujuanOpd(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_TUJUANOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_TUJUANOPD_FAILED, payload: response.error })

    return response
}

const getListSasaranDiampuOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_SASARANDIAMPU_OPD_START })

    const response = await Api.getList_SasaranDiampuOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_SASARANDIAMPU_OPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_LIST_SASARANDIAMPU_OPD_FAILED, payload: response.error })

    return response
}

export {
    getListTujuanOpd,
    createTujuanOpd,
    updateTujuanOpd,
    getTujuanOpd,
    deleteTujuanOpd,
    getListSasaranDiampuOpd
}