import Layout from '@/app/components/Layout/Layout';
import React from 'react'
import ApexTree from 'apextree'
import { Link } from 'react-router-dom';
import { useDispatch, useSelector } from 'react-redux';
import { getListPohonKinerjaKdh } from '@ducks/pohonkinerjakdh/action'
import { useNavigate } from 'react-router-dom';

const PohonKinerjaView = () => {
    const chartData = () => {
        let data = null
        try {
            if(pohonKinerjaKdhState.data !== null){
                const pkKdh = pohonKinerjaKdhState.data
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
    const pohonKinerjaKdhState = useSelector((state) => state.pohonKinerjaKdhState)
    React.useEffect(() => {
        dispatch(getListPohonKinerjaKdh())
    },[])
    const dispatch = useDispatch()
    const options = {
        contentKey: 'name',
        width: '100%',
        height: 650,
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
    },[pohonKinerjaKdhState.data])
    const navigate = useNavigate()
    const onClickPohonKinerja = (link) => {
        navigate(link)
    }
    return (
        <Layout>
            <div className="bg-white h-auto dark:bg-gray-800 rounded-lg drop-shadow-xl py-5 px-4 w-full flex min-h-[35rem]">
                <div className="flex flex-col">

                    <h1 className="text-xl font-bold mb-4 w-full text-center">Pohon Kinerja</h1>
                    <div className="w-full flex">
                        
                        <Link 
                            to={'/perencanaan/kdh/pohonkinerja/visi'} 
                            className="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded flex justify-center items-center gap-1">
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                strokeWidth="1.5" 
                                stroke="currentColor" 
                                className="size-4"
                            >
                                <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>

                            Kembali
                        </Link>
                    </div>
                    <div id="canvas" ref={canvasRef}></div>
                </div>
            </div>
        </Layout>
    )
}

const renderVisi = (visi, id) => {
    return `
    <a href="/perencanaan/kdh/pohonkinerja/misi?visiId=${id}" style="display : flex; flex; flex-direction: column; background-color: #E4E0E1; padding: 16px; border-radius: 35px; 
            width: 100%; height: 100%; justify-content: center; align-items: center; text-align: center; font-size: 48px; font-weight: 900;">
        Visi : <br />
        <span style="color: green;">
                ${visi.toUpperCase()}
        </span>
    </a>
    `
}

const renderMisi = (misi, urutan, id) => {
    return `
    <a href="/perencanaan/kdh/pohonkinerja/tujuan?misiId=${id}" style="display : flex; flex; flex-direction: column; background-color: #D6C0B3; color: white; padding: 16px; border-radius: 35px; 
            width: 100%; height: 100%; justify-content: center; align-items: center; text-align: center; font-size:32px; ">
        Misi ${urutan} : <br />
        <span style="color: #31511E;">
                ${misi.toUpperCase()}
        </span>
    </a>
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
    <a href="/perencanaan/kdh/pohonkinerja/sasaran?tujuanId=${id}" style="display : flex; flex; flex-direction: column; background-color: #AB886D; color: white; padding: 4px; border-radius: 35px; width: 100%; height: 100%; justify-content: center; align-items: center; text-align: center;">
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
    </a>
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
    <a href="/perencanaan/kdh/pohonkinerja/indikator?tujuanId=${tujuanId}&sasaranId=${sasaranId}" style="display: flex; flex-direction: column; background-color: #493628; color: white; padding: 35px; border-radius: 35px; width: 100%; height: 100%; justify-content: center; align-items: center; text-align: center;">
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
    </a>
    `
}

export default PohonKinerjaView