import * as types from "./types"
import { parseJwt } from '@/app/helper/JwtHelper';
import Swal from "sweetalert2";

const refillAuth = () => async (dispatch, getState, Api) => {
    const token = localStorage.getItem('token');
    if (token !== null) {
        const response = await Api.verifyMe()
        if(response.error)
        {
            const message = response.error
            Swal.fire({
                title: "Perhatian!",
                text: message,
                icon: "warning"
            });
            localStorage.removeItem('token');
            dispatch({
                type: types.AUTH_DESTROY
            })
        }
        else{

            dispatch({
                type: types.AUTH_SUCCESS,
                payload: {
                    token: token,
                    data: response.data.data,
                },
            });
        }
    }
};

const authDestroy = () => async (dispatch, getState, Api) => {
    localStorage.removeItem('token');
    dispatch({
        type: types.AUTH_DESTROY,
    });
};


const authAction = (payload) => async (dispatch, getState, Api) => {
    dispatch({
        type: types.AUTH_LOADING,
    });
    return new Promise(async (resolve, reject) => {
        try {
            const response = await Api.authentication(payload);
            if (response.status !== 'failed') {
                let token = response.data.token_access;
                let biodata = parseJwt(token);
                localStorage.setItem('token', token);
                dispatch({
                    type: types.AUTH_SUCCESS,
                    payload: {
                        token: token,
                        data: biodata,
                    },
                });
                resolve({ code: 200 });
            } else {
                dispatch({
                    type: types.AUTH_FAILED,
                    payload: response.error,
                });
                reject({ code: 400 });
            }
        } catch (error) {
            dispatch({
                type: types.AUTH_FAILED,
                payload: 'error',
            });
            reject({ code: 401 });
        }
    });
};

const authActionPegawai = (payload) => async (dispatch, getState, Api) => {
  dispatch({
      type: types.AUTH_LOADING,
  });
  return new Promise(async (resolve, reject) => {
      try {
          const response = await Api.authenticationPegawai(payload);
          if (response.status !== 'failed') {
              let token = response.data.token_access;
              let biodata = parseJwt(token);
              localStorage.setItem('token', token);
              dispatch({
                  type: types.AUTH_SUCCESS,
                  payload: {
                      token: token,
                      data: biodata,
                  },
              });
              resolve({ code: 200 });
          } else {
              dispatch({
                  type: types.AUTH_FAILED,
                  payload: response.error,
              });
              reject({ code: 400 });
          }
      } catch (error) {
          dispatch({
              type: types.AUTH_FAILED,
              payload: 'error',
          });
          reject({ code: 401 });
      }
  });
};


const ssoAuthorizationAction = (payload) => async (dispatch, getState, Api) => {
    dispatch({
        type: types.AUTH_LOADING,
    });
    return new Promise(async (resolve, reject) => {
        try {
            const response = await Api.ssoAuthorization(payload);
            if (response.status !== 'failed') {
                let token = response.data.token_access;
                let biodata = parseJwt(token);
                localStorage.setItem('token', token);
                dispatch({
                    type: types.AUTH_SUCCESS,
                    payload: {
                        token: token,
                        data: biodata,
                    },
                });
                resolve({ code: 200 });
            } else {
                dispatch({
                    type: types.AUTH_FAILED,
                    payload: response.error,
                });
                reject({ code: 400 });
            }
        } catch (error) {
            dispatch({
                type: types.AUTH_FAILED,
                payload: 'error',
            });
            reject({ code: 401 });
        }
    });
};

const getMyRoles_act = () => async (dispatch, getState, Api) => {
    dispatch({ type: types.GET_MYROLES_START })

    const response = await Api.getMyRoles()
    if(response.error === null){
        dispatch({ type: types.GET_MYROLES_SUCCESS, payload: response.data })
    }
    else{
        dispatch({ type: types.GET_MYROLES_FAILED, payload: response.error })
    }
    return response
}

const changeMyRole_act = (payload) => async (dispatch, getState, Api) => {
    dispatch({
        type: types.AUTH_LOADING,
    });
    return new Promise(async (resolve, reject) => {
        try {
            const response = await Api.changeMyRole(payload);
            if (response.status !== 'failed') {
                let token = response.data.token_access;
                let biodata = parseJwt(token);
                localStorage.setItem('token', token);
                dispatch({
                    type: types.AUTH_SUCCESS,
                    payload: {
                        token: token,
                        data: biodata,
                    },
                });
                resolve({ code: 200 });
            } else {
                dispatch({
                    type: types.AUTH_FAILED,
                    payload: response.error,
                });
                reject({ code: 400 });
            }
        } catch (error) {
            dispatch({
                type: types.AUTH_FAILED,
                payload: 'error',
            });
            reject({ code: 401 });
        }
    });
};

export {
    refillAuth,
    authDestroy,
    authAction,
    authActionPegawai,

    ssoAuthorizationAction,

    getMyRoles_act,
    changeMyRole_act
}