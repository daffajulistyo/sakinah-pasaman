import React from 'react'
import TabMenu from './TabMenu'

const TabMenuPohonKinerja = ({ active = "visi", params = null }) => {

    const pohonkinerjamenu = [
        {
            name: "visi",
            url: '/perencanaan/kdh/pohonkinerja/visi'
        },
        {
            name: "misi",
            url: '/perencanaan/kdh/pohonkinerja/misi'
        },
        {
            name: "tujuan",
            url: '/perencanaan/kdh/pohonkinerja/tujuan'
        },
        {
            name: "sasaran",
            url: '/perencanaan/kdh/pohonkinerja/sasaran'
        },
        {
            name: "indikator",
            url: '/perencanaan/kdh/pohonkinerja/indikator'
        }
    ]

    let aktifmenu = false
    const sendData = pohonkinerjamenu.map((item, key) => {
        if(active === item.name) aktifmenu = true

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

export default TabMenuPohonKinerja