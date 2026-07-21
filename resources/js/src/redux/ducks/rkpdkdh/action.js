import * as types from "./types"

const getListRkpdKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_RKPDKDH_START })

    const response = await Api.getList_RkpdKdh(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_RKPDKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_RKPDKDH_FAILED, payload: response.error })
    }
    return response
}


const createRkpdKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_RKPDKDH_START })

    const response = await Api.create_rkpdKdh(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_RKPDKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_RKPDKDH_FAILED, payload: response.error })

    return response
}



const createProgramRkpdKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PROGRAM_RKPD_KDH_START })

    const response = await Api.create_rkpdKdhProgram(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_PROGRAM_RKPD_KDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.CREATE_PROGRAM_RKPD_KDH_FAILED, payload: response.error })
    }
    return response
}

const getListRkpdKdhProgram = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PROGRAM_RKPD_KDH_START })

    const response = await Api.getList_rkpdKdhProgram(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PROGRAM_RKPD_KDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PROGRAM_RKPD_KDH_FAILED, payload: response.error })
    }
    return response
}

export {
    getListRkpdKdh,
    createRkpdKdh,
    createProgramRkpdKdh,
    getListRkpdKdhProgram
}