import React from "react"
import ChevronIcon from "../Icons/ChevronIcon"
import Menu from "./Menu"
import MenuAdminKdh from "./MenuAdminKdh"
import MenuAdminOpd from "./MenuAdminOpd"
import MenuPegawai from "./MenuPegawai"
import MenuEsselon from "./MenuEsselon"
import { Link } from "react-router-dom"
import { useSelector } from "react-redux"
import { CalcIcon } from "../Icons"

const SideBar = () => {
    const [token, setToken] = React.useState("")
    const tokenStore = localStorage.getItem("token")
    React.useEffect(() => {
        setToken(tokenStore)
    },[tokenStore])
    const authState = useSelector((state) => state.authState)
    const menuItems = () => {
        if(authState.biodata.level === "Admin_KDH"){ return [...Menu,...MenuAdminKdh] }
        else if(authState.biodata.level === "Admin_OPD"){ return [...Menu,...MenuAdminOpd] }
        else if(authState.biodata.level === "Pegawai"){ 
            if(authState.biodata.eselon_id === "99"){
                return [...Menu,...MenuPegawai] 
            }
            else{
                return [...Menu,...MenuEsselon]
            }
        }
        else { return Menu }
    }
    
    const renderMenu = () => {
        if(Menu.length > 0){
            return (
                <ul className="space-y-2">
                    {
                        menuItems().map((item, index) => (
                            <li key={index}>
                            {
                                item.sub ?
                                <div key={index}>
                                    <button type="button"
                                        className="flex items-center p-2 w-full text-base font-bold text-teal-500 rounded-lg transition 
                                                    duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                        aria-controls={`dropdown-${index}`} data-collapse-toggle={`dropdown-${index}`}>
                                        <item.icon />
                                        <span className="flex-1 ml-3 text-left whitespace-nowrap">{item.name}</span>
                                        <ChevronIcon />
                                    </button>
                                    <ul id={`dropdown-${index}`} key={index} className="hidden py-2 space-y-2 bg-gray-100 dark:bg-gray-900 rounded-b-lg list-disc">
                                        {
                                            item.sub.map((val,key) => (
                                                <li key={key}>
                                                    {
                                                        val.external ? (
                                                            <a href={val.url} target="_blank" rel="noopener noreferrer">
                                                                {val.name}
                                                            </a>
                                                        ) : (
                                                            <Link to={val.url} className="flex items-center p-2 pl-11 w-full text-sm font-bold text-teal-500 rounded-lg 
                                                                transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                                                                {val.name}
                                                            </Link>
                                                        )
                                                    }
                                                </li>
                                            ))   
                                        }
                                    </ul>
                                </div>
                                :
                                <>
                                {
                                    item.external ? (
                                        <a href={item.url} target="_blank" rel="noopener noreferrer">
                                            {item.name}
                                        </a>
                                    ) : (
                                        <Link to={item.url} key={index}
                                            className="flex items-center p-2 text-base font-bold text-teal-500 rounded-lg 
                                                dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                            <item.icon />
                                            <span className="ml-3">{item.name}</span>
                                        </Link>
                                    )
                                }
                                </>
                            }
                            </li>
                        ))
                    }
                    {
                        authState.biodata.level === "Admin_OPD" && (
                            <li>
                                <a href={`https://evaluasi-sakinah.sumbarprov.go.id/login?token=${token}`} target="_blank" rel="noopener noreferrer" className="flex items-center p-2 text-base font-bold text-teal-500 rounded-lg 
                                    dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                                    <CalcIcon />
                                    <span className="ml-3">Evaluasi</span>
                                </a>
                            </li>
                        )
                    }
                    
                    
                </ul>
            )
        }
    }

    return (
        <aside id="default-sidebar"
            className="fixed top-0 left-0 z-[35] w-64 min-h-screen h-full transition-transform -translate-x-full sm:translate-x-0"
            aria-label="Sidenav">
            <div
                className="overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700 pt-24">
                { renderMenu() }
            </div>
        </aside>
    )
}

export default SideBar