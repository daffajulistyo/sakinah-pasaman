import React from 'react'
import { useSelector } from 'react-redux'
import { Navigate } from 'react-router-dom'

const WithIsPrivate = (WrappedComponents) => {
    const HOC = (props) => {
        const ISLOGIN = useSelector((state) => state.authState.isLogin)
        if(!ISLOGIN){
            return <Navigate to={'/authorization'} />
        }
        return <WrappedComponents {...props} />;
    }
    return HOC
}

export default WithIsPrivate