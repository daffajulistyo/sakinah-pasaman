
<script>
    <x-app.datatable.datatablejs :url="'/managements/menus/datatable'" />
    function menusCrud() {
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
            form: {
                menugroup_id: '',
                menu_label: '',
                menu_icon: '',
                menu_desc: '',
                menu_order: '',
                action_id: '',
                route: ''
            },
            errMsg: {
                menugroup_id: '',
                menu_label: '',
                menu_icon: '',
                menu_desc: '',
                menu_order: '',
                action_id: '',
                route: ''
            },
            addData() {
                this.resetForm()
                this.idData = null
                this.formState = 'save'
                this.openModal = true
            },
            confirmSave() {
                const title = this.formState == 'edit' ? 'Ubah data?' : 'Simpan data?'
                this.loadingState = true
                
                Swal.fire({
                title: title,
                text: "pastikan data yang diinputkan sudah benar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.saveData()
                    }
                    else this.loadingState = false
                })
            },
            async saveData() {
                    try {
                        const response = this.formState == 'save' ? await axios.post('/managements/menus', this.form) 
                                                                : await axios.put('/managements/menus/' + this.idData, this.form)
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
                            this.openModal = false
                            this.resetForm()
                            this.datatable.refreshTable()
                            this.loadingState = false
                        }
                } catch (e) {
                    this.loadingState = false
                    console.error('saveMenu error:', e)
                    if(e.response && e.response.status == 422) {
                        Swal.fire({
                            icon: 'error',
                            title: e.response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        let errors = e.response.data.errors;
                        Object.keys(this.errMsg).forEach(key => {
                            this.errMsg[key] = Array.isArray(errors[key]) ? errors[key].map((value) => {
                            return value;
                            }).join(' ') : errors[key]
                        });
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: e.response?.data?.message || 'Something went wrong',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
                
            },
            async editData(id = 0) {
                this.resetForm();
                this.idData = id
                this.formState = 'edit'
                this.loadingState = true
                try {
                    const response = await axios.get('/managements/menus/'+id);
                    if(response.status == 200) {
                        const dataApi = response.data.menu;
                        this.form = {
                            menu_label: dataApi.menu_label,
                            menu_icon: dataApi.menu_icon,
                            menu_desc: dataApi.menu_desc,
                            menu_order: dataApi.menu_order,
                            route: dataApi.route
                        }
                        this.actionsSelect.addItem(dataApi.action_id)
                        this.menugroupSelect.addItem(dataApi.menugroup_id)
                        this.openModal = true
                        this.loadingState = false
                    }
                } catch (e) {
                    this.loadingState = false
                    console.error('editMenu error:', e)
                    Swal.fire({
                        icon: 'error',
                        title: "something went wrong",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            },
            confirmDelete(id = 0) {
                this.idData = id
                this.loadingState = true
                Swal.fire({
                title: 'Hapus data ini?',
                text: "data yang sudah dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteData()
                    }
                    else this.loadingState = false
                })
            },
            async deleteData() {
                try {
                    const response = await axios.delete('/managements/menus/'+this.idData);
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
                    console.error('deleteMenu error:', e)
                    Swal.fire({
                        icon: 'error',
                        title: "something went wrong",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            },
            resetForm() {
                this.form = {
                    menugroup_id: '',
                    menu_label: '',
                    menu_icon: '',
                    menu_desc: '',
                    menu_order: '',
                    action_id: '',
                    route: ''
                }
                this.errMsg = {
                    menugroup_id: '',
                    menu_label: '',
                    menu_icon: '',
                    menu_desc: '',
                    menu_order: '',
                    action_id: '',
                    route: ''
                }
                this.menugroupSelect.clear()
                this.actionsSelect.clear()
            },
            menugroupSelect: null,
            actionsSelect: null,
            async getMenugroups()
            {
                try {
                    const response = await axios.get('/managements/menugroups/showall')
                    if(response.status == 200) {
                        const groups = response.data.menugroups
                        if(groups && groups.length > 0) {
                            this.menugroupSelect = new TomSelect('#menugroupDom',{
                                create: true,
                                valueField: 'id',
                                labelField: 'menugroup_label',
                                searchField: 'menugroup_label',
                                options: groups,
                                render: {
                                    option: function(data, escape) {
                                        return '<div class="flex flex-col mb-2">' +
                                                    '<span class="px-2 py-1 text-sm font-bold">' + escape(data.menugroup_label) + '</span>' +
                                                    '<span class="px-2 text-xs">' + escape(data.menugroup_desc) + '</span>' +
                                                '</div>';
                                    },
                                    item: function(data, escape) {
                                        return '<div title="' + escape(data.menugroup_desc) + '" class="text-base font-bold" >' + escape(data.menugroup_label) + '</div>';
                                    }
                                }
                            })
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'No menu groups found',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                } catch (e) {
                    console.error('getMenugroups error:', e)
                    Swal.fire({
                        icon: 'error',
                        title: "failed to get menugroups",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            },
            async getActions()
            {
                try {
                    const response = await axios.get('/managements/actions/showall/nonajaxonly')
                    if(response.status == 200) {
                        const actions = response.data.actions
                        if(actions && actions.length > 0) {
                            this.actionsSelect = new TomSelect('#actionDom',{
                                create: true,
                                valueField: 'id',
                                labelField: 'action_path',
                                searchField: 'action_path',
                                options: actions,
                                render: {
                                    option: function(data, escape) {
                                        return '<div class="flex flex-col mb-2">' +
                                                    '<span class="px-2 py-1 text-sm font-bold">' + escape(data.action_path) + '</span>' +
                                                '</div>';
                                    },
                                    item: function(data, escape) {
                                        return '<div title="' + escape(data.action_path) + '" class="text-base font-bold" >' + escape(data.action_path) + '</div>';
                                    }
                                }
                            })
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'No actions found',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                } catch (e) {
                    console.error('getActions error:', e)
                    Swal.fire({
                        icon: 'error',
                        title: "failed to get actions",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            }

        }
    }
</script>
