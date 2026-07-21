import * as types from "./types"

const getListCascadingOpd = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_CASCADINGOPD_START })

    const response = await Api.getList_cascadingOpd()
    if(response.error === null){
        dispatch({ type: types.GET_LIST_CASCADINGOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_CASCADINGOPD_FAILED, payload: response.error })
    }
    return response
}

const createCascadingOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_CASCADINGOPD_START })

    const response = await Api.create_cascadingOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_CASCADINGOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.CREATE_CASCADINGOPD_FAILED, payload: response.error })
    }

    return response
}

export {
    getListCascadingOpd,
    createCascadingOpd
}