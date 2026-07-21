import * as types from "./types"

const getListRenaksiOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RENAKSIOPD_START })

    const response = await Api.getList_renaksiOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RENAKSIOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_RENAKSIOPD_FAILED, payload: response.error })
    }
    return response
}


const createRenaksiOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_RENAKSIOPD_START })

    const response = await Api.create_renaksiOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_RENAKSIOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_RENAKSIOPD_FAILED, payload: response.error })

    return response
}


const getListRenaksiOpdLangkah = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RENAKSIOPD_LANGKAH_START })

    const response = await Api.getList_renaksiOpdLangkah(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RENAKSIOPD_LANGKAH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_RENAKSIOPD_LANGKAH_FAILED, payload: response.error })
    }
    return response
}


const createRenaksiOpdLangkah = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_RENAKSIOPD_LANGKAH_START })

    const response = await Api.create_renaksiOpdLangkah(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_RENAKSIOPD_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_RENAKSIOPD_LANGKAH_FAILED, payload: response.error })

    return response
}

const updateRenaksiOpdLangkah = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_RENAKSIOPD_LANGKAH_START })

    const response = await Api.update_renaksiOpdLangkah(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_RENAKSIOPD_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_RENAKSIOPD_LANGKAH_FAILED, payload: response.error })

    return response
}

const deleteRenaksiOpdLangkah = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_RENAKSIOPD_LANGKAH_START })

    const response = await Api.delete_renaksiOpdLangkah(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_RENAKSIOPD_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_RENAKSIOPD_LANGKAH_FAILED, payload: response.error })

    return response
}

export {
    getListRenaksiOpd,
    createRenaksiOpd,
    getListRenaksiOpdLangkah,
    createRenaksiOpdLangkah,
    updateRenaksiOpdLangkah,
    deleteRenaksiOpdLangkah
}