import * as types from "./types"

const getListMisiKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MISIKDH_START })

    const response = await Api.getList_misiKdh(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MISIKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_MISIKDH_FAILED, payload: response.error })
    }
    return response
}

const createMisiKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_MISIKDH_START })

    const response = await Api.create_misiKdh(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_MISIKDH_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_MISIKDH_FAILED, payload: response.error })

    return response
}

const getMisiKdh = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_MISIKDH_START })

    const response = await Api.get_misiKdh(id)
    if(response.error === null){
        dispatch({ type: types.GET_MISIKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_MISIKDH_FAILED, payload: response.error })

    return response
}

const updateMisiKdh = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_MISIKDH_START })

    const response = await Api.update_misiKdh(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_MISIKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_MISIKDH_FAILED, payload: response.error })

    return response
}

const deleteMisiKdh = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_MISIKDH_START })

    const response = await Api.delete_misiKdh(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_MISIKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_MISIKDH_FAILED, payload: response.error })

    return response
}

export {
    getListMisiKdh, createMisiKdh, getMisiKdh, updateMisiKdh, deleteMisiKdh
}