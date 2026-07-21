import React from 'react'
import LoadingOverlay from 'react-loading-overlay'
import BrandLogo from '@assets/public_assets/brand_logo.png'
import { useDispatch, useSelector } from 'react-redux'
import { useNavigate } from 'react-router-dom'

const Authorization = () => {
    const authState = useSelector((state) => (state.authState))
    const dispatch = useDispatch()
    const tokenStr = localStorage.getItem('token');
    const navigate = useNavigate()

    React.useEffect(() => {
        if(tokenStr !== null && !authState.isLogin){
            navigate('/authenticating')
        }
        else if(authState.isLogin){
            navigate('/dashboard')
        }
        else {
            navigate('/admin')
        }
    },[tokenStr, authState.isLogin])

    return (
        <LoadingOverlay spinner active={true} text={'Mohon menunggu...'}>
            <div className="w-screen h-screen dark:bg-gray-50 bg-white flex flex-col justify-center items-center">
                <img src={BrandLogo} alt="logo" className="md:w-1/3 sm:w-1/2 w-full" />
            </div>
        </LoadingOverlay>
    )
}

export default Authorization
