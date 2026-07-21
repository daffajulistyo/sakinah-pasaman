import * as types from "./types"

const getListPohonKinerjaOpd = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_POHONKINERJA_OPD_START })

    const response = await Api.getList_pohonKinerjaOpd()
    if(response.error === null){
        
        dispatch({ type: types.GET_LIST_POHONKINERJA_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_POHONKINERJA_OPD_FAILED, payload: response.error })

    return response
}

export {
    getListPohonKinerjaOpd
}