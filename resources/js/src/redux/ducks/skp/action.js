import * as types from './types'

const getListPeriodeSkp = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PERIODE_SKP_START })

    const response = await Api.getList_periodeSkp()
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PERIODE_SKP_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_LIST_PERIODE_SKP_FAILED, payload: response.error })

    return response
}

const createPeriodeSkp = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PERIODE_SKP_START })

    const response = await Api.create_periodeSkp(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_PERIODE_SKP_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_PERIODE_SKP_FAILED, payload: response.error })

    return response
}

const getPeriodeSkp = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PERIODE_SKP_START })

    const response = await Api.get_periodeSkp(id)
    if(response.error === null){
        dispatch({ type: types.GET_PERIODE_SKP_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_PERIODE_SKP_FAILED, payload: response.error })

    return response
}

const getListSasaranYangDiampu = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_SASARAN_YANG_DIAMPU_START })

    const response = await Api.getList_sasaranYangDiampu()
    if(response.error === null){
        dispatch({ type: types.GET_LIST_SASARAN_YANG_DIAMPU_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_LIST_SASARAN_YANG_DIAMPU_FAILED, payload: response.error })

    return response
}

const getListSkp = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_SKP_START })

    const response = await Api.getList_skp(id)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_SKP_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_LIST_SKP_FAILED, payload: response.error })

    return response
}

const createSkp = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_SKP_START })

    const response = await Api.create_skp(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_SKP_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_SKP_FAILED, payload: response.error })

    return response
}

const updateSkp = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_SKP_START })

    const response = await Api.update_skp(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_SKP_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_SKP_FAILED, payload: response.error })
    return response
}

const deleteSkp = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_SKP_START })

    const response = await Api.delete_skp(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_SKP_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_SKP_FAILED, payload: response.error })

    return response
}

const getIndikatorSkp = (id, params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_INDIKATOR_SKP_START })

    const response = await Api.get_indikatorSkp(id, params)
    if(response.error === null){
        dispatch({ type: types.GET_INDIKATOR_SKP_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_INDIKATOR_SKP_FAILED, payload: response.error })
    return response
}

const getListRencanaAksi = (idskp, idindikator) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RENCANA_AKSI_START })

    const response = await Api.getList_rencanaAksi({ skp_id: idskp, indikator_skp_id: idindikator })
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RENCANA_AKSI_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_LIST_RENCANA_AKSI_FAILED, payload: response.error })
    return response
}

const createRencanaAksi = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_RENCANA_AKSI_START })

    const response = await Api.create_rencanaAksi(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_RENCANA_AKSI_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_RENCANA_AKSI_FAILED, payload: response.error })
    return response
}

const updateRencanaAksi = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_RENCANA_AKSI_START })

    const response = await Api.update_rencanaAksi(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_RENCANA_AKSI_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_RENCANA_AKSI_FAILED, payload: response.error })
    return response
}
const deleteRencanaAksi = (id) => async (dispatch, getState, Api) => {
    dispatch({ type: types.DELETE_RENCANA_AKSI_START })

    const response = await Api.delete_rencanaAksi(id)
    if(response.error === null){
        dispatch({ type: types.DELETE_RENCANA_AKSI_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.DELETE_RENCANA_AKSI_FAILED, payload: response.error })
    return response
}

const getListSkpRealisasi = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_REALISASI_SKP_START })

    const response = await Api.getList_realisasiSkp(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_REALISASI_SKP_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_LIST_REALISASI_SKP_FAILED, payload: response.error })
    return response
}
const updateSkpRealisasi = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_REALISASI_SKP_START })

    const response = await Api.update_realisasiSkp(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_REALISASI_SKP_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_REALISASI_SKP_FAILED, payload: response.error })
    return response
}
const getListRencanaAksiSkpRealisasi = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RENCANA_AKSI_REALISASI_START })

    const response = await Api.getList_rencanaAksiSkpRealisasi(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RENCANA_AKSI_REALISASI_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.GET_LIST_RENCANA_AKSI_REALISASI_FAILED, payload: response.error })
    return response
}
const updateRencanaAksiSkpRealisasi = (id, payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.UPDATE_RENCANA_AKSI_REALISASI_START })

    const response = await Api.update_rencanaAksiSkpRealisasi(id, payload)
    if(response.error === null){
        dispatch({ type: types.UPDATE_RENCANA_AKSI_REALISASI_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.UPDATE_RENCANA_AKSI_REALISASI_FAILED, payload: response.error })
    return response
}

export {
    getListPeriodeSkp, createPeriodeSkp, getPeriodeSkp, getListSasaranYangDiampu, getListSkp, createSkp,
    getIndikatorSkp, updateSkp, deleteSkp, getListRencanaAksi, createRencanaAksi, updateRencanaAksi, deleteRencanaAksi,
    getListSkpRealisasi, updateSkpRealisasi, getListRencanaAksiSkpRealisasi, updateRencanaAksiSkpRealisasi
}