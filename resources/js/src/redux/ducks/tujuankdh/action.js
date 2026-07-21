import * as types from "./types"

const getListTujuanKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_TUJUANKDH_START })

    const response = await Api.getList_tujuanKdh(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_TUJUANKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_TUJUANKDH_FAILED, payload: response.error })
    }
    return response
}

const createTujuanKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_TUJUANKDH_START })

    const response = await Api.create_tujuanKdh(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_TUJUANKDH_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_TUJUANKDH_FAILED, payload: response.error })

    return response
}

const getTujuanKdh = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_TUJUANKDH_START })

    const response = await Api.get_tujuanKdh(id)
    if(response.error === null){
        dispatch({ type: types.GET_TUJUANKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_TUJUANKDH_FAILED, payload: response.error })

    return response
}

const updateTujuanKdh = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_TUJUANKDH_START })

    const response = await Api.update_tujuanKdh(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_TUJUANKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_TUJUANKDH_FAILED, payload: response.error })

    return response
}

const deleteTujuanKdh = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_TUJUANKDH_START })

    const response = await Api.delete_tujuanKdh(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_TUJUANKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_TUJUANKDH_FAILED, payload: response.error })

    return response
}

export {
    getListTujuanKdh, createTujuanKdh, getTujuanKdh, updateTujuanKdh, deleteTujuanKdh
}