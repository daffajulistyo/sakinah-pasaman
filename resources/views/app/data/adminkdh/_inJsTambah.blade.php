<script>
    
    <x-app.datatable.datatablejs :url="env('APP_URL'). '/data/pegawai/datatable'" />
    function userCrud() {
        return {
            datatable: datatable(),
            openModal : false,
            formState : 'save',
            loadingState: false,
            idData : null,
            successAlert: {
                open: false,
                message: ''
            },
            failedAlert: {
                open: false,
                message: ''
            },
            failedAlert: {
                open: false,
                message: ''
            },
            confirmAdd(id = 0) {
                this.idData = id
                this.loadingState = true
                Swal.fire({
                title: 'Jadikan sebagai admin KDH?',
                text: "anda akan menjadikan pegawai ini sebagai admin kepala daerah pada aplikasi sakinah!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.addData()
                    }
                    else this.loadingState = false
                })
            },
            
            async addData(id = 0) {
                try {
                    const response = await axios.post('{{ env('APP_URL') }}/data/adminkdh', { user_id: this.idData });
                    if(response.status == 200) {
                    
                        Swal.fire({
                            icon: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        })

                        this.successAlert = {
                            open: true,
                            message: response.data.message
                        }
                        this.loadingState = false
                        window.location.href = '{{ env('APP_URL') }}/data/adminkdh'
                    }
                } catch (e) {
                    this.loadingState = false
                    Swal.fire({
                        icon: 'error',
                        title: "something went wrong",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            },
        }
    }
</script>