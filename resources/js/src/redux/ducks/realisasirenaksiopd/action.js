import * as types from "./types"

const getListRealisasiRenaksiOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_REALISASI_RENAKSIOPD_START })

    const response = await Api.getList_realisasiRenaksiOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIOPD_FAILED, payload: response.error })
    }
    return response
}

const createRealisasiRenaksiOpd = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_REALISASI_RENAKSIOPD_START })

    const response = await Api.update_realisasiRenaksiOpd(id, payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_REALISASI_RENAKSIOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_REALISASI_RENAKSIOPD_FAILED, payload: response.error })

    return response
}

const getListRealisasiRenaksiOpdLangkah = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_REALISASI_RENAKSIOPD_LANGKAH_START })

    const response = await Api.getList_langkahRealisasiRenaksiOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIOPD_LANGKAH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIOPD_LANGKAH_FAILED, payload: response.error })
    }
    return response
}

const createRealisasiRenaksiOpdLangkah = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_REALISASI_RENAKSIOPD_LANGKAH_START })

    const response = await Api.update_langkahRealisasiRenaksiOpd(id, payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_REALISASI_RENAKSIOPD_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_REALISASI_RENAKSIOPD_LANGKAH_FAILED, payload: response.error })

    return response
}

export {
    getListRealisasiRenaksiOpd,
    createRealisasiRenaksiOpd,
    getListRealisasiRenaksiOpdLangkah,
    createRealisasiRenaksiOpdLangkah
}