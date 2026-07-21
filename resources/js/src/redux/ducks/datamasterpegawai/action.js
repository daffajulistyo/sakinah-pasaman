import * as types from "./types"

const getListPegawai = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PEGAWAI_START })
    const response = await Api.getListPegawai(payload)
    if (response.error === null) {
        dispatch({ type: types.GET_LIST_PEGAWAI_SUCCESS, payload: response.data })
    } else {
        dispatch({ type: types.GET_LIST_PEGAWAI_FAILED, payload: response.error })
    }
    return response
}

const createPegawai = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PEGAWAI_START })
    const response = await Api.createPegawai(payload)
    if (response.error === null) {
        dispatch({ type: types.CREATE_PEGAWAI_SUCCESS, payload: response.data })
    } else {
        dispatch({ type: types.CREATE_PEGAWAI_FAILED, payload: response.error })
    }
    return response
}

const getPegawai = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PEGAWAI_START })
    const response = await Api.getPegawai(id)
    if (response.error === null) {
        dispatch({ type: types.GET_PEGAWAI_SUCCESS, payload: response.data })
    } else {
        dispatch({ type: types.GET_PEGAWAI_FAILED, payload: response.error })
    }
    return response
}

const updatePegawai = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_PEGAWAI_START })
    const response = await Api.updatePegawai(id, payload)
    if (response.error === null) {
        dispatch({ type: types.UPDATE_PEGAWAI_SUCCESS, payload: response.data })
    } else {
        dispatch({ type: types.UPDATE_PEGAWAI_FAILED, payload: response.error })
    }
    return response
}

const deletePegawai = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_PEGAWAI_START })
    const response = await Api.deletePegawai(id)
    if (response.error === null) {
        dispatch({ type: types.DELETE_PEGAWAI_SUCCESS, payload: response.data })
    } else {
        dispatch({ type: types.DELETE_PEGAWAI_FAILED, payload: response.error })
    }
    return response
}

export {
    getListPegawai,
    createPegawai,
    getPegawai,
    updatePegawai,
    deletePegawai,
}
