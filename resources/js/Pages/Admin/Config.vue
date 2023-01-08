<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">

                <div class="row px-3">
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <inertia-link :href="'/'+this.UserRoles[0]" class=""> <span class="color-5-orange">Dashboard</span> </inertia-link>
                            </li>
                        </ol>
                    </div>
                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            Configuration Settings 
                        </h5>
                    </div>
                    
                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control border-right-0 searching" v-model="params.searchConfig" placeholder="Search..." aria-label="Search..." style="border-top-left-radius:50px;border-bottom-left-radius:50px;" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-light border-left-0 bg-white" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-right:1px solid #ced4da;">
                                    <i class="fas fa-search color-4-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row px-3 justify-content-start">
                    <div class="col shadow rounded bg-light">
                        <table class="table text-sm bg-light">
                            <thead>
                                <tr class="color-6-red">
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('key')">
                                            Key
                                            <i v-if="params.searchConfig === null && params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'key' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'key' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('value')">
                                            Value
                                            <i v-if="params.searchConfig === null && params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'value' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'value' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('description')">
                                            Description
                                            <i v-if="params.searchConfig === null && params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'description' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'description' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Last Updated By</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Updated At</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody class="nomatches border-0" v-if="Config.data.length == 0">
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <h6 class="text-danger">
                                            No matching results
                                        </h6>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody >
                                <tr class="color-6-red" v-for="conf in Config.data" :key="conf">
                                    <td class="block text-break text-wrap text-sm border-0" style="max-width: 150px;" v-html="conf.key"></td>
                                    <td class="block text-break text-wrap text-sm border-0" style="max-width: 150px;" v-html="conf.value"></td>
                                    <td class="block text-break text-wrap text-sm border-0" style="max-width: 150px;" v-html="conf.description"></td>
                                    <td class="block text-break text-wrap text-sm border-0" style="max-width: 150px;" v-html="conf.last_updated_by"></td>
                                    <td class="block text-break text-wrap text-sm border-0" style="max-width: 150px;" v-html="this.dateFormat(conf.updated_at)"></td>
                                    <td class="block text-break text-wrap text-sm border-0" style="max-width: 150px;">
                                        <a @click="editSpecificConfig(conf)" data-toggle="modal" data-target="#editConfigModal" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm">Edit <i class="fas fa-edit"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-8  mt-2 pl-0">
                        <pagination :links="Config.links" :current_page="Config.current_page" :prev_url="Config.prev_page_url" :next_url="Config.next_page_url" :total_page="Config.last_page" :path="Config.path"></pagination>
                    </div>
                </div> 

            </section>
        </div>

        <!-- edit config -->
		<modal :id="editConfigModal" :maxWidth="'md'">
            <div class="modal-content color-6-red">
                <form @submit.prevent="saveEditedSpecificConfig">
                    <div class="modal-header">
                        <h5 class="modal-title" > Edit Configuration Data </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Key </p>
                                    <input type="text" class="border-radius-10rem form-control" v-model="editConfigForm.key" readonly>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Last Updated By </p>
                                    <input type="text" class="border-radius-10rem form-control" v-model="editConfigForm.last_updated_by" readonly>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Value </p>
                                    <textarea class="rounded-0 form-control" cols="30" rows="10" style="resize:none;" v-model="editConfigForm.value"></textarea>
                                </div>

                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Description </p>
                                    <textarea class="rounded-0 form-control" cols="30" rows="10" style="resize:none;" v-model="editConfigForm.description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-red ml-auto btn-sm rounded-pill">Save <i class="fas fa-save"></i></button>
                    </div>
                </form>
            </div>
        </modal> 

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
            Pagination,
        },
        props:['UsersDetails','Config', 'Filters', 'errors', 'UserRoles'],
        data()  {
            return {
                params:{
                    searchConfig: this.Filters.searchConfig,
                    field: this.Filters.field,
                    direction: this.Filters.direction,
                },  
                editConfigModal: 'editConfigModal',
                editor: ClassicEditor,
                editorData: null,
                editorConfig:null,
                editConfigForm: this.$inertia.form({
                    value: '',
                    description: '',
                    last_updated_by: '',
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.AdminConfig'), params, {
                            replace: true,
                            preserveState: false,
                            onSuccess: (res) => {
                                console.log("AdminConfig");
                                // this.searching_data = res.props.Symptoms.data;
                                if(res.props.Config.data.length == 0){
                                    $('.nomatches').removeAttr('hidden');
                                }else{
                                    $('.nomatches').attr('hidden','hidden');
                                }
                            },
                        });
                    
                    }.bind(this), 500)
                },
                deep: true,
            },
        },
        methods:{
            dateFormat(date_data){
                return moment(date_data).format('MMM DD YYYY');
            },
            editSpecificConfig(conf_data){
                this.editConfigForm = JSON.parse(JSON.stringify(conf_data));
                console.log("edit config");
                console.log(this.editConfigForm);
                // this.editConfigForm.last_updated_by = this.editConfigForm.users_first_name +' '+ this.editConfigForm.users_last_name;
            },
            saveEditedSpecificConfig(){
                console.log(this.editConfigForm.value);
                this.$inertia.post(route(this.UserRoles[0]+'.SaveEditedSpecificConfig'), this.editConfigForm, {
                    forceFormData: true,
                    onSuccess: (res) => {
                        $('#editConfigModal').modal('hide');
                        Swal.fire({
                            title: 'Success!',
                            text: 'Configuration has been updated.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                        console.log("onSuccess");
                    },
                    onError: (errors) => {
                        console.log("onError");
                        Swal.fire({
                            title: 'Error!',
                            text: 'Network Error. Update has been cancelled.',
                            icon: 'Error',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                        console.log("onSuccess");
                    },
                    onFinish: () => {
                        console.log("onFinish");
                        console.log(this.editConfigForm);
                    },
                }).then(function (response) {
                }.bind(this));
            },
            sort(field){
                this.params.field = field;
                this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
            },
        },
        created:function(){
            console.log(this.Config);
        }
        
    }
</script>
