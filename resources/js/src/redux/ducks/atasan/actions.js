import * as types from "./types"

const getListAtasanPegawai = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_ATASAN_START })

    const response = await Api.getList_atasanPegawai()
    if(response.error === null){
        dispatch({ type: types.GET_LIST_ATASAN_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_ATASAN_FAILED, payload: response.error })
    }
    return response
}

const createAtasanPegawai = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_ATASAN_START })

    const response = await Api.create_atasanPegawai(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_ATASAN_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.CREATE_ATASAN_FAILED, payload: response.error })
    }
    return response
}

export {
    getListAtasanPegawai,
    createAtasanPegawai
}