import React from 'react'
import DarkModeSwitcher from '@components/DarkModeSwitcher'
import { authActionPegawai } from '@/redux/ducks/auth/action'
import { useDispatch, useSelector } from 'react-redux'
import LoadingOverlay from 'react-loading-overlay'
import { useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import Amico from '@assets/pegawai-illus.png'

const LoginPegawai = () => {
    const authState = useSelector((state) => (state.authState))
    const dispatch = useDispatch()
    const [usrNameInput, setUsrNameInput] = React.useState("")
    const [pwdInput, setPwdInput] = React.useState("")
    const [message, setMessage] = React.useState("")
    const tokenStr = localStorage.getItem('token');
    const [showPassword, setShowPassword] = React.useState(false);
    // login action
    const authentication = (e) => {
        e.preventDefault()
        if(usrNameInput !== "" && pwdInput !== ""){
            dispatch(
                authActionPegawai({
                    username: usrNameInput,
                    password: pwdInput
                })
            )
        }
    }
    const navigate = useNavigate()

    useEffect(() => {
        if(tokenStr !== null && !authState.isLogin){
            navigate('/authenticating')
        }
        if(authState.isLogin){
            navigate('/dashboard')
        }
    },[tokenStr, authState.isLogin])

    useEffect(() => {
        if(authState.error) setMessage(authState.message)
    },[authState.error])

    return (
        <LoadingOverlay 
            active={authState.loading}
            spinner
            text={"Mohon menunggu..."} 
        >
            <div className="w-screen min-h-screen relative">
                <div className="absolute top-3 right-3 sm:mt-2 mt-1 sm:mr-10 mr-3">
                    <DarkModeSwitcher />
                </div>
                <div className="absolute top-3 left-3 sm:mt-3 mt-2 sm:ml-10 ml-3">
                    <a href="/" className="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                        <img className="w-8 h-8 mr-2" src="https://dashboard.sumbarprov.go.id/img/logo_sumbar.png" alt="logo" />
                            SAKINAH    
                    </a>
                </div>
                <section className="bg-green-200 dark:bg-green-900">
                    <div className="flex flex-row items-center justify-center px-6 py-8 mx-auto h-screen lg:py-0 gap-6">
                        <div className="lg:w-1/2 w-full justify-center items-center flex lg:static absolute">
                            <img src={Amico} alt="" className="" />
                        </div>
                        <div className="lg:w-1/2 w-full flex justify-center items-center z-10">
                            <div className="rounded-lg dark:shadow dark:border md:mt-0 w-full xl:p-0 dark:bg-gray-800/85 dark:border-gray-700/85 lg:mx-16">
                                <div className="p-6 space-y-4 md:space-y-6 sm:p-8">
                                    <h1 className="text-3xl font-bold leading-tight tracking-tight text-gray-900 md:text-5xl dark:text-white">
                                        Login Pegawai
                                    </h1>
                                    {
                                        message !== "" ?
                                                    <div className="p-3 bg-red-200 rounded-lg animate__bounceIn">
                                                        <h4 className="text-xs font-bold text-red-500">
                                                            { message }
                                                        </h4>
                                                    </div> : ""
                                    }
                                    <form className="space-y-4 md:space-y-6" action="#" onSubmit={(e) => authentication(e)}>
                                        <div>
                                            <label htmlFor="username" className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                            <input type="text" name="username" id="username" onChange={(e) => setUsrNameInput(e.target.value)}
                                                className="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block 
                                                w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                                placeholder="johndoe" required="" />
                                        </div>
                                        <div>
                                            <label htmlFor="password" className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                            <div className="relative">
                                                <input 
                                                    type={showPassword ? "text" : "password"} 
                                                    name="password" 
                                                    id="password" 
                                                    placeholder="••••••••" 
                                                    onChange={(e) => setPwdInput(e.target.value)}
                                                    className="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block 
                                                        w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                                    required="" 
                                                />
                                                <button
                                                    type="button"
                                                    className="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-300"
                                                    onClick={() => setShowPassword(prev => !prev)}
                                                    tabIndex={-1}
                                                    aria-label={showPassword ? "Sembunyikan password" : "Tampilkan password"}
                                                >
                                                    {showPassword ? (
                                                        <svg className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10a9.97 9.97 0 012.395-6.374M21.47 16.95A9.96 9.96 0 0022 9c0-5.523-4.477-10-10-10a9.96 9.96 0 00-6.95 2.53M3 3l18 18" />
                                                        </svg>
                                                    ) : (
                                                        <svg className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path strokeLinecap="round" strokeLinejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.36 1.143-.908 2.205-1.632 3.128M15.89 17.829A9.956 9.956 0 0112 19c-5.523 0-10-4.477-10-10a9.958 9.958 0 014.177-8.271"/>
                                                        </svg>
                                                    )}
                                                </button>
                                            </div>
                                        </div>
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-start">
                                                <div className="flex items-center h-5">
                                                <input id="remember" aria-describedby="remember" type="checkbox" 
                                                    className="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 
                                                    dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" 
                                                    required="" />
                                                </div>
                                                <div className="ml-3 text-sm">
                                                <label htmlFor="remember" className="text-gray-500 dark:text-gray-300">Remember me</label>
                                                </div>
                                            </div>
                                            {/* <a href="#" className="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Forgot password?</a> */}
                                        </div>
                                        <div className="flex justify-center">
                                            <button type="submit"
                                                className="w-full sm:w-auto text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 
                                                font-medium rounded-lg text-lg px-24 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                                Masuk
                                            </button>
                                        </div>
                                        {/* <p className="text-sm font-light text-gray-500 dark:text-gray-400">
                                            Don’t have an account yet? <a href="#" className="font-medium text-primary-600 hover:underline dark:text-primary-500">Login</a>
                                        </p> */}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div className="absolute bottom-1 w-full flex justify-center item-center flex-col">
                    <div className="text-sm dark:text-white text-center">©2024 Pemerintah Provinsi Sumatera Barat</div>
                    <div className="text-sm dark:text-white text-center">Powered by Diskominfotik</div>
                </div>
            </div>
            
        </LoadingOverlay>
    )
}


export default LoginPegawai