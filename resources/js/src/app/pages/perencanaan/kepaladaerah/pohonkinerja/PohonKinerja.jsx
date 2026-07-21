import React from 'react'
import LoadingOverlay from 'react-loading-overlay'
import { useNavigate } from 'react-router-dom'

const PohonKinerja = () => {
    const navigate = useNavigate()
    React.useEffect(() => {
        navigate('/perencanaan/kdh/pohonkinerja/visi')
    },[])


    return (
        <LoadingOverlay
            spinner
            active={true}
            text="Mohon menunggu..."
        >

        <div className="w-screen h-screen bg-gray-50">

        </div>
        </LoadingOverlay>
    )
}

export default PohonKinerja