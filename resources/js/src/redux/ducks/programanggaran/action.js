import * as types from "./types"

const getListProgramAnggaran = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PROGRAM_ANGGARAN_START })

    let idskpd = payload.idskpd ?? ""
    let year = payload.year ?? new Date().getFullYear()
    let periode = payload.periode ?? "murni"
    const response = await Api.getProgramAnggaranSkpd(idskpd, year, periode)
    if(response.error === null){
        dispatch({ type: types.GET_PROGRAM_ANGGARAN_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_PROGRAM_ANGGARAN_FAILED, payload: response.error })
    }
    
    return response
}

const getListProgramAnggaranOpd = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_PROGRAM_ANGGARAN_START })

    let year = payload.year ?? new Date().getFullYear()
    let periode = payload.periode ?? "murni"
    const response = await Api.getProgramAnggaranSkpdforOPD(year, periode)
    if(response.error === null){
        dispatch({ type: types.GET_PROGRAM_ANGGARAN_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_PROGRAM_ANGGARAN_FAILED, payload: response.error })
    }
    
    return response
}


export {
    getListProgramAnggaran,
    getListProgramAnggaranOpd
}