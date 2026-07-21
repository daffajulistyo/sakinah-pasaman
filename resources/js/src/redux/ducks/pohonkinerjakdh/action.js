import * as types from "./types"

const getListPohonKinerjaKdh = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_POHONKINERJA_KDH_START })

    const response = await Api.getList_pohonKinerja()
    if(response.error === null){
        
        dispatch({ type: types.GET_LIST_POHONKINERJA_KDH_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_POHONKINERJA_KDH_FAILED, payload: response.error })

    return response
}

export {
    getListPohonKinerjaKdh
}