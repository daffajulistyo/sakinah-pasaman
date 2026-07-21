import * as types from "./types"

const getListRpjmdKdh = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RPJMD_START })

    const response = await Api.getListRpjmdKdh()
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RPJMD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_RPJMD_FAILED, payload: response.error })
    }
    return response
}

const createTargetRpjmdKdh = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_TARGET_RPJMD_KDH_START })

    const response = await Api.createTargetRpjmdKdh(id, payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_TARGET_RPJMD_KDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.CREATE_TARGET_RPJMD_KDH_FAILED, payload: response.error })
    }
    return response
}

export {
    getListRpjmdKdh, 
    createTargetRpjmdKdh
}