import * as types from "./types"

const getListSasaranKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_SASARANKDH_START })

    const response = await Api.getList_sasaranKdh(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_SASARANKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_SASARANKDH_FAILED, payload: response.error })
    }
    return response
}

const createSasaranKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_SASARANKDH_START })

    const response = await Api.create_sasaranKdh(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_SASARANKDH_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_SASARANKDH_FAILED, payload: response.error })

    return response
}

const getSasaranKdh = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_SASARANKDH_START })

    const response = await Api.get_sasaranKdh(id)
    if(response.error === null){
        dispatch({ type: types.GET_SASARANKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_SASARANKDH_FAILED, payload: response.error })

    return response
}

const updateSasaranKdh = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_SASARANKDH_START })

    const response = await Api.update_sasaranKdh(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_SASARANKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_SASARANKDH_FAILED, payload: response.error })

    return response
}

const deleteSasaranKdh = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_SASARANKDH_START })

    const response = await Api.delete_sasaranKdh(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_SASARANKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_SASARANKDH_FAILED, payload: response.error })

    return response
}

export {
    getListSasaranKdh, createSasaranKdh, getSasaranKdh, updateSasaranKdh, deleteSasaranKdh
}