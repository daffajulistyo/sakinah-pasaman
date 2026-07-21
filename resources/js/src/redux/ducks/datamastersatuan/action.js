import * as types from "./types"

const getListDatamasterSatuan = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_DATAMASTERSATUAN_START })

    const response = await Api.getList_dmSatuan(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_DATAMASTERSATUAN_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_DATAMASTERSATUAN_FAILED, payload: response.error })
    }
    return response
}

const createDatamasterSatuan = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_DATAMASTERSATUAN_START })

    const response = await Api.create_dmSatuan(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_DATAMASTERSATUAN_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_DATAMASTERSATUAN_FAILED, payload: response.error })

    return response
}

const getDatamasterSatuan = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_DATAMASTERSATUAN_START })

    const response = await Api.get_dmSatuan(id)
    if(response.error === null){
        dispatch({ type: types.GET_DATAMASTERSATUAN_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_DATAMASTERSATUAN_FAILED, payload: response.error })

    return response
}

const updateDatamasterSatuan = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_DATAMASTERSATUAN_START })

    const response = await Api.update_dmSatuan(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_DATAMASTERSATUAN_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_DATAMASTERSATUAN_FAILED, payload: response.error })

    return response
}

const deleteDatamasterSatuan = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_DATAMASTERSATUAN_START })

    const response = await Api.delete_dmSatuan(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_DATAMASTERSATUAN_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_DATAMASTERSATUAN_FAILED, payload: response.error })

    return response
}


const getOptionsDatamasterSatuan = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_OPTIONSSATUAN_START })
    const payload = { page: 1, per_page: 99999, search:"", is_active: true }

    const response = await Api.getList_dmSatuan(payload)
    if(response.error === null){
        dispatch({ type: types.GET_OPTIONSSATUAN_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_OPTIONSSATUAN_FAILED, payload: response.error })
    }
    return response
}

export {
    getListDatamasterSatuan,
    createDatamasterSatuan,
    getDatamasterSatuan,
    updateDatamasterSatuan,
    deleteDatamasterSatuan,
    getOptionsDatamasterSatuan
}