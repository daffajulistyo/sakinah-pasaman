import * as types from "./types"

const getListRenaksiKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RENAKSIKDH_START })

    const response = await Api.getList_renaksiKdh(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RENAKSIKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_RENAKSIKDH_FAILED, payload: response.error })
    }
    return response
}


const createRenaksiKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_RENAKSIKDH_START })

    const response = await Api.create_renaksiKdh(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_RENAKSIKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_RENAKSIKDH_FAILED, payload: response.error })

    return response
}


const getListRenaksiKdhLangkah = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RENAKSIKDH_LANGKAH_START })

    const response = await Api.getList_renaksiKdhLangkah(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RENAKSIKDH_LANGKAH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_RENAKSIKDH_LANGKAH_FAILED, payload: response.error })
    }
    return response
}


const createRenaksiKdhLangkah = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_RENAKSIKDH_LANGKAH_START })

    const response = await Api.create_renaksiKdhLangkah(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_RENAKSIKDH_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_RENAKSIKDH_LANGKAH_FAILED, payload: response.error })

    return response
}

const updateRenaksiKdhLangkah = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_RENAKSIKDH_LANGKAH_START })

    const response = await Api.update_renaksiKdhLangkah(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_RENAKSIKDH_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_RENAKSIKDH_LANGKAH_FAILED, payload: response.error })

    return response
}

const deleteRenaksiKdhLangkah = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_RENAKSIKDH_LANGKAH_START })

    const response = await Api.delete_renaksiKdhLangkah(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_RENAKSIKDH_LANGKAH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_RENAKSIKDH_LANGKAH_FAILED, payload: response.error })

    return response
}

export {
    getListRenaksiKdh,
    createRenaksiKdh,
    getListRenaksiKdhLangkah,
    createRenaksiKdhLangkah,
    updateRenaksiKdhLangkah,
    deleteRenaksiKdhLangkah
}