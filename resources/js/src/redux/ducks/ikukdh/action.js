import * as types from "./types"

const updateIkuKdh = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_IKU_KDH_START })
    const response = await Api.updateIkuKdah(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_IKU_KDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_IKU_KDH_FAILED, payload: response.error })
    return response
}

export {
    updateIkuKdh
}