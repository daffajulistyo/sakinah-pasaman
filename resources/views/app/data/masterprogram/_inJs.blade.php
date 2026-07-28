<script>
    <x-app.datatable.datatablejs :url="'/data/masterprogram/datatable'" />
    function userCrud() {
        return {
            datatable: datatable(),
            loadingState: false,
            successAlert: {
                open: false,
                message: ''
            },
            confirmDelete(id) {
                this.loadingState = true
                Swal.fire({
                    title: 'Hapus data ini?',
                    text: "Program yang sudah dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteData(id)
                    } else this.loadingState = false
                })
            },
            async deleteData(id) {
                try {
                    const response = await axios.delete('/data/masterprogram/' + id);
                    if (response.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.successAlert = { open: true, message: response.data.message }
                        this.datatable.refreshTable()
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: "something went wrong",
                        showConfirmButton: false,
                        timer: 1500
                    })
                } finally {
                    this.loadingState = false
                }
            },
        }
    }
</script>

