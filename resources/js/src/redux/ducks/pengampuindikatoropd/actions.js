import * as types from "./types"

const getListPegawaiPengampuIndikatorOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PEGAWAI_PENGAMPUINDIKATOR_START })

    const response = await Api.getList_pegawai_pengampuIndikatorOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PEGAWAI_PENGAMPUINDIKATOR_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PEGAWAI_PENGAMPUINDIKATOR_FAILED, payload: response.error })
    }
    return response
}

const getListPengampuIndikatorOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PENGAMPUINDIKATOR_START })

    const response = await Api.getList_pengampuIndikatorOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PENGAMPUINDIKATOR_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PENGAMPUINDIKATOR_FAILED, payload: response.error })
    }
    return response
}


const createPengampuIndikatorOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PENGAMPUINDIKATOR_START })

    const response = await Api.create_pengampuIndikatorOpd(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_PENGAMPUINDIKATOR_START, payload: response.data })
    }
    else dispatch({ type: types.CREATE_PENGAMPUINDIKATOR_FAILED, payload: response.error })

    return response
}

const updatePengampuIndikatorOpd = (id,payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_PENGAMPUINDIKATOR_START })
    console.log(payload);
    
    const response = await Api.update_pengampuIndikatorOpd(id,payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_PENGAMPUINDIKATOR_START, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_PENGAMPUINDIKATOR_FAILED, payload: response.error })

    return response
}

const deletePengampuIndikatorOpd = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_PENGAMPUINDIKATOR_START })

    const response = await Api.delete_pengampuIndikatorOpd(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_PENGAMPUINDIKATOR_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_PENGAMPUINDIKATOR_FAILED, payload: response.error })

    return response
}

export {
    getListPegawaiPengampuIndikatorOpd,
    getListPengampuIndikatorOpd,
    createPengampuIndikatorOpd,
    updatePengampuIndikatorOpd,
    deletePengampuIndikatorOpd
}