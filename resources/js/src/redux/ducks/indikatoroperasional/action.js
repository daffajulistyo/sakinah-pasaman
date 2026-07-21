import * as types from "./types"

const getRefIndikatorOperasionalOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_REF_INDIKATOROPERASIONALOPD_START })

    const response = await Api.getRef_indikatorOperasionalOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_REF_INDIKATOROPERASIONALOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_REF_INDIKATOROPERASIONALOPD_FAILED, payload: response.error })
    }
    return response
}

const getListIndikatorOperasionalOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_INDIKATOROPERASIONALOPD_START })

    const response = await Api.getList_indikatorOperasionalOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_INDIKATOROPERASIONALOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_INDIKATOROPERASIONALOPD_FAILED, payload: response.error })
    }
    return response
}

const createIndikatorOperasionalOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_INDIKATOROPERASIONALOPD_START })

    const response = await Api.create_indikatorOperasionalOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_INDIKATOROPERASIONALOPD_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_INDIKATOROPERASIONALOPD_FAILED, payload: response.error })

    return response
}

const getIndikatorOperasionalOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_INDIKATOROPERASIONALOPD_START })

    const response = await Api.get_indikatorOperasionalOpd(id)
    if(response.error === null){
        dispatch({ type: types.GET_INDIKATOROPERASIONALOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_INDIKATOROPERASIONALOPD_FAILED, payload: response.error })

    return response
}


const deleteIndikatorOperasionalOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_INDIKATOROPERASIONALOPD_START })

    const response = await Api.delete_indikatorOperasionalOpd(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_INDIKATOROPERASIONALOPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_INDIKATOROPERASIONALOPD_FAILED, payload: response.error })

    return response
}

export {
    getRefIndikatorOperasionalOpd,
    getListIndikatorOperasionalOpd, createIndikatorOperasionalOpd, getIndikatorOperasionalOpd, deleteIndikatorOperasionalOpd
}