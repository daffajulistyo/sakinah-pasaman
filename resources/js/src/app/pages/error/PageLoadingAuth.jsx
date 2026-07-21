import { useDispatch, useSelector } from 'react-redux'
import { refillAuth } from '@/redux/ducks/auth/action'
import React from 'react'
import LoadingOverlay from 'react-loading-overlay'
import { useNavigate } from 'react-router-dom'

const PageLoadingAuth = () => {
    const dispatch = useDispatch()
    const authState = useSelector((state) => state.authState)
    const tokenStr = localStorage.getItem('token');
    const navigate = useNavigate()
    React.useEffect(() => {
        dispatch(refillAuth())
    },[])

    React.useEffect(() => {
        if(authState.isLogin && tokenStr !== null){
            if(localStorage.getItem('lastPath') !== null && localStorage.getItem('lastPath') !== ""){
                navigate(localStorage.getItem('lastPath'))
            }
            else navigate('/dashboard')
        }
        if(!authState.isLogin && tokenStr === null){
            navigate('/')
        }
    },[authState.isLogin, tokenStr])

    return (
        <LoadingOverlay
            spinner
            active={true}
            text="Mohon menunggu..."
        >

        <div className="w-screen h-screen dark:bg-gray-50 bg-white flex justify-center items-center">
            <div className="text-center align-middle text-9xl font-bold italic text-teal-500">
                NEW SAKIP 2024
            </div>
        </div>
        </LoadingOverlay>
    )
}

export default PageLoadingAuth