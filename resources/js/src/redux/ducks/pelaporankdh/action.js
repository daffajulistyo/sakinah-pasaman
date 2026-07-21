import * as types from "./types"

const getListPelaporanDataKinerjaKdh = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PELAPORAN_DATAKINERJAKDH_START })

    const response = await Api.getList_dataKinerjaKdh()
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PELAPORAN_DATAKINERJAKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PELAPORAN_DATAKINERJAKDH_FAILED, payload: response.error })
    }
    return response
}

const getListPelaporanCapaianKinerjaKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PELAPORAN_CAPAIANKINERJAKDH_START })

    const response = await Api.getList_capaianKinerjaKdh(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PELAPORAN_CAPAIANKINERJAKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PELAPORAN_CAPAIANKINERJAKDH_FAILED, payload: response.error })
    }
    return response
}

export {
    getListPelaporanDataKinerjaKdh,
    getListPelaporanCapaianKinerjaKdh
}