import { act } from 'react';
import * as types  from './types';
const initialState = {
    loading: false,
    error: false,
    message: '',
    isLogin: false,
    token: null,
    biodata: {},
    myroles: []
};

export default function authReducer(state = initialState, actions) {
    switch (actions.type) {
        case types.AUTH_LOADING:
            return {
                ...state,
                loading: true,
            };
        case types.AUTH_FAILED:
            return {
                ...state,
                error: true,
                loading: false,
                message: actions.payload,
            };
        case types.AUTH_SUCCESS:
            return {
                ...state,
                error:false,
                isLogin: true,
                loading:false,
                biodata: actions.payload.data,
                token: actions.payload.token,
            };
        case types.AUTH_DESTROY:
            return {
                ...state,
                isLogin: false,
                loading:false,
                biodata: {},
                token: '',
            };

        case types.GET_MYROLES_START:
            return {
                ...state,
                loading: true,
            };
        case types.GET_MYROLES_FAILED:
            return {
                ...state,
                error: true,
                loading: false,
                myroles: [],
                message: actions.payload,
            };
        case types.GET_MYROLES_SUCCESS:
            return {
                ...state,
                error: false,
                loading: false,
                myroles: actions.payload.data
            };
        default:
            return state;
    }
}