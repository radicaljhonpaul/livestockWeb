<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">

                <div class="row px-3">

                    <div class="col mr-auto">
                        <h5 class="color-6-red font-weight-bold">
                            Authorized Systems Connecting to EASIS Web
                        </h5>
                    </div>
        
                </div>

                <div class="col-3 text-right ml-auto">
                        <div class="mb-3" v-if="this.UserRoles.includes('Admin')">
                            <button class="btn btn-outline-brown btn-sm" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;" data-toggle="modal" data-target="#createConnectionModal" >
                                Create Connection  <i class="fas fa-plus"> </i>
                            </button>
                        </div>
                </div>


                <!--Visit Ratings table-->
            
                <div class="row px-3 justify-content-start">
                    <div class="col shadow rounded bg-light">
                        <table class="table text-sm">
                            <thead>
                                <tr class="color-6-red">
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        Created Date
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0"> Name </th>
                                    <th scope="col" class="pt-3 pb-1 border-0"> Description </th>
                                    <th scope="col" class="pt-3 pb-1 border-0"> Token </th>
                                    <th scope="col" class="pt-3 pb-1 border-0"> Delete </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="color-6-red" v-for="Connection in Connections" :key="Connection">
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;" v-html="dateFormat(Connection.created_at)" ></td>
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ Connection.name }}</td>
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ Connection.description }}</td>
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;"> 
                                        <div class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm" data-toggle="modal" data-target="#viewTokenModal" @click="viewTokenInfo(Connection)">
                                         View Token <i class="fas fa-eye"> </i>
                                        </div>
                                    </td>
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;"> Delete </td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>   

       
            </section>

        <!-- Adding Category Modal -->
		<modal :id="createConnectionModal" :maxWidth="'md'">
            <div class="modal-content color-6-red">
                <div class="modal-header">
                    <h5 class="modal-title" > Create a Connection </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetCreateForm()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-lighter mb-0 color-6-red">  Name </p>
                            <input type="text" v-model="createConnectionForm.name" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.name }}</div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-lighter mb-0 color-6-red"> Description </p>
                            <input type="text" v-model="createConnectionForm.description" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.description }}</div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    
                   <button class="btn btn-outline-brown btn-sm rounded-pill" @click="createConnection()">Create <i class="fas fa-paper-plane"></i></button>
                   <button class="btn btn-outline-secondary ml-auto btn-sm rounded-pill" data-dismiss="modal" @click="resetCreateForm()">Cancel <i class="fas fa-times"></i> </button>
               
                </div>
            </div>
        </modal>            

        <!-- Adding Category Modal -->
		<modal :id="viewTokenModal" :maxWidth="'md'">
            <div class="modal-content color-6-red">
                <div class="modal-header">
                    <h5 class="modal-title" > Caution: Keep Token Secure </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetTokenModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-lighter mb-0 color-6-red">  Name </p>
                            <input type="text" v-model="specificConnection.name" class="form-control rounded-0">
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-lighter mb-0 color-6-red"> Description </p>
                            <input type="text" v-model="specificConnection.description" class="form-control rounded-0">
                        </div>

                          <div class="col-12 mt-2">
                            <p class="text-sm font-weight-lighter mb-0 color-6-red"> Token </p>
                            <input type="text" v-model="specificConnection.value" class="form-control rounded-0">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    
                 <button class="btn btn-outline-secondary ml-auto btn-sm rounded-pill" data-dismiss="modal" @click="resetTokenModal()">Close <i class="fas fa-times"></i> </button>
               
                </div>
            </div>
        </modal>      


        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'
     import Modal from '@/Jetstream/Modal'
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
    import Swal from 'sweetalert2'
    import Pagination from '@/CustomComponents/GeneralComp/Pagination.vue'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'

    export default {
        components: {
            AdminLayout,
            Modal,
            Swal,
            JetButton,
            JetInput,
        },
        props:['UsersDetails','Connections', 'Filters', 'errors', 'UserRoles'],
        data()  {
            return {
                 params:{
                    field: this.Filters.field,
                    direction: this.Filters.direction, 
                },    
                editor: ClassicEditor,
                createConnectionModal: 'createConnectionModal',
                viewTokenModal: 'viewTokenModal',
                createConnectionForm: this.$inertia.form({
                    name: '',
                    description: '',
                }),
                specificConnection: this.$inertia.form({
                    
                }),

            }
        },
        watch: {
            params: {
                handler(){
                    setTimeout(function () {
                        let params = this.params;

                        Object.keys(params).forEach(key => {
                            if(params[key] == ''){
                                delete params[key];
                            }
                        });
                        this.$inertia.get(this.route(this.UserRoles[0]+'.AdminConnections'), params, {replace: true, preserveState: false});
                    }.bind(this), 500)
                },
                deep: true,
            },
        },
        methods:{
            createConnection(){
				this.$inertia.post(route(this.UserRoles[0]+'.CreateConnection'), this.createConnectionForm, {
                    forceFormData: true,
 					onSuccess: (res) => {
                        console.log("onSuccess");            
                        $('#createConnectionModal').modal('hide');
                        
                        //Success info (moved to onsuccess)
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your created connection has been saved successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });

                        this.createConnectionForm.reset();
                        this.$inertia.reload();
                        
                     }
				}).then(function (response) {
                    //Do nothing
            	}.bind(this));
            },
            dateFormat(date_data){
                return moment(date_data).format('MMM DD YYYY');
            },
            resetCreateForm(){
                this.errors.add = null;
                this.createConnectionForm.reset();
            },
            resetTokenModal(){
                this.specificConnection.reset();
            },
            viewTokenInfo(connection){
                console.log(connection);
                this.specificConnection = JSON.parse(JSON.stringify(connection));

            }
        }     
        
    }
</script>
