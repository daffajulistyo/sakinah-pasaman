import * as types from "./types"

const getListRealisasiRenaksiPegawai = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_REALISASI_RENAKSIPEGAWAI_START })

    const response = await Api.getList_realisasiRenaksiPegawai(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIPEGAWAI_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIPEGAWAI_FAILED, payload: response.error })
    }
    return response
}

const createRealisasiRenaksiPegawai = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_REALISASI_RENAKSIPEGAWAI_START })

    const response = await Api.update_realisasiRenaksiPegawai(id, payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_REALISASI_RENAKSIPEGAWAI_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_REALISASI_RENAKSIPEGAWAI_FAILED, payload: response.error })

    return response
}

const getListRealisasiRenaksiPegawaiLangkah = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_REALISASI_RENAKSIPEGAWAI_LANGKAH_START })

    const response = await Api.getList_langkahRealisasiRenaksiPegawai(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIPEGAWAI_LANGKAH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIPEGAWAI_LANGKAH_FAILED, payload: response.error })
    }
    return response
}

const createRealisasiRenaksiPegawaiLangkah = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_REALISASI_RENAKSIPEGAWAI_LANGKAH_START })

    const response = await Api.update_langkahRealisasiRenaksiPegawai(id, payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_REALISASI_RENAKSIPEGAWAI_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_REALISASI_RENAKSIPEGAWAI_LANGKAH_FAILED, payload: response.error })

    return response
}

export {
    getListRealisasiRenaksiPegawai,
    createRealisasiRenaksiPegawai,
    getListRealisasiRenaksiPegawaiLangkah,
    createRealisasiRenaksiPegawaiLangkah
}