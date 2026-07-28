<script>
    function userCrud() {
        return {
            loadingState: false,
            form: {
                kode_program: '',
                nama_program: '',
                kode_skpd: '',
                tahun: '2025',
                is_active: true,
            },
            successAlert: { open: false, message: '' },
            failedAlert: { open: false, message: '' },

            async simpanData() {
                this.loadingState = true
                try {
                    const response = await axios.post('/data/masterprogram', {
                        kode_program: this.form.kode_program,
                        nama_program: this.form.nama_program,
                        kode_skpd: this.form.kode_skpd,
                        tahun: this.form.tahun,
                        is_active: this.form.is_active ? 1 : 0,
                    })
                    if (response.data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.successAlert = { open: true, message: response.data.message }
                        this.form = { kode_program: '', nama_program: '', kode_skpd: '', tahun: '2025', is_active: true }
                    }
                } catch (e) {
                    const msg = e.response?.data?.message || 'something went wrong'
                    Swal.fire({ icon: 'error', title: msg, showConfirmButton: false, timer: 1500 })
                    this.failedAlert = { open: true, message: msg }
                } finally {
                    this.loadingState = false
                }
            }
        }
    }
</script>

