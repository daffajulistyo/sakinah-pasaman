import * as types from "./types"

const getListVisiKDH = (payload) => async (dispatch, getState, Api) => {
    dispatch({
        type: types.GET_LIST_VISIKDH_START
    })

    const response = await Api.getList_visiKdh(payload)
    if(response.error === null){
        dispatch({
            type: types.GET_LIST_VISIKDH_SUCCESS,
            payload: response.data
        })
    }
    else{
        dispatch({
            type: types.GET_LIST_VISIKDH_FAILED,
            payload: response.error
        })
    }
    return response
}

const createVisiKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({
        type: types.CREATE_VISIKDH_START
    })
    const response = await Api.create_visiKdh(payload)
    if(response.error === null){
        dispatch({
            type: types.CREATE_VISIKDH_SUCCESS,
            payload: response.data
        })
    }
    else{
        dispatch({
            type: types.CREATE_VISIKDH_FAILED,
            payload: response.error
        })
    }
    return response
}

const getVisiKdh = (id) => async (dispatch, getState, Api) => {
    dispatch({
        type: types.GET_VISIKDH_START
    })
    const response = await Api.get_visiKdh(id)
    if(response.error === null){
        dispatch({
            type: types.GET_VISIKDH_SUCCESS,
            payload: response.data
        })
    }
    else dispatch({ type: types.GET_VISIKDH_FAILED, payload: response.error })
    return response
}

const updateVisiKdh = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_VISIKDH_START })
    const response = await Api.update_visiKdh(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_VISIKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_VISIKDH_FAILED, payload: response.error })
    return response
}

const deleteVisiKdh = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_VISIKDH_START })
    const response = await Api.delete_visiKdh(id)    
    if(response.error === null){
        dispatch({ type: types.DELETE_VISIKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_VISIKDH_FAILED, payload: response.error })
    return response
}

export {
    getListVisiKDH, createVisiKdh, getVisiKdh, updateVisiKdh, deleteVisiKdh
}