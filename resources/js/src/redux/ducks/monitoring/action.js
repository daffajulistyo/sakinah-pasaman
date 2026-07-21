import * as types from './types'


const setSelectedOpd = (opd) => async (dispatch) => {
    dispatch({ type: types.SET_SELECTED_OPD, payload: opd })
}

const clearSelectedOpd = () => async (dispatch) => {
    dispatch({ type: types.CLEAR_SELECTED_OPD })
}

const getListMonitoringPohonKinerjaOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MONITORING_POHONKINERJA_OPD_START })

    const response = await Api.getList_monitoringPohonKinerjaOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MONITORING_POHONKINERJA_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_MONITORING_POHONKINERJA_OPD_FAILED, payload: response.error })

    return response
}

const getListMonitorCascadingOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MONITORING_CASCADING_OPD_START })

    const response = await Api.getList_monitoringCascadingOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MONITORING_CASCADING_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_MONITORING_CASCADING_OPD_FAILED, payload: response.error })

    return response
}

const getListMonitorRenstraOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MONITORING_RENSTRA_OPD_START })

    const response = await Api.getList_monitoringRenstraOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MONITORING_RENSTRA_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_MONITORING_RENSTRA_OPD_FAILED, payload: response.error })

    return response
}

const getListMonitorIkuOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MONITORING_IKU_OPD_START })

    const response = await Api.getList_monitoringIkuOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MONITORING_IKU_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_MONITORING_IKU_OPD_FAILED, payload: response.error })

    return response
}

const getListMonitorRenjaOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MONITORING_RENJA_OPD_START })

    const response = await Api.getList_monitoringRenjaOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MONITORING_RENJA_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_MONITORING_RENJA_OPD_FAILED, payload: response.error })

    return response
}

const getListMonitorPerjanjianKinerjaOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MONITORING_PK_OPD_START })

    const response = await Api.getList_monitoringPkOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MONITORING_PK_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_MONITORING_PK_OPD_FAILED, payload: response.error })

    return response
}

const getListMonitorRencanaAksiOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MONITORING_RENCANAAKSI_OPD_START })

    const response = await Api.getList_monitoringRencanaAksiOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MONITORING_RENCANAAKSI_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_MONITORING_RENCANAAKSI_OPD_FAILED, payload: response.error })

    return response
}

const getListMonitorRealisasiRenaksiOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MONITORING_REALISASIRENAKSI_OPD_START })

    const response = await Api.getList_monitoringRealisasiRenaksiOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MONITORING_REALISASIRENAKSI_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_MONITORING_REALISASIRENAKSI_OPD_FAILED, payload: response.error })

    return response
}

const getListMonitorDataKinerjaOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MONITORING_DATAKINERJA_OPD_START })

    const response = await Api.getList_monitoringDataKinerjaOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MONITORING_DATAKINERJA_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_MONITORING_DATAKINERJA_OPD_FAILED, payload: response.error })

    return response
}

const getListMonitorCapaianKinerjaOpd = (params) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_MONITORING_CAPAIANKINERJA_OPD_START })

    const response = await Api.getList_monitoringCapaianKinerjaOpd(params)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_MONITORING_CAPAIANKINERJA_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_LIST_MONITORING_CAPAIANKINERJA_OPD_FAILED, payload: response.error })

    return response
}


export {
    setSelectedOpd, clearSelectedOpd,
    getListMonitoringPohonKinerjaOpd,
    getListMonitorCascadingOpd,
    getListMonitorRenstraOpd,
    getListMonitorIkuOpd,
    getListMonitorRenjaOpd,
    getListMonitorPerjanjianKinerjaOpd,
    getListMonitorRencanaAksiOpd,
    getListMonitorRealisasiRenaksiOpd,
    getListMonitorDataKinerjaOpd,
    getListMonitorCapaianKinerjaOpd
}