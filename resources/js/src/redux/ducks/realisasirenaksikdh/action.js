import * as types from "./types"

const getListRealisasiRenaksiKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_REALISASI_RENAKSIKDH_START })

    const response = await Api.getList_realisasiRenaksiKdh(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIKDH_FAILED, payload: response.error })
    }
    return response
}

const createRealisasiRenaksiKdh = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_REALISASI_RENAKSIKDH_START })

    const response = await Api.update_realisasiRenaksiKdh(id, payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_REALISASI_RENAKSIKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_REALISASI_RENAKSIKDH_FAILED, payload: response.error })

    return response
}

const getListRealisasiRenaksiKdhLangkah = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_REALISASI_RENAKSIKDH_LANGKAH_START })

    const response = await Api.getList_langkahRealisasiRenaksiKdh(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIKDH_LANGKAH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_REALISASI_RENAKSIKDH_LANGKAH_FAILED, payload: response.error })
    }
    return response
}

const createRealisasiRenaksiKdhLangkah = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_REALISASI_RENAKSIKDH_LANGKAH_START })

    const response = await Api.update_langkahRealisasiRenaksiKdh(id, payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_REALISASI_RENAKSIKDH_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_REALISASI_RENAKSIKDH_LANGKAH_FAILED, payload: response.error })

    return response
}

export {
    getListRealisasiRenaksiKdh,
    createRealisasiRenaksiKdh,
    getListRealisasiRenaksiKdhLangkah,
    createRealisasiRenaksiKdhLangkah
}