import * as types from "./types"

const getListRenaksiPegawai = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RENAKSIPEGAWAI_START })

    const response = await Api.getList_renaksiPegawai(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RENAKSIPEGAWAI_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_RENAKSIPEGAWAI_FAILED, payload: response.error })
    }
    return response
}


const createRenaksiPegawai = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_RENAKSIPEGAWAI_START })

    const response = await Api.create_renaksiPegawai(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_RENAKSIPEGAWAI_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_RENAKSIPEGAWAI_FAILED, payload: response.error })

    return response
}


const getListRenaksiPegawaiLangkah = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RENAKSIPEGAWAI_LANGKAH_START })

    const response = await Api.getList_renaksiPegawaiLangkah(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RENAKSIPEGAWAI_LANGKAH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_RENAKSIPEGAWAI_LANGKAH_FAILED, payload: response.error })
    }
    return response
}


const createRenaksiPegawaiLangkah = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_RENAKSIPEGAWAI_LANGKAH_START })

    const response = await Api.create_renaksiPegawaiLangkah(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_RENAKSIPEGAWAI_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_RENAKSIPEGAWAI_LANGKAH_FAILED, payload: response.error })

    return response
}

const updateRenaksiPegawaiLangkah = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_RENAKSIPEGAWAI_LANGKAH_START })

    const response = await Api.update_renaksiPegawaiLangkah(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_RENAKSIPEGAWAI_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_RENAKSIPEGAWAI_LANGKAH_FAILED, payload: response.error })

    return response
}

const deleteRenaksiPegawaiLangkah = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_RENAKSIPEGAWAI_LANGKAH_START })

    const response = await Api.delete_renaksiPegawaiLangkah(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_RENAKSIPEGAWAI_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_RENAKSIPEGAWAI_LANGKAH_FAILED, payload: response.error })

    return response
}

export {
    getListRenaksiPegawai,
    createRenaksiPegawai,
    getListRenaksiPegawaiLangkah,
    createRenaksiPegawaiLangkah,
    updateRenaksiPegawaiLangkah,
    deleteRenaksiPegawaiLangkah
}