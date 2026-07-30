import React from 'react'
import PublicLayout from '@/app/components/PublicLayout'
import { useDispatch, useSelector } from 'react-redux'
import { getListPublicPohonKinerjaPemda, getPublicVisiPemda } from '@/redux/ducks/public/action'
import ApexTree from 'apextree'

const PohonKinerjaPemda = () => {
    const [tahunLabel, setTahunLabel] = React.useState('')
    const dispatch = useDispatch()
    const publicDataState = useSelector((state) => state.publicDataState)
    React.useEffect(() => { publicDataState.data_visi_pemda === null ? dispatch(getPublicVisiPemda()) : null }, [publicDataState.data_visi_pemda])
    React.useEffect(() => {
        if(publicDataState.data_visi_pemda !== null)
        {
            let starts = publicDataState.data_visi_pemda?.period_starts ?? ""
            let ends = publicDataState.data_visi_pemda?.period_ends ?? ""
            if(starts !== "" && ends !== ""){
                setTahunLabel('TAHUN '+starts+' - '+ends)
            }
        }
    },[publicDataState.data_visi_pemda])
    React.useEffect(() => {
        publicDataState.data_pohonkinerja_pemda === null ?
        dispatch(getListPublicPohonKinerjaPemda()) : null
    },[])
    const chartData = () => {
        let data = null
        try {
            if(publicDataState.data_pohonkinerja_pemda !== null){
                const pkKdh = publicDataState.data_pohonkinerja_pemda
                pkKdh.forEach(item => {
                    let misiOrder = 0
                    let misi = item.misi.map(item => {
                        misiOrder++
                        let tujuan = item.tujuan.map(item => {
                            let tujuanId = item.id
                            let sasaranOrder = 0
                            let sasaran = item.sasaran.map(item => {
                                sasaranOrder++
                                return {
                                    id: item.id,
                                    name: renderSasaran(item.sasaran, sasaranOrder, tujuanId, item.id, item.indikator_sasaran)
                                }
                            })
                            return {
                                id: item.id,
                                name: renderTujuan(item.tujuan, item.id, item.indikator_tujuan),
                                children: sasaran
                            }
                        })
                        return {
                            id: item.id,
                            name: renderMisi(item.misi, misiOrder, item.id),
                            children: tujuan
                        }
                    })
                    data = {
                        id: item.id,
                        name: renderVisi(item.visi, item.id),
                        children: misi
                    }
                })
            }
        } catch (error) {
            data = null
            
        }
        return data
    }

    const options = {
        contentKey: 'name',
        width: '100%',
        height: 1200,
        nodeWidth: 850,
        nodeHeight: 740,
        childrenSpacing: 450,
        siblingSpacing: 150,
        direction: 'top',
        fontSize: '30px',
        fontFamily: 'sans-serif',
        fontWeight: 600,
        fontColor: '#454545',
        borderWidth: 1,
        borderColor: '#17a2b829  ',
        canvasStyle: 'border: 1px solid black;background: #f8f8f8  ;',
        viewBox:'-2178.778444457703 134.0002819732913 4095.327925800674 670.2664363012559',
        enableToolbar: true,
    };
    const canvasRef = React.useRef(null);
    const [isRendered, setIsRendered] = React.useState(false); // Menyimpan status apakah tree sudah dirender
    const renderChart = () => {
        const dataChart = chartData()
        if (!canvasRef.current || isRendered) return; // Pastikan canvasRef ada dan tree belum dirender
        
        // Kosongkan canvasRef sebelum merender
        canvasRef.current.innerHTML = ''; // Menghapus konten sebelumnya

        const tree = new ApexTree(canvasRef.current, options);
        console.log(dataChart);
        
        tree.render(dataChart);
        setTimeout(() => {
            let apexDom = document.getElementById('apexTreeWrapper')
            apexDom.childNodes[0].setAttribute('viewBox','-2178.778444457703 134.0002819732913 4095.327925800674 670.2664363012559')
            setIsRendered(true); // Set status ke true setelah dirender
        }, 500); 
    }
    React.useLayoutEffect(() => { 
        if(chartData() !== null){
            renderChart()
        }
    },[publicDataState.data_pohonkinerja_pemda])
    return (
        <PublicLayout loading={publicDataState.loading}>
            <div className="w-full px-4 md:py-6 py-2">
                <div className="w-full mx-auto max-w-screen-2xl">
                    <h1 className="font-bold md:text-2xl sm:text-xl text-lg text-primaryWebColor">POHON KINERJA PEMERINTAH KABUPATEN PASAMAN</h1>
                </div>
            </div>
            <div className="w-full max-w-screen-2xl min-h-screen bg-white mx-auto border p-4 rounded-lg">
                <h1 className="text-xl font-bold text-center mb-3"> POHON KINERJA BUPATI <br /> {tahunLabel} </h1>
                <div id="canvas" className='w-full' ref={canvasRef}></div>
            </div>
        </PublicLayout>
    )
}


const renderVisi = (visi, id) => {
    return `
    <div style="display : flex; flex; flex-direction: column; background-color: #E4E0E1; padding: 16px; border-radius: 35px; 
            width: 100%; height: 100%; justify-content: center; align-items: center; text-align: center; font-size: 48px; font-weight: 900;">
        Visi : <br />
        <span style="color: green;">
                ${visi.toUpperCase()}
        </span>
    </div>
    `
}

const renderMisi = (misi, urutan, id) => {
    return `
    <div style="display : flex; flex; flex-direction: column; background-color: #D6C0B3; color: white; padding: 16px; border-radius: 35px; 
            width: 100%; height: 100%; justify-content: center; align-items: center; text-align: center; font-size:32px; ">
        Misi ${urutan} : <br />
        <span style="color: #31511E;">
                ${misi.toUpperCase()}
        </span>
    </div>
    `
}

const renderTujuan = (tujuan, id, indikator) => {
    let indikatorRender = "(no data)"
    if(indikator){
        if(indikator.length > 0){
            indikatorRender = ""
            indikator.map((item) => {
                indikatorRender += `<li style="color: #e7fc42;">
                            ${item.indikator}
                    </li>`
            })
        }
    }
    return `
    <div style="display : flex; flex; flex-direction: column; background-color: #AB886D; color: white; padding: 4px; border-radius: 35px; width: 100%; height: 100%; justify-content: center; align-items: center; text-align: center;">
        Tujuan : <br />
        <span style="color: #D4F6FF;">
                ${tujuan}
        </span>
        <br />
        <br />
        Indikator : 
        <br />
        <ol style="text-align:left;">
        ${indikatorRender}
        </ol>
    </div>
    `
}

const renderSasaran = (sasaran, urutan, tujuanId, sasaranId, indikator) => {
    let indikatorRender = "(no data)"
    if(indikator){
        if(indikator.length > 0){
            indikatorRender = ""
            indikator.map((item, key) => {
                indikatorRender += `<li style="color: #e7fc42;">
                            ${key+1}. ${item.indikator}
                    </li>`
            })
        }
    }
    return `
    <div style="display: flex; flex-direction: column; background-color: #493628; color: white; padding: 35px; border-radius: 35px; width: 100%; height: 100%; justify-content: center; align-items: center; text-align: center;">
        Sasaran ${urutan} : <br />
        <span style="color: #F0BB78;">
                ${sasaran}
        </span>
        <br />
        <br />
        Indikator : 
        <br />
        <ol style="text-align:left;">
        ${indikatorRender}
        </ol>
    </div>
    `
}

export default PohonKinerjaPemda