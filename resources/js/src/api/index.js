import axios from 'axios'

const BASE_HOST_URL =import.meta.env.VITE_BASE_HOST_URL
class Api {

    getAuthorization = () => {
        const tokenStr = localStorage.getItem('token') ?? "";
        const authorization = { headers: {"Authorization" : `Bearer ${tokenStr}`} }
        return authorization;
    }

    async authenticationPegawai(payload){
        try {
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/pegawai/auth`, payload)
            if(status === 200) return { data, error: null }
        } catch (error) {
            return {
                status: 'failed',
                error: error.status == 401 ? error.response.data.message : error.message
            }
        }
    }

    async authentication(payload){
        try {
            const { status, data } = await axios.post(`${BASE_HOST_URL}/auth`, payload)
            if(status === 200) return { data, error: null }
        } catch (error) {
            return {
                status: 'failed',
                error: error.status == 401 ? error.response.data.message : error.message
            }
        }
    }

    async verifyMe()
    {
        const token = localStorage.getItem('token') || '';
        if (token.includes('mock-signature')) {
            return { data: { data: {} }, error: null };
        }
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/me`, authorization)
            if(status === 200) return { data, error: null }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message
            }
        }
    }


    async getMyRoles()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/getmyroles`, authorization)
            if(status === 200) return { data, error: null }
            else return {error: true}
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message
            }
        }
    }

    async changeMyRole(payload)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/changemyrole`, payload, authorization)
            if(status === 200) return { data, error: null }
            else return {error: true}
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message
            }
        }
    }


    /**
     * CRUDL Data Master Satuan
     */

    async getList_dmSatuan(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/data/satuan/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_dmSatuan(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/master/data/satuan`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_dmSatuan(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/data/satuan/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_dmSatuan(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/master/data/satuan/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_dmSatuan(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/master/data/satuan/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    /**
     * end of CRUDL Data Master Satuan
     */
    
    /**
     * CRUDL Data Master OPD
     */
    async getList_dmOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/data/opd/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_dmOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/master/data/opd`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_dmOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/data/opd/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_dmOpd(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/master/data/opd/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_dmOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/master/data/opd/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    /**
     * end of CRUDL Data Master OPD
     */

    /**
     * CRUDL Visi Kepala Daerah
     */

    async getList_visiKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/visi/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_visiKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/visi`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_visiKdh(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/visi/${id}`, authorization)
            if(status === 200) return { data, error:null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_visiKdh(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/visi/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_visiKdh(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/visi/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    
    /**
     * end of api CRUDL Visi Kepala Daerah
     */


    /**
     * CRUDL Misi Kepala Daerah
     */
    async getList_misiKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/misi/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_misiKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/misi`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_misiKdh(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/misi/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_misiKdh(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/misi/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_misiKdh(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/misi/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * end of CRUDL Misi Kepala Daerah
     */
    
    /**
     * CRUDL Tujuan Kepala Daerah
     */
    async getList_tujuanKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/tujuan/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_tujuanKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/tujuan`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_tujuanKdh(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/tujuan/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_tujuanKdh(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/tujuan/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_tujuanKdh(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/tujuan/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * end of CRUDL Tujuan Kepala Daerah
     */

    /**
     * CRUDL Sasaran Kepala Daerah
     */
    async getList_sasaranKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/sasaran/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_sasaranKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/sasaran`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_sasaranKdh(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/sasaran/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_sasaranKdh(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/sasaran/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_sasaranKdh(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/sasaran/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * end of CRUDL Sasaran Kepala Daerah
     */
    
    /**
     * CRUDL Indikator Kepala Daerah
     */
    async getList_indikatorKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/indikator/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_indikatorKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/indikator`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_indikatorKdh(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/indikator/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_indikatorKdh(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/indikator/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async upload_formulaPerhitunganKdh(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/indikator/upload/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error?.response?.data?.message ?? error.message
            }
        }
    }
    async delete_indikatorKdh(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/indikator/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * end of CRUDL Indikator Kepala Daerah
     */
    /**
     * get Pohon Kinerja
     */

    async getList_pohonKinerja()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/showall`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * get list on Cascading KDH
     */
    async getList_cascadingKDH()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/showallsasaran`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * create program cascading KDH
     */
    async create_cascadingKDH(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/pohonkinerja/cascading`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * get program anggaran skpd
     */
    async getProgramAnggaranSkpd(idskpd = "", year = "", periode = "murni")
    {
        try {
            let tahun = year != "" ? year : new Date().getFullYear()
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/integrated/program/anggaran_opd/${tahun}/${periode}/${idskpd}`, authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error?.response?.data?.message ?? error.message
            }
        }
    }
    async getProgramAnggaranSkpdforOPD(year = "", periode = "murni")
    {
        try {
            let tahun = year != "" ? year : new Date().getFullYear()
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/integrated/program/anggaran/${tahun}/${periode}`, authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error?.response?.data?.message ?? error.message
            }
        }
    }

    // NOTE: Method integrasi SIMONEV Bappeda di-comment (diganti master lokal).
    // Aktifkan kembali jika integrasi dibutuhkan.
    // /**
    //  * get program anggaran skpd simonev
    //  */
    // async getProgramAnggaranSkpdSimonev(idskpd = "", year = "")
    // {
    //     try {
    //         let tahun = year != "" ? year : new Date().getFullYear()
    //         const authorization = this.getAuthorization()
    //         const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/integrated/simonev-bappeda/anggaran/skpd-program/${idskpd}/${tahun}`, authorization)            
    //         if(status === 200) return { data, error: null }
    //         else return { error: true }
    //     } catch (error) {
    //         return {
    //             status: error.status,
    //             error: error.response.data.message ?? error.message
    //         }
    //     }
    // }

    /**
     * get list RPJMD KDH
     */
    async getListRpjmdKdh()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/rpjmd/list`, authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    /**
     * create target RPJMD
     */
    async createTargetRpjmdKdh(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/kdh/rpjmd/update/${id}`, params,authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    
    /**
     * update indikator kinerja utama
     */
    async updateIkuKdah(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/kdh/indikatorkinerjautama/update/${id}`, params,authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * modul rkpd get list, create and update
     */
    async getList_RkpdKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/rkpd/list`, authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async create_rkpdKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/rkpd`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async create_rkpdKdhProgram(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/rkpd-kegiatan`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async getList_rkpdKdhProgram(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/rkpd-kegiatan/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    
    /**
     * modul perjanjian kinerja kdh get list, create and update
     */
    async getList_pkKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/perjanjian-kinerja/list`, authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async create_pkKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/perjanjian-kinerja`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async create_pkKdhProgram(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/perjanjian-kinerja-program`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async getList_pkKdhProgram(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/perjanjian-kinerja-program/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    /**
     * API modul Rencana Aksi Kepala Daerah
     * 
     */
    async getList_renaksiKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/aksi/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async create_renaksiKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/aksi`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async getList_renaksiKdhLangkah(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/langkah-aksi/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async create_renaksiKdhLangkah(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/kdh/langkah-aksi`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    

    async update_renaksiKdhLangkah(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/kdh/langkah-aksi/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_renaksiKdhLangkah(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/kdh/langkah-aksi/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * api modul tujuan perencanaan Perangkat Daerah
     * 
     */
    async getList_SasaranDiampuOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pohonkinerja/getSasaranKDH`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }   
    }

    async getList_tujuanOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pohonkinerja/tujuan/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        } 
    }

    async create_tujuanOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/pohonkinerja/tujuan`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_tujuanOpd(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/opd/pohonkinerja/tujuan/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_tujuanOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pohonkinerja/tujuan/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_tujuanOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/opd/pohonkinerja/tujuan/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    /**
     * api modul sasaran perencanaan Perangkat Daerah
     * 
     */

    async getList_sasaranOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pohonkinerja/sasaran/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        } 
    }

    async create_sasaranOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/pohonkinerja/sasaran`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_sasaranOpd(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/opd/pohonkinerja/sasaran/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_sasaranOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pohonkinerja/sasaran/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_sasaranOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/opd/pohonkinerja/sasaran/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * api modul perencanaan opd bagian indikator
     * 
     */
    async getList_indikatorOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pohonkinerja/indikator/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        } 
    }

    async create_indikatorOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/pohonkinerja/indikator`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_indikatorOpd(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/opd/pohonkinerja/indikator/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_indikatorOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pohonkinerja/indikator/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_indikatorOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/opd/pohonkinerja/indikator/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    
    /**
     * get list on Cascading OPD
     */
    async getList_cascadingOpd()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/cascading/showall`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    /**
     * create program cascading OPD
     */
    async create_cascadingOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/cascading/create`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * get list Renstra OPD
     */
    async getListRenstraOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/renstra/list-indikator`, authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    /**
     * create target Renstra
     */
    async createTargetRenstraOpd(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/opd/renstra/update/${id}`, params,authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * update indikator kinerja utama OPD
     */
    async updateIkuOpd(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/opd/indikatorkinerjautama/update/${id}`, params,authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    /**
     * modul iku get list
     */
    async getList_ikuOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/indikatorkinerjautama/list`, authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * modul renja OPD get list, create and update
     */
    async getList_renjaOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/renja/showall`, authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async create_renjaOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/renja/create`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async create_renjaOpdProgram(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/renja/create-program`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async getList_renjaOpdProgram(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/renja/list-program`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * modul perjanjian kinerja opd get list, create and update
     */
    async getList_pkOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/perjanjiankinerja/showall`, authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async create_pkOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/perjanjiankinerja/create`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async create_pkOpdProgram(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/perjanjiankinerja/create-program`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async getList_pkOpdProgram(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/perjanjiankinerja/list-program`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * modul rencana aksi OPD
     * 
     */
    async getList_renaksiOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/aksi/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }
    async create_renaksiOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/aksi/create`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async getList_renaksiOpdLangkah(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/aksi/langkah/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async create_renaksiOpdLangkah(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/aksi/langkah`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async getList_renaksiOpdLangkah(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/aksi/langkah/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_renaksiOpdLangkah(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/opd/aksi/langkah/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_renaksiOpdLangkah(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/opd/aksi/langkah/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    
    /**
     * get Pohon Kinerja OPD
     */

    async getList_pohonKinerjaOpd()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pohonkinerja/showall`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * Pengampu indikator list, create, read, update, delete
     */

    async getList_pengampuIndikatorOpd(indikator_opd_id)
    {
        try {
            let params = {
                indikator_opd_id: indikator_opd_id
            }
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pohonkinerja/pengampu/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async create_pengampuIndikatorOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/pohonkinerja/pengampu`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async update_pengampuIndikatorOpd(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/opd/pohonkinerja/pengampu/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }
    async delete_pengampuIndikatorOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/opd/pohonkinerja/pengampu/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async getList_pegawai_pengampuIndikatorOpd(idskpd)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/integrated/simpeg/pegawai/${idskpd}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * Pengukuran KDH
     */
    async getList_realisasiRenaksiKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/realisasi/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async update_realisasiRenaksiKdh(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/kdh/realisasi/update/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async getList_langkahRealisasiRenaksiKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/realisasi/langkah/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_langkahRealisasiRenaksiKdh(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/kdh/realisasi/langkah/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * Pengukuran OPD
     */

    async getList_realisasiRenaksiOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/realisasi/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async update_realisasiRenaksiOpd(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/opd/realisasi/update/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async getList_langkahRealisasiRenaksiOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/realisasi/langkah/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_langkahRealisasiRenaksiOpd(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/opd/realisasi/langkah/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * Perjanjian Kinerja Pegawai
     * 
     */
    
    async getList_pkPegawai(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/perjanjiankinerja/showall`, authorization)            
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async create_pkPegawai(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/pegawai/perjanjiankinerja/create`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async create_pkPegawaiProgram(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/pegawai/perjanjiankinerja/create-program`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async getList_pkPegawaiProgram(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/perjanjiankinerja/list-program`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * modul rencana aksi Pegawai
     * 
     */
    async getList_renaksiPegawai(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/aksi/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }
    async create_renaksiPegawai(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/pegawai/aksi/create`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async getList_renaksiPegawaiLangkah(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/aksi/langkah/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async create_renaksiPegawaiLangkah(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/pegawai/aksi/langkah`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async getList_renaksiPegawaiLangkah(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/aksi/langkah/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_renaksiPegawaiLangkah(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/pegawai/aksi/langkah/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_renaksiPegawaiLangkah(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/pegawai/aksi/langkah/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * Pengukuran Kinerja Pegawai
     */

    async getList_realisasiRenaksiPegawai(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/realisasi/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    
    async update_realisasiRenaksiPegawai(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/pegawai/realisasi/update/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async getList_langkahRealisasiRenaksiPegawai(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/realisasi/langkah/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_langkahRealisasiRenaksiPegawai(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/pegawai/realisasi/langkah/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * Pelaporan Kepala Daerah
     * 
     */
    
    async getList_dataKinerjaKdh()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pelaporan/data_kinerja`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async getList_capaianKinerjaKdh(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/kdh/pelaporan/capaian`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * Pelaporan Perangkat Daerah
     * 
     */
    
    async getList_dataKinerjaOpd()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pelaporan/data_kinerja`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async getList_capaianKinerjaOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/pelaporan/capaian`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * =======================================================================================================================================================
     * SKP Pegawai
     * =======================================================================================================================================================
     */


    async getList_atasanPegawai()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/profil`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_atasanPegawai(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/pegawai/profil`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async getList_periodeSkp()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/skp/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_periodeSkp(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/pegawai/skp/create`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_periodeSkp(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/skp/read/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async getList_sasaranYangDiampu()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/skp/getSasaranPegawai`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async getList_skp(id)
    {
        try {
            const authorization = this.getAuthorization()
            // authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/skp/indikator/list/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_skp(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/pegawai/skp/indikator`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_skp(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/pegawai/skp/indikator/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_skp(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/pegawai/skp/indikator/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_indikatorSkp(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/skp/indikator/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async getList_rencanaAksi(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/skp/langkah`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async create_rencanaAksi(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/pegawai/skp/langkah`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_rencanaAksi(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/pegawai/skp/langkah/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }
    async delete_rencanaAksi(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/pegawai/skp/langkah/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async getList_realisasiSkp(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/skp/indikator/list/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async update_realisasiSkp(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/pegawai/skp/indikator/realisasi/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async getList_rencanaAksiSkpRealisasi(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/pegawai/skp/langkah/realisasi`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                // error: error.response?.data?.message ?? error.message
            }
        }
    }

    async update_rencanaAksiSkpRealisasi(id, params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/pegawai/skp/langkah/realisasi/${id}`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    /**
     * Monitoring Perangkat Daerah
     * 
     */
    async getList_monitoringPohonKinerjaOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/monitoring/opd/pohonkinerja`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_monitoringCascadingOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/monitoring/opd/cascading`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_monitoringRenstraOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/monitoring/opd/renstra`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_monitoringIkuOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/monitoring/opd/indikatorkinerjautama`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_monitoringRenjaOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/monitoring/opd/renja`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_monitoringPkOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/monitoring/opd/perjanjian_kinerja`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_monitoringRencanaAksiOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/monitoring/opd/aksi`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_monitoringRealisasiRenaksiOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/monitoring/opd/realisasi`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_monitoringDataKinerjaOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/monitoring/opd/data_kinerja`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_monitoringCapaianKinerjaOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/monitoring/opd/capaian`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        }
        catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicPohonKinerjaBupati()
    {
        try {

            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/kdh/pohonkinerja`)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async get_publicVisiBupati()
    {
        try {

            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/kdh/visi`)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicRencanaKinerjaBupati(payload)
    {
        try {
            let params = { params: payload }
            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/kdh/rkpd`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }
    
    async getList_publicRpjmdBupati(payload)
    {
        try {
            let params = { params: payload }
            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/kdh/rpjmd`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicPkBupati(payload)
    {
        try {
            let params = { params: payload }

            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/kdh/perjanjiankinerja`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicRenaksiBupati(payload)
    {
        try {
            let params = { params: payload }
            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/kdh/rencana`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicRealisasiRenaksiBupati(payload)
    {
        try {

            let params = { params: payload }
            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/kdh/realisasi`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicDaftarOpd(payload)
    {
        try {

            let params = { params: payload }
            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/opd/list`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicPohonKinerjaOpd(payload)
    {
        try {
            let params = { params: payload }
            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/opd/pohonkinerja`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicRencanaKinerjaOpd(payload)
    {
        try {
            let params = { params: payload }
            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/opd/renja`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicRenstraOpd(payload)
    {
        try {
            let params = { params: payload }
            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/opd/renstra`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }


    async getList_publicPkOpd(payload)
    {
        try {
            let params = { params: payload }

            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/opd/perjanjiankinerja`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicRenaksiOpd(payload)
    {
        try {
            let params = { params: payload }
            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/opd/rencana`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }

    async getList_publicRealisasiRenaksiOpd(payload)
    {
        try {

            let params = { params: payload }
            const { status, data } = await axios.get(`${BASE_HOST_URL}/dashboard/opd/realisasi`, params)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response?.data?.message ?? error.message
            }
        }
    }


    /**
     * api modul sasaran operasional perencanaan Perangkat Daerah
     * 
     */

    async getRef_sasaranOperasionalOpd()
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/operasional/sasaran/ref`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        } 
    }

    async getList_sasaranOperasionalOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/operasional/sasaran/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        } 
    }

    async create_sasaranOperasionalOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/operasional/sasaran`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_sasaranOperasionalOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/operasional/sasaran/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_sasaranOperasionalOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/opd/operasional/sasaran/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }


    /**
     * api modul indikator operasional perencanaan Perangkat Daerah
     * 
     */
    async getRef_indikatorOperasionalOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/operasional/indikator/ref/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        } 
    }

    async getList_indikatorOperasionalOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/operasional/indikator/list`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        } 
    }

    async create_indikatorOperasionalOpd(params)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/opd/operasional/indikator`, params, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async get_indikatorOperasionalOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/opd/operasional/indikator/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async delete_indikatorOperasionalOpd(id)
    {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/opd/operasional/indikator/${id}`, authorization)
            if(status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return {
                status: error.status,
                error: error.response.data.message ?? error.message
            }
        }
    }

    async getListPegawai(params) {
        try {
            const authorization = this.getAuthorization()
            authorization.params = params
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/pegawai/list`, authorization)
            if (status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

    async createPegawai(params) {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.post(`${BASE_HOST_URL}/v1/master/pegawai`, params, authorization)
            if (status === 201 || status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

    async getPegawai(id) {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/pegawai/${id}`, authorization)
            if (status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

    async updatePegawai(id, params) {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.put(`${BASE_HOST_URL}/v1/master/pegawai/${id}`, params, authorization)
            if (status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

    async deletePegawai(id) {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.delete(`${BASE_HOST_URL}/v1/master/pegawai/${id}`, authorization)
            if (status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

    async getRefEselon() {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/ref/eselon`, authorization)
            if (status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

    async getRefGolongan() {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/ref/golongan`, authorization)
            if (status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

    async getRefJenisJabatan() {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/ref/jenis-jabatan`, authorization)
            if (status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

    async getRefJabatan(opsi = {}) {
        try {
            const authorization = this.getAuthorization()
            const params = {};
            if (opsi.jenis_id) params.jenis_id = opsi.jenis_id;
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/ref/jabatan`, { ...authorization, params })
            if (status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

    async getRefSubOpd(opsi = {}) {
        try {
            const authorization = this.getAuthorization()
            const params = {};
            if (opsi.opd_id) params.opd_id = opsi.opd_id;
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/ref/sub-opd`, { ...authorization, params })
            if (status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

    async getRefRoles() {
        try {
            const authorization = this.getAuthorization()
            const { status, data } = await axios.get(`${BASE_HOST_URL}/v1/master/ref/roles`, authorization)
            if (status === 200) return { data, error: null }
            else return { error: true }
        } catch (error) {
            return { status: error.status, error: error.response?.data?.message ?? error.message }
        }
    }

}

export default Api