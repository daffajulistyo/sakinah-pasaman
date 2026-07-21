import * as types from './types'

const initialState = {
    loading: false,
    error: false,
    message: "",
    data: null,
    list: [],
    pagination: { page: 1, per_page: 10, total_records: 0, total_page: 1 },
}

export default function datamasterPegawaiReducer(state = initialState, actions) {
    switch (actions.type) {
        case types.GET_LIST_PEGAWAI_START:
            return { ...state, loading: true }
        case types.GET_LIST_PEGAWAI_SUCCESS: {
            const pag = actions.payload?.data || {}
            return {
                ...state, loading: false, error: false,
                list: pag.data || [],
                pagination: {
                    page: pag.current_page || 1,
                    per_page: pag.per_page || 10,
                    total_records: pag.total || 0,
                    total_page: pag.last_page || 1,
                }
            }
        }
        case types.GET_LIST_PEGAWAI_FAILED:
            return { ...state, loading: false, error: true }

        case types.CREATE_PEGAWAI_START:
            return { ...state, loading: true }
        case types.CREATE_PEGAWAI_SUCCESS:
            return { ...state, loading: false, error: false, data: null }
        case types.CREATE_PEGAWAI_FAILED:
            return { ...state, loading: false, error: true }

        case types.GET_PEGAWAI_START:
            return { ...state, loading: true }
        case types.GET_PEGAWAI_SUCCESS:
            return { ...state, loading: false, error: false, data: actions.payload?.data || actions.payload }
        case types.GET_PEGAWAI_FAILED:
            return { ...state, loading: false, error: true }

        case types.UPDATE_PEGAWAI_START:
            return { ...state, loading: true }
        case types.UPDATE_PEGAWAI_SUCCESS:
            return { ...state, loading: false, error: false, data: null }
        case types.UPDATE_PEGAWAI_FAILED:
            return { ...state, loading: false, error: true }

        case types.DELETE_PEGAWAI_START:
            return { ...state, loading: true }
        case types.DELETE_PEGAWAI_SUCCESS:
            return { ...state, loading: false, error: false }
        case types.DELETE_PEGAWAI_FAILED:
            return { ...state, loading: false, error: true }

        default:
            return state
    }
}
