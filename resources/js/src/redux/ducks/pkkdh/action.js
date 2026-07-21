import * as types from "./types"

const getListPkKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PKKDH_START })

    const response = await Api.getList_pkKdh(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PKKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PKKDH_FAILED, payload: response.error })
    }
    return response
}


const createPkKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PKKDH_START })

    const response = await Api.create_pkKdh(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_PKKDH_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_PKKDH_FAILED, payload: response.error })

    return response
}



const createProgramPkKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PROGRAM_PKKDH_START })

    const response = await Api.create_pkKdhProgram(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_PROGRAM_PKKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.CREATE_PROGRAM_PKKDH_FAILED, payload: response.error })
    }
    return response
}

const getListPkKdhProgram = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PROGRAM_PKKDH_START })

    const response = await Api.getList_pkKdhProgram(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PROGRAM_PKKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PROGRAM_PKKDH_FAILED, payload: response.error })
    }
    return response
}


export {
    getListPkKdh,
    createPkKdh,
    createProgramPkKdh,
    getListPkKdhProgram
}