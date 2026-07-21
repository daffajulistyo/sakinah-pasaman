<script>
    
    <x-app.datatable.datatablejs :url="env('APP_URL'). '/data/adminkdh/datatable'" />
    function userCrud() {
        return {
            datatable: datatable(),
            openModal : false,
            formState : 'save',
            loadingState: false,
            idData : null,
            
            confirmDelete(user_id, id_roleplay) {
                this.loadingState = true
                Swal.fire({
                title: 'Hapus data ini?',
                text: "anda akan menghapus pegawai ini dari daftar admin KDH!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteData(user_id, id_roleplay)
                    }
                    else this.loadingState = false
                })
            },
            
            async deleteData(user_id, id_roleplay) {
                try {
                    const response = await axios.delete('{{ env('APP_URL') }}/data/adminkdh/'+user_id+'/'+id_roleplay);
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
                        this.datatable.refreshTable()
                        this.loadingState = false
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