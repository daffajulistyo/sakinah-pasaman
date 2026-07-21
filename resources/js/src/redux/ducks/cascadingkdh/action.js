import * as types from "./types"

const getListCascadingKdh = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_LIST_CASCADINGKDH_START })

    const response = await Api.getList_cascadingKDH()
    if(response.error === null){
        dispatch({ type: types.GET_LIST_CASCADINGKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_LIST_CASCADINGKDH_FAILED, payload: response.error })
    }
    return response
}

const createCascadingKdh = (payload) => async (dispatch, getState, Api) => {
    dispatch({ type: types.CREATE_CASCADINGKDH_START })

    const response = await Api.create_cascadingKDH(payload)
    if(response.error === null){
        dispatch({ type: types.CREATE_CASCADINGKDH_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.CREATE_CASCADINGKDH_FAILED, payload: response.error })
    }

    return response
}

export {
    getListCascadingKdh,
    createCascadingKdh
}