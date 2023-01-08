<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">

                <div class="row px-3">

                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            Feed Store
                        </h5>
                    </div>
                    
                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="mb-3" v-if="this.UserRoles.includes('Admin') || this.UserRoles.includes('IFodderContentManager')">
                            <button class="btn btn-outline-brown btn-sm" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;" data-toggle="modal" data-target="#createCategoryModal" >
                                Create Category  <i class="fas fa-plus"> </i>
                            </button>
                        </div>
                    </div>
                </div>

                <!--Categories table-->
                <div class="row px-3 justify-content-start">
                    <div class="col shadow rounded bg-light">
                        <table class="table text-sm">
                            <thead>
                                <tr class="color-6-red">
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('name')">
                                        Name
                                        <i v-if="params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th colspan="2" class="pt-3 pb-1 border-0"> Action </th>
                                    <!-- <th class="pt-3 pb-1 border-0"> </th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="color-6-red" v-for="Category in Categories" :key="Category">
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ Category.name }}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">Edit</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">
                                       <a :href="route(UserRoles[0]+'.AdminIngredients', {category_id: Category.id})" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm"> View Feeds <i class="fas fa-eye"></i> </a>
                                       <!-- <inertia-link class="color-6-red" :href="route(UserRoles[0]+'.AdminIngredients', {category_id: Category.id})">View Feeds</inertia-link> -->
                                   </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>              
            </section>


        <!-- Adding Category Modal -->
		<modal :id="createCategoryModal" :maxWidth="'md'">
            <div class="modal-content color-6-red">
                <div class="modal-header">
                    <h5 class="modal-title" > Create a Category </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetCreateForm()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light mb-0 color-6-red"> Category Name </p>
                            <input type="text" v-model="createCategoryForm.name" class="form-control rounded-0">
                             <div class="invalid-checked" v-if="errors.add">{{ errors.add.name }}</div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    
                   <button class="btn btn-outline-brown btn-sm rounded-pill" @click="createCategory()">Create <i class="fas fa-paper-plane"></i></button>
                   <button class="btn btn-outline-secondary ml-auto btn-sm rounded-pill" data-dismiss="modal" @click="resetCreateForm()">Cancel <i class="fas fa-times"></i> </button>
               
                </div>
            </div>
        </modal>



        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'
     import Modal from '@/Jetstream/Modal'
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import Swal from 'sweetalert2'

    export default {
        components: {
            AdminLayout,
            Modal,
            Swal,
        },
        props:['UsersDetails','Categories', 'Filters', 'errors', 'UserRoles'],
        data()  {
            return {
                 params:{
                    field: this.Filters.field,
                    direction: this.Filters.direction, 
                },    
                editor: ClassicEditor,
                createCategoryModal: 'createCategoryModal',
                createCategoryForm: this.$inertia.form({
                    name: '',
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.AdminCategories'), params, {replace: true, preserveState: false});

                    }.bind(this), 500)
                },
                deep: true,
            },
        },
        methods:{
            hasValidRole(roles_arr){
                console.log("roles_arr");
                var hasIFodderContentManager = roles_arr.includes('IFodderContentManager');
                var hasAdmin = roles_arr.includes('Admin');
                if(hasIFodderContentManager === true){
                    return "IFodderContentManager";
                }

                if(hasAdmin === true){
                    return "Admin";
                }
                
            },
            createCategory(){     
				this.$inertia.post(route(this.hasValidRole(this.UserRoles)+'.CreateCategory'), this.createCategoryForm, {
                    forceFormData: true,
 					onSuccess: (res) => {
                        console.log("onSuccess");            
                        $('#createCategoryModal').modal('hide');
                        
                        //Success info (moved to onsuccess)
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your created category has been saved successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });

                        this.createCategoryForm.reset();
                        this.$inertia.reload();
                        
                     }
				}).then(function (response) {
                    //Do nothing
            	}.bind(this));
            },
            resetCreateForm(){
                this.errors.add = null;
                this.createCategoryForm.reset();
            },            
            sort(field){
                this.params.field = field;
                this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
            }
        }     
        
    }
</script>
