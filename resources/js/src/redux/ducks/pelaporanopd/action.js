import * as types from "./types"

const getListPelaporanDataKinerjaOpd = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PELAPORAN_DATAKINERJAOPD_START })

    const response = await Api.getList_dataKinerjaOpd()
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PELAPORAN_DATAKINERJAOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PELAPORAN_DATAKINERJAOPD_FAILED, payload: response.error })
    }
    return response
}

const getListPelaporanCapaianKinerjaOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PELAPORAN_CAPAIANKINERJAOPD_START })

    const response = await Api.getList_capaianKinerjaOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PELAPORAN_CAPAIANKINERJAOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PELAPORAN_CAPAIANKINERJAOPD_FAILED, payload: response.error })
    }
    return response
}

export {
    getListPelaporanDataKinerjaOpd,
    getListPelaporanCapaianKinerjaOpd
}