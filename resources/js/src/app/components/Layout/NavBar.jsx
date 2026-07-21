import DarkModeSwitcher from '@components/DarkModeSwitcher'
import { authDestroy } from '@/redux/ducks/auth/action'
import { useDispatch, useSelector } from 'react-redux'
import { Link, useNavigate } from 'react-router-dom'
import brandLogo from '@assets/public_assets/brand_logo.png'

const NavBar = () => {
    const dispatch = useDispatch()
    const navigate = useNavigate()
    const authState = useSelector((state) => state.authState)
    const LogoutAction = () => {
        sessionStorage.clear()
        dispatch(authDestroy())
        navigate('/admin')
    }
    return (
        <nav className="bg-white border-gray-200 dark:bg-gray-900 fixed top-0 z-40 drop-shadow-lg">
            <div className="w-screen flex flex-wrap items-center justify-between mx-auto p-4">
                <div className="flex">
                    <a href="#" className="flex items-center space-x-3 rtl:space-x-reverse">
                        <img src={brandLogo} className="h-10" alt="Pemprov Sumbar Logo" />
                        {/* <span className="self-center text-2xl font-bold whitespace-nowrap text-teal-500 dark:text-white">SAKINAH</span> */}
                    </a>
                </div>
                <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
                    aria-controls="default-sidebar" type="button"
                    className="items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span className="sr-only">Open main menu</span>
                    <svg className="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
                <div className="flex sm:order-last order-first sm:pr-6 gap-4">
                    <DarkModeSwitcher />
                    <div className="hidden w-full md:block md:w-auto" id="navbar-default sm:order-first">
                        <ul
                            className="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                            
                            <li>

                                <button id="dropdownDelayButton" data-dropdown-toggle="dropdownDelay" data-dropdown-delay="500" data-dropdown-trigger="hover"
                                    className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    type="button">
                                        AB
                                </button>
                                <div id="dropdownDelay"  aria-labelledby="dropdownDelayButton"
                                    className="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                    <div className="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                        <div>{authState.biodata.name}</div>
                                        <div className="font-medium truncate">{authState.biodata.username}</div>
                                    </div>
                                    <ul className="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownInformationButton">
                                        <li>
                                            <Link to="/dashboard"
                                                className="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</Link>
                                        </li>
                                        <li>
                                            <Link to="/profile"
                                                className="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profile</Link>
                                        </li>
                                        
                                        
                                    </ul>
                                    <div className="py-2">
                                        <button onClick={() => LogoutAction()}
                                            className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                Sign out
                                        </button>
                                    </div>
                                </div>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    )
}

export default NavBar