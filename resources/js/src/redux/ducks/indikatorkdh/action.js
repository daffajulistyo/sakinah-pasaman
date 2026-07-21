import * as types from "./types"

const getListIndikatorKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_INDIKATORKDH_START })

    const response = await Api.getList_indikatorKdh(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_INDIKATORKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_INDIKATORKDH_FAILED, payload: response.error })
    }
    return response
}

const createIndikatorKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_INDIKATORKDH_START })

    const response = await Api.create_indikatorKdh(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_INDIKATORKDH_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_INDIKATORKDH_FAILED, payload: response.error })

    return response
}

const getIndikatorKdh = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_INDIKATORKDH_START })

    const response = await Api.get_indikatorKdh(id)
    if(response.error === null){
        dispatch({ type: types.GET_INDIKATORKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_INDIKATORKDH_FAILED, payload: response.error })

    return response
}

const updateIndikatorKdh = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_INDIKATORKDH_START })

    const response = await Api.update_indikatorKdh(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_INDIKATORKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_INDIKATORKDH_FAILED, payload: response.error })

    return response
}

const deleteIndikatorKdh = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_INDIKATORKDH_START })

    const response = await Api.delete_indikatorKdh(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_INDIKATORKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_INDIKATORKDH_FAILED, payload: response.error })

    return response
}

const uploadFormulaPerhitunganKdh = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPLOAD_FORMULA_PERHITUNGAN_KDH_START })

    const response = await Api.upload_formulaPerhitunganKdh(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPLOAD_FORMULA_PERHITUNGAN_KDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPLOAD_FORMULA_PERHITUNGAN_KDH_FAILED, payload: response.error })

    return response
}

export {
    getListIndikatorKdh, createIndikatorKdh, getIndikatorKdh, updateIndikatorKdh, deleteIndikatorKdh, uploadFormulaPerhitunganKdh
}