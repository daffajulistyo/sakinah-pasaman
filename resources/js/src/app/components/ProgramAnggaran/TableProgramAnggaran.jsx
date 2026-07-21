import React from 'react'
import { StaticTable } from '@/app/components/Table'
import { numberFormatter } from '@/helper/common'
import PrimaryBtn from '@/app/components/Button/PrimaryBtn'
import { PlusCircleIcon } from '@heroicons/react/24/outline'

const TablePA = ({
    programAnggaranData = [],
    existingData=[],
    loading = false, 
    action = null
}) => {
    const [programAnggaranState, setProgramAnggaranState] = React.useState(programAnggaranData)
    const isCheckedFromExistingData = (prog, keg = null, subkeg = null) => {        
        if(existingData.length > 0){
            var program = existingData.find((p, a) => ( p.kode_program === prog))
            if(keg != null){                
                var kegiatan = program.data_kegiatan.find((k,b) => (k.kode_kegiatan === keg))
                if(subkeg !== null){
                    var subkegiatan = kegiatan.data_sub_kegiatan.find((s,c) => (s.kode_kegiatan === subkeg))
                    return {
                        checked: subkegiatan.checked ?? false,
                        anggaran: subkegiatan.uAnggaran ?? 0
                    }
                }
                else return {
                    checked: kegiatan.checked ?? false,
                    anggaran: kegiatan.uAnggaran ?? 0
                }
            }
            else return {
                checked: program.checked ?? false,
                anggaran: program.uAnggaran ?? 0
            }
        }
        else return {checked: false, anggaran: 0}
    }
    React.useEffect(() => {
        if(programAnggaranData.length > 0){
            let PA = []
            PA = programAnggaranData.map((item, key) => {
                let keg = []
                if(item.data_kegiatan.length > 0){
                    keg = item.data_kegiatan.map((k, i) => {
                        let subkeg = []
                        if(k.data_sub_kegiatan.length > 0){
                            subkeg = k.data_sub_kegiatan.map((sk) => {
                                var zxc = isCheckedFromExistingData(item.kode_program, k.kode_kegiatan, sk.kode_kegiatan)               
                                return {
                                    ...sk,
                                    checked: zxc.checked,
                                    parent: [item.id_program,k.id_giat],
                                    parentKey: [key,i],
                                    uAnggaran: zxc.anggaran
                                }
                            })
                        }
                        var asd = isCheckedFromExistingData(item.kode_program, k.kode_kegiatan)
                        return {
                            ...k,
                            checked: asd.checked,
                            parent: [item.id_program],
                            parentKey: [key],
                            data_sub_kegiatan: subkeg,
                            uAnggaran: asd.anggaran
                        }
                    })
                }
                var qwe = isCheckedFromExistingData(item.kode_program)
                return {
                    ...item,
                    checked: qwe.checked,
                    parent: [],
                    parentKey: [],
                    data_kegiatan: keg,
                    uAnggaran: qwe.anggaran
                }
            })
            
            setProgramAnggaranState(PA)
        }
        else setProgramAnggaranState(programAnggaranData)
    },[programAnggaranData])

    const tableHeader = () => (
        <tr>
            <th scope="col" className="px-4 py-3 text-center border-x-2">Program</th>
            <th scope="col" className="px-4 py-3 text-center border-x-2 w-[15%]">Anggaran</th>
            <th scope="col" className="px-4 py-3 text-center border-x-2 w-[15%]">Anggaran digunakan</th>
            <th scope="col" className="px-4 py-3 w-[5%]">
                <span className="sr-only">Actions</span>
            </th>
        </tr>
    )
    const onChangeCheck = (key, parent = []) => {
        let level = parent.length > 1 ? 3 : (parent.length > 0 ? 2 : 1)
        let currentStatus = false
        if(level === 1){ 
            currentStatus = programAnggaranState[key].checked 
            let PA = []
            PA = programAnggaranState.map((item, x) => {
                let keg = []
                if(item.data_kegiatan.length > 0){
                    keg = item.data_kegiatan.map((k, i) => {
                        let subkeg = []
                        if(k.data_sub_kegiatan.length > 0){
                            subkeg = k.data_sub_kegiatan.map((sk) => ({
                                ...sk,
                                checked: x === key ? !currentStatus : sk.checked,
                                parent: [item.id_program,k.id_giat],
                                parentKey: [x,i]
                            }))
                        }
                        return {
                            ...k,
                            checked: x === key ? !currentStatus : k.checked,
                            parent: [item.id_program],
                            parentKey: [x],
                            data_sub_kegiatan: subkeg
                        }
                    })
                }
                return {
                    ...item,
                    checked: x === key ? !currentStatus : item.checked,
                    parent: [],
                    parentKey: [],
                    data_kegiatan: keg
                }
            })
            PA = hitungAnggaran(PA)
            setProgramAnggaranState(PA)
        }
        else if(level === 2){ 
            currentStatus = programAnggaranState[parent[0]].data_kegiatan[key].checked
            let PA = []
            PA = programAnggaranState.map((item, x) => {
                let keg = []
                let progStatus = item.checked
                let totalFalseProg = currentStatus ? 1 : 0
                if(x === parent[0]){ 
                    progStatus = !currentStatus ? !currentStatus : item.checked; 
                }
                if(item.data_kegiatan.length > 0){
                    keg = item.data_kegiatan.map((k, i) => {
                        if(!k.checked && i !== key){ totalFalseProg += 1 }
                        let subkeg = []
                        if(k.data_sub_kegiatan.length > 0){
                            subkeg = k.data_sub_kegiatan.map((sk) => ({
                                ...sk,
                                checked: (i === key && x === parent[0] ) ? !currentStatus : sk.checked,
                                parent: [item.id_program,k.id_giat],
                                parentKey: [x,i]
                            }))
                        }
                        return {
                            ...k,
                            checked: (i === key && x === parent[0]) ? !currentStatus : k.checked,
                            parent: [item.id_program],
                            parentKey: [x],
                            data_sub_kegiatan: subkeg
                        }
                    })
                    if(totalFalseProg === item.data_kegiatan.length){ progStatus = false }
                }
                return {
                    ...item,
                    checked: progStatus,
                    parent: [],
                    parentKey: [],
                    data_kegiatan: keg
                }
            })
            PA = hitungAnggaran(PA)
            setProgramAnggaranState(PA)
        }
        else {
            currentStatus = programAnggaranState[parent[0]].data_kegiatan[parent[1]].data_sub_kegiatan[key].checked
            let PA = []
            PA = programAnggaranState.map((item, x) => {
                let keg = []
                let progStatus = item.checked
                let totalFalseProg = 0
                if(x === parent[0]){ 
                    progStatus = !currentStatus ? !currentStatus : item.checked; 
                }
                if(item.data_kegiatan.length > 0){
                    keg = item.data_kegiatan.map((k, i) => {
                        let subkeg = []
                        let kegStatus = k.checked
                        let totalFalse = currentStatus ? 1 : 0
                        if(x === parent[0] && i === parent[1]){ 
                            kegStatus = !currentStatus ? !currentStatus : k.checked; 
                        }
                        if(k.data_sub_kegiatan.length > 0){
                            subkeg = k.data_sub_kegiatan.map((sk, z) => {
                                if(!sk.checked && z !== key){ totalFalse += 1 }
                                 return {
                                ...sk,
                                checked: (x === parent[0] && i === parent[1] && z === key) ? !currentStatus : sk.checked,
                                parent: [item.id_program,k.id_giat],
                                parentKey: [x,i]
                                }
                            })
                            if(totalFalse === k.data_sub_kegiatan.length){ kegStatus = false }
                        }
                        
                        if(!kegStatus){ totalFalseProg += 1 }
                        return {
                            ...k,
                            checked: kegStatus,
                            parent: [item.id_program],
                            parentKey: [x],
                            data_sub_kegiatan: subkeg
                        }
                    })
                    
                    if(x === parent[0]){ 
                        console.log(totalFalseProg === item.data_kegiatan.length, totalFalseProg, progStatus, item.checked, item.nama_program);
                        
                    }
                    if(totalFalseProg === item.data_kegiatan.length){ progStatus = false }
                }
                return {
                    ...item,
                    checked: progStatus,
                    parent: [],
                    parentKey: [],
                    data_kegiatan: keg
                }
            })
            
            PA = hitungAnggaran(PA)
            setProgramAnggaranState(PA)
        }
        
    }
    const rowRender = (level, data, key = 0) => {
        if(level === "program"){
            return (
                <tr className="border-b dark:border-gray-700 bg-green-300/50 dark:text-white">
                    <td className="px-4 py-3 border-x-2">
                        <span className="font-semibold">{data.nama_program}</span>
                    </td>
                    <td className="px-4 py-3 border-x-2 text-right">{numberFormatter(data.anggaran)}</td>
                    <td className="px-4 py-3 border-x-2 text-right">{numberFormatter(data.uAnggaran)}</td>
                    <td className="px-4 py-3 flex h-full w-full justify-center items-center">
                        <div className="flex items-center mb-4">
                            <input 
                                id={`default-checkbox-${data.id_program}`} 
                                type="checkbox" 
                                checked={data.checked}
                                onClick={() => onChangeCheck(key, data.parentKey)}
                                className="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded 
                                    focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 
                                    focus:ring-2 dark:bg-gray-700 dark:border-gray-600" 
                            />
                        </div>
                    </td>
                </tr>
            )
        }
        else if(level === "kegiatan"){
            return (
                <tr className="border-b dark:border-gray-700 bg-yellow-200/50 dark:text-white">
                    <td className="px-4 py-3 border-x-2 md:pl-20 sm:pl-10 pl-5">
                        {data.nama_kegiatan}
                    </td>
                    <td className="px-4 py-3 border-x-2 text-right">{numberFormatter(data.anggaran)}</td>
                    <td className="px-4 py-3 border-x-2 text-right">{numberFormatter(data.uAnggaran)}</td>
                    <td className="px-4 py-3 flex h-full w-full justify-center items-center">
                        <div className="flex items-center mb-4">
                            <input 
                                id={`default-checkbox-${data.id_giat}`} 
                                type="checkbox" 
                                checked={data.checked}
                                onClick={() => onChangeCheck(key, data.parentKey)}
                                className="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded 
                                    focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 
                                    focus:ring-2 dark:bg-gray-700 dark:border-gray-600" 
                            />
                        </div>
                    </td>
                </tr>
            )
        }
        else if(level === "subkegiatan"){
            return (
                <tr className="border-b dark:border-gray-700 bg-blue-100/50 dark:text-white">
                    <td className="px-4 py-3 border-x-2 md:pl-32 sm:pl-16 pl-8">
                        {data.nama_sub_kegiatan}
                    </td>
                    <td className="px-4 py-3 border-x-2 text-right">{numberFormatter(data.anggaran)}</td>
                    <td className="px-4 py-3 border-x-2 text-right">{numberFormatter(data.uAnggaran)}</td>
                    <td className="px-4 py-3 flex h-full w-full justify-center items-center">
                        <div className="flex items-center mb-4">
                            <input 
                                id={`default-checkbox-${data.id_sub_giat}`} 
                                type="checkbox" 
                                checked={data.checked}
                                onClick={() => onChangeCheck(key, data.parentKey)}
                                className="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded 
                                    focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 
                                    focus:ring-2 dark:bg-gray-700 dark:border-gray-600" 
                            />
                        </div>
                    </td>
                </tr>
            )
        }
    }
    const hitungAnggaran = (data) => 
    {
        let PA = data
        if(PA.length > 0){
            PA = PA.map((item, key) => {
                let t1 = 0
                let keg = []
                if(item.data_kegiatan.length > 0){
                    keg = item.data_kegiatan.map((k, i) => {
                        let t2 = 0
                        let subkeg = []
                        if(k.data_sub_kegiatan.length > 0){
                            subkeg = k.data_sub_kegiatan.map((sk) => {
                                t2 += sk.checked ? parseInt(sk.anggaran) : 0
                                return {
                                    ...sk,
                                    uAnggaran: sk.checked ? parseInt(sk.anggaran) : 0
                                }
                            })
                        }
                        t1 += t2
                        return {
                            ...k,
                            data_sub_kegiatan: subkeg,
                            uAnggaran: t2
                        }
                    })
                }
                return {
                    ...item,
                    data_kegiatan: keg,
                    uAnggaran: t1
                }
            })
        }
        
        return PA
    }

    const simpanAction = () =>
    {
        action(programAnggaranState)
    }
    const renderTable = () => (
        programAnggaranState.length > 0 ? 
            programAnggaranState.map((item, key) => (
                <>
                {
                    rowRender("program",item, key)
                    
                }
                {
                    item.data_kegiatan.length > 0 ?
                    item.data_kegiatan.map((keg, i) => (
                        <>
                            {
                                rowRender("kegiatan",keg, i)
                            }
                            {
                                keg.data_sub_kegiatan.length > 0 ?
                                keg.data_sub_kegiatan.map((subkeg, n) => (
                                    <>
                                        {
                                            rowRender("subkegiatan",subkeg, n)
                                        }
                                    </>
                                )) : ""
                            }
                        </>
                    )) : ""
                }
                </>
            )) :
            <tr className="border-b dark:border-gray-700 bg-blue-100/50 dark:text-white">
                <td className="px-4 py-3 border-x-2 text-center" colSpan="100%" >No Data</td>
            </tr>
    )
    return (
        <>
        
        <div className="w-full flex justify-end mb-3">
            <PrimaryBtn loading={loading} onClick={() => simpanAction()} >
                <PlusCircleIcon className="w-5 h-5" />
                Simpan Data
            </PrimaryBtn>
        </div>
        <StaticTable header={tableHeader()}>
            {
                !loading ?
                renderTable()
                : 
                <tr className="border-b dark:border-gray-700 dark:text-white">
                    <td className="px-4 py-3 border-x-2 md:pl-20 sm:pl-10 pl-5 text-center" colSpan="100%">
                        Loading....
                    </td>
                </tr>

            }
        </StaticTable>
        </>
    )
}

export default TablePA