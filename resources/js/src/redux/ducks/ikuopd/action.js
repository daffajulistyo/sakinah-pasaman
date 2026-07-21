import * as types from "./types"

const updateIkuOpd = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_IKU_OPD_START })
    const response = await Api.updateIkuOpd(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_IKU_OPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_IKU_OPD_FAILED, payload: response.error })
    return response
}

const getListIkuOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_IKU_OPD_START })

    const response = await Api.getList_ikuOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_IKU_OPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_IKU_OPD_FAILED, payload: response.error })
    }
    return response
}

export {
    updateIkuOpd,
    getListIkuOpd
}