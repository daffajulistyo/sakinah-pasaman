import * as types from "./types"

const getListPkPegawai = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PKPEGAWAI_START })

    const response = await Api.getList_pkPegawai(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PKPEGAWAI_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PKPEGAWAI_FAILED, payload: response.error })
    }
    return response
}


const createPkPegawai = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PKPEGAWAI_START })

    const response = await Api.create_pkPegawai(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_PKPEGAWAI_SUCCESS, payload: response.data })
    }
    else dispatch({ type: types.CREATE_PKPEGAWAI_FAILED, payload: response.error })

    return response
}



const createProgramPkPegawai = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_PROGRAM_PKPEGAWAI_START })

    const response = await Api.create_pkPegawaiProgram(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_PROGRAM_PKPEGAWAI_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.CREATE_PROGRAM_PKPEGAWAI_FAILED, payload: response.error })
    }
    return response
}

const getListPkPegawaiProgram = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_PROGRAM_PKPEGAWAI_START })

    const response = await Api.getList_pkPegawaiProgram(payload)
    if(response.error === null){
        dispatch({ type: types.GET_LIST_PROGRAM_PKPEGAWAI_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_PROGRAM_PKPEGAWAI_FAILED, payload: response.error })
    }
    return response
}


export {
    getListPkPegawai,
    createPkPegawai,
    createProgramPkPegawai,
    getListPkPegawaiProgram
}