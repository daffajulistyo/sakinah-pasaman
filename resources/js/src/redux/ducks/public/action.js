import * as types from './types'

const getListPublicPohonKinerjaPemda = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_POHONKINERJA_PEMDA_START })

    const response = await Api.getList_publicPohonKinerjaBupati()
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_POHONKINERJA_PEMDA_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_POHONKINERJA_PEMDA_FAILED, payload: response.error })

    return response
}

const getPublicVisiPemda = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_VISI_PEMDA_START })

    const response = await Api.get_publicVisiBupati()
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_VISI_PEMDA_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_VISI_PEMDA_FAILED, payload: response.error })

    return response
}

const getPublicRencanaKinerjaPemda = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_RENCANAKINERJA_PEMDA_START })

    const response = await Api.getList_publicRencanaKinerjaBupati(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_RENCANAKINERJA_PEMDA_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_RENCANAKINERJA_PEMDA_FAILED, payload: response.error })

    return response
}

const getPublicRpjmdPemda = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_RPJMD_PEMDA_START })

    const response = await Api.getList_publicRpjmdBupati(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_RPJMD_PEMDA_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_RPJMD_PEMDA_FAILED, payload: response.error })

    return response
}

const getPublicPkPemda = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_PK_PEMDA_START })

    const response = await Api.getList_publicPkBupati(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_PK_PEMDA_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_PK_PEMDA_FAILED, payload: response.error })

    return response
}

const getPublicRenaksiPemda = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_RENAKSI_PEMDA_START })

    const response = await Api.getList_publicRenaksiBupati(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_RENAKSI_PEMDA_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_RENAKSI_PEMDA_FAILED, payload: response.error })

    return response
}

const getPublicRealisasiRenaksiPemda = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_REALISASIRENAKSI_PEMDA_START })

    const response = await Api.getList_publicRealisasiRenaksiBupati(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_REALISASIRENAKSI_PEMDA_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_REALISASIRENAKSI_PEMDA_FAILED, payload: response.error })

    return response
}

const getPublicDaftarOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_DAFTAR_OPD_START })

    const response = await Api.getList_publicDaftarOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_DAFTAR_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_DAFTAR_OPD_FAILED, payload: response.error })

    return response
}

const getListPublicPohonKinerjaOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_POHONKINERJA_OPD_START })

    const response = await Api.getList_publicPohonKinerjaOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_POHONKINERJA_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_POHONKINERJA_OPD_FAILED, payload: response.error })

    return response
}


const getPublicRencanaKinerjaOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_RENCANAKINERJA_OPD_START })

    const response = await Api.getList_publicRencanaKinerjaOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_RENCANAKINERJA_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_RENCANAKINERJA_OPD_FAILED, payload: response.error })

    return response
}

const getPublicRenstraOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_RENSTRA_OPD_START })

    const response = await Api.getList_publicRenstraOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_RENSTRA_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_RENSTRA_OPD_FAILED, payload: response.error })

    return response
}

const getPublicPkOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_PK_OPD_START })

    const response = await Api.getList_publicPkOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_PK_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_PK_OPD_FAILED, payload: response.error })

    return response
}

const getPublicRenaksiOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_RENAKSI_OPD_START })

    const response = await Api.getList_publicRenaksiOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_RENAKSI_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_RENAKSI_OPD_FAILED, payload: response.error })

    return response
}

const getPublicRealisasiRenaksiOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PUBLIC_REALISASIRENAKSI_OPD_START })

    const response = await Api.getList_publicRealisasiRenaksiOpd(payload)
    if(response.error === null){
        dispatch({ type: types.GET_PUBLIC_REALISASIRENAKSI_OPD_SUCCESS, payload: response.data ? response.data : response.actions })
    }
    else dispatch({ type: types.GET_PUBLIC_REALISASIRENAKSI_OPD_FAILED, payload: response.error })

    return response
}

export {
    getListPublicPohonKinerjaPemda,
    getPublicVisiPemda,
    getPublicRencanaKinerjaPemda,
    getPublicRpjmdPemda,
    getPublicPkPemda,
    getPublicRenaksiPemda,
    getPublicRealisasiRenaksiPemda,
    getPublicDaftarOpd,
    getListPublicPohonKinerjaOpd,
    getPublicRencanaKinerjaOpd,
    getPublicRenstraOpd,
    getPublicPkOpd,
    getPublicRenaksiOpd,
    getPublicRealisasiRenaksiOpd,
}