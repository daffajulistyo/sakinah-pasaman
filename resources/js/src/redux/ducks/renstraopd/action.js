import * as types from "./types"

const getListRenstraOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RENSTRA_OPD_START })
    
    const response = await Api.getListRenstraOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RENSTRA_OPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_RENSTRA_OPD_FAILED, payload: response.error })
    }
    return response
}

const createTargetRenstraOpd = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_TARGET_RENSTRA_OPD_START })

    const response = await Api.createTargetRenstraOpd(id, payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_TARGET_RENSTRA_OPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.CREATE_TARGET_RENSTRA_OPD_FAILED, payload: response.error })
    }
    return response
}

export {
    getListRenstraOpd, 
    createTargetRenstraOpd
}