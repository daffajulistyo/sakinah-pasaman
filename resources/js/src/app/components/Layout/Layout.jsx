import WithIsPrivate from "@/app/hoc/WithIsPrivate"
import NavBar from "./NavBar"
import SideBar from "./SideBar"
import { initFlowbite } from "flowbite"
import { useEffect } from "react"
import LoadingOverlay from "react-loading-overlay"

const Layout = ({
    children,
    loading = false
}) => {

    
    useEffect(() => { initFlowbite() },[])
    return (
        
        <LoadingOverlay
            spinner
            active={loading}
            text="Mohon menunggu..."
        >
            <div className="w-screen min-h-screen bg-gray-100 dark:bg-gray-700 flex">
                <NavBar />

                <SideBar />
                <div className="h-auto sm:pt-24 pt-[5rem] sm:px-4 px-3 sm:pl-[17rem] flex w-full pb-10 gap-4 flex-col">
                    { children }
                </div>
            </div>
        </LoadingOverlay>
    )
}

export default WithIsPrivate(Layout)