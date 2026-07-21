import * as types from "./types"

const getListDatamasterOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_DATAMASTEROPD_START })

    const response = await Api.getList_dmOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_DATAMASTEROPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_DATAMASTEROPD_FAILED, payload: response.error })
    }
    return response
}

const createDatamasterOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_DATAMASTEROPD_START })

    const response = await Api.create_dmOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_DATAMASTEROPD_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_DATAMASTEROPD_FAILED, payload: response.error })

    return response
}

const getDatamasterOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_DATAMASTEROPD_START })

    const response = await Api.get_dmOpd(id)
    if(response.error === null){
        dispatch({ type: types.GET_DATAMASTEROPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_DATAMASTEROPD_FAILED, payload: response.error })

    return response
}

const updateDatamasterOpd = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_DATAMASTEROPD_START })

    const response = await Api.update_dmOpd(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_DATAMASTEROPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_DATAMASTEROPD_FAILED, payload: response.error })

    return response
}

const deleteDatamasterOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_DATAMASTEROPD_START })

    const response = await Api.delete_dmOpd(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_DATAMASTEROPD_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_DATAMASTEROPD_FAILED, payload: response.error })

    return response
}

const getOptionsDatamasterOpd = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_OPTIONSOPD_START })
    const payload = { page: 1, per_page: 99999, search:"", is_active: true }

    const response = await Api.getList_dmOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_OPTIONSOPD_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_OPTIONSOPD_FAILED, payload: response.error })
    }
    return response
}

export {
    getListDatamasterOpd,
    createDatamasterOpd,
    getDatamasterOpd,
    updateDatamasterOpd,
    deleteDatamasterOpd,
    getOptionsDatamasterOpd
}