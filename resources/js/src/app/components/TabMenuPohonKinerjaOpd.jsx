import React from 'react'
import TabMenu from './TabMenu'

const TabMenuPohonKinerjaOpd = ({ active = "tujuan", params = null }) => {
    
    const pohonkinerjamenu = [
        {
            name: "tujuan",
            url: '/perencanaan/opd/pohonkinerja/tujuan'
        },
        {
            name: "sasaran",
            url: '/perencanaan/opd/pohonkinerja/sasaran'
        },
        {
            name: "indikator",
            url: '/perencanaan/opd/pohonkinerja/indikator'
        }
    ]
    let aktifmenu = false
    const sendData = pohonkinerjamenu.map((item, key) => {
        if(active === item.name){
            aktifmenu = true 
        }
        let querystring = ''
        if(!aktifmenu){
            querystring = new URLSearchParams(params)
            querystring = '?' + querystring.toString()
        }
        return {
            name: item.name,
            url: item.url + querystring,
            is_active: aktifmenu
        }
    })

    return (
        <TabMenu menuitem={sendData} active={active} />
    )
}

export default TabMenuPohonKinerjaOpd