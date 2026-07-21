import React from 'react'
import TabMenu from './TabMenu'

const TabMenuSasaranOperasional = ({ active = "sasaran", params = null }) => {
    
    const pohonkinerjamenu = [
        {
            name: "sasaran",
            url: '/perencanaan/opd/sasaran_operasional'
        },
        {
            name: "indikator",
            url: '/perencanaan/opd/indikator_operasional'
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

export default TabMenuSasaranOperasional