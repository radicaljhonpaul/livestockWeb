<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">
                <div class="row px-3">


                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                           Price Management
                        </h5>
                    </div>

                    <!-- Location for Adding & Searching -->
                    <div class="col-2 text-right ml-auto">
                        
                        <div class="mb-3" v-if="this.UserRoles.includes('Admin') || this.UserRoles.includes('IFodderContentManager')">
                            <button class="btn btn-outline-warning btn-sm" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;" data-toggle="modal" data-target="#createSrpYearModal" >
                                Create Price List  <i class="fas fa-plus"> </i>
                            </button>
   
                        </div>
                    </div>  

                </div>



                <!--Srp Year table-->
                <div class="row px-3 justify-content-start">
                    <div class="col shadow rounded bg-light">
                        <table class="table text-sm">
                            <thead>
                                <tr class="color-6-red">
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('year')">
                                            Year
                                            <i v-if="params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'year' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'year' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>


                                    <th class="pt-3 pb-1 border-0">Action </th>
                                    <th class="pt-3 pb-1 border-0">Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="color-6-red" v-for="SrpYear in SrpYears" :key="SrpYear">
                                   <td class="text-truncate text-sm" style="max-width: 150px;">{{ SrpYear.year }}</td>
                                   <td class="text-truncate text-sm" style="max-width: 150px;">
                                       <!-- Checkbox view for Admin & IFodder User-->
                                        <label v-if="SrpYear.is_active == true && (this.UserRoles.includes('Admin') || this.UserRoles.includes('IFodderContentManager'))" class="flex items-center">
                                            <input :id="'check_'+SrpYear.id" type="checkbox" class="form-checkbox" @change="onChangeActive($event,SrpYear.year,SrpYear.id)" checked>
                                        </label>

                                        <label v-if="SrpYear.is_active == false && (this.UserRoles.includes('Admin') || this.UserRoles.includes('IFodderContentManager'))" class="flex items-center">
                                            <input :id="'check_'+SrpYear.id" type="checkbox" class="form-checkbox" @change="onChangeActive($event,SrpYear.year,SrpYear.id)">
                                        </label>

                                        <!-- Plain Text for Other Users -->
                                         <span v-if="SrpYear.is_active == true && !(this.UserRoles.includes('Admin') || this.UserRoles.includes('IFodderContentManager'))" class="flex items-center">
                                            Active
                                        </span>

                                         <span v-if="SrpYear.is_active == false && !(this.UserRoles.includes('Admin') || this.UserRoles.includes('IFodderContentManager'))" class="flex items-center">
                                            Not Active
                                        </span>

                                   </td>
                                   <td class="text-truncate text-sm" style="max-width: 150px;">
                                       <a :href="route(this.UserRoles[0]+'.AdminPriceByYear', {srpyear_id: SrpYear.id})" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm"> View <i class="fas fa-eye"></i> </a>
                                       <!-- <inertia-link class="color-6-red" :href="route(this.UserRoles[0]+'.AdminPriceByYear', {srpyear_id: SrpYear.id})">View</inertia-link> -->
                                   </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>   

            </section>

        <!-- Adding SrpYear -->
		<modal :id="createSrpYearModal" :maxWidth="'md'">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" > Create an Price List </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetCreateForm()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light text-warning mb-0"> Name (Start with Year) </p>
                                <input type="text" v-model="createSrpYearForm.year" class="form-control rounded-0">
                             <div class="invalid-checked" v-if="errors.add">{{ errors.add.year }}</div>
                        
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                   <button class="btn btn-outline-primary ml-auto btn-sm rounded-pill" @click="createSrpYear()">Create <i class="fas fa-paper-plane"></i></button>
                   <button class="btn btn-outline-primary ml-auto btn-sm rounded-pill" data-dismiss="modal" @click="resetCreateForm()">Cancel</button>
                </div>
            </div>
        </modal>


        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'
    import Pagination from '@/CustomComponents/GeneralComp/Pagination.vue'
    import Modal from '@/Jetstream/Modal'
    import Swal from 'sweetalert2'
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        components: {
            AdminLayout,
            Pagination,
            Modal,
            Swal,
            
        },
        props:['UsersDetails','SrpYears', 'Filters', 'UserRoles', 'errors'],
        data()  {
            return {
                params:{
                    field: this.Filters.field,
                    direction: this.Filters.direction, 
                },
                editor: ClassicEditor,
                createSrpYearModal: 'createSrpYearModal',
                createSrpYearForm: this.$inertia.form({
                    year: '',
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.AdminPricing'), params, {replace: true, preserveState: false});

                    }.bind(this), 500)
                },
                deep: true,
            },
        },
        methods:{
            sort(field){
                this.params.field = field;
                this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
            },
            hasIfodderRole(roles_arr){
                console.log("roles_arr");
                var hasIFodderContentManager = roles_arr.includes('IFodderContentManager');
                var hasAdmin = roles_arr.includes('Admin');
                if(hasIFodderContentManager === true){
                    return "IFodderContentManager";
                }else if(hasAdmin === true){
                    return "Admin";
                }
            },
             createSrpYear(){
                
				this.$inertia.post(route(this.hasIfodderRole(this.UserRoles)+'.CreateSrpYear'), this.createSrpYearForm, {
                    forceFormData: true,
 					onSuccess: (res) => {
                        console.log("onSuccess");            
                        $('#createSrpYearModal').modal('hide');
                        
                        //Success info (moved to onsuccess)
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your created Srp Year has been saved successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });

                        this.createSrpYearForm.reset();
                        this.$inertia.reload();
                        
                     }
				}).then(function (response) {
                    //Do nothing
            	}.bind(this));
            },
            resetCreateForm(){
                this.errors.add = null;
                this.createSrpYearForm.reset();
            },
            onChangeActive($event,srp_year,srp_year_id) {
                if($('#check_'+srp_year_id).is(":checked")){
                    Swal.fire({
                        title: 'Activate '+srp_year+' Price List?',
                        body: 'hello', 
                        showDenyButton: true,
                        confirmButtonText: 'Yes',
                        denyButtonText: `No`,
                    }).then((result) => {
                        if (result.isConfirmed) {

                            this.$inertia.post(route(this.UserRoles[0]+'.ActivateSrpYear'),
                            {
                                srp_year_id: parseInt(srp_year_id),
                                is_active: 1,
                            },

                            {
                                replace: true,
                                preserveState: false,
                                onSuccess: (res) => {
                                    Swal.fire('Saved!', '', 'success')
                                    this.$inertia.reload();
                                },
                            });
                        } else if (result.isDenied) {
                            this.$inertia.reload();
                            Swal.fire('Changes are not saved', '', 'info')
                        }
                    })

                }else if($('#check_'+srp_year_id).is(":not(:checked)")){
                    Swal.fire({
                        title: 'Deactive '+srp_year+' Price List?', 
                        showDenyButton: true,
                        confirmButtonText: 'Yes',
                        denyButtonText: `No`,
                    }).then((result) => {
                        if (result.isConfirmed) {

                            this.$inertia.post(route(this.UserRoles[0]+'.ActivateSrpYear'),
                            {
                                srp_year_id: parseInt(srp_year_id),
                                is_active: 0,
                            },

                            {
                                replace: true,
                                preserveState: false,
                                onSuccess: (res) => {
                                    Swal.fire('Saved!', '', 'success')
                                    this.$inertia.reload();
                                },
                            });
                        } else if (result.isDenied) {
                            this.$inertia.reload();
                            Swal.fire('Changes are not saved', '', 'info')
                        }
                    })
                }
            },
        }

        
    }
</script>

