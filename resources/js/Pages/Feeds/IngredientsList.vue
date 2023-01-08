

<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">
                <div class="row px-3">
                    
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <inertia-link :href="route(UserRoles[0]+'.AdminCategories')" class="color-6-red" :active="route().current(this.UserRoles[0]+'.AdminCategories')">Feed Store</inertia-link>
                            </li>
                        </ol>
                    </div>

                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                           {{ Category.name }}
                        </h5>
                    </div>

                    <!-- Location for Adding & Searching -->
                    <div class="col-2 text-right ml-auto">
                        <div class="mb-3" v-if="this.UserRoles.includes('Admin') || this.UserRoles.includes('IFodderContentManager')">
                            <button class="btn btn-outline-brown btn-sm" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;" data-toggle="modal" data-target="#createIngredientModal" >
                                Create Ingredient  <i class="fas fa-plus"> </i>
                            </button>
                        </div>
                    </div> 
                </div>

              


                <!--Feeds table-->
                <div class="row px-3 justify-content-start">
                    <div class="col shadow rounded bg-light">
                        <table class="table text-sm">
                            <thead>
                                <tr class="color-6-red">
                                    <th class="pt-3 pb-1 border-0">Category</th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('name')">
                                            Feed Name
                                            <i v-if="params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th class="pt-3 pb-1 border-0">DM</th>
                                    <th class="pt-3 pb-1 border-0">ME</th>
                                    <th class="pt-3 pb-1 border-0">TDN</th>
                                    <th class="pt-3 pb-1 border-0">CP</th>
                                    <th class="pt-3 pb-1 border-0">NDF</th>
                                    <th class="pt-3 pb-1 border-0">Ca</th>
                                    <th class="pt-3 pb-1 border-0">P(g)</th>
                                    <th class="pt-3 pb-1 border-0">Min</th>
                                    <th class="pt-3 pb-1 border-0">Max</th>
                                    <!-- Show if Admin or iFodder Content Manager -->
                                    <th class="pt-3 pb-1 border-0" v-if="this.UserRoles.includes('Admin') || this.UserRoles.includes('IFodderContentManager')">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="color-6-red" v-for="Feed in Ingredients.data" :key="Feed">
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ Feed.category.name }}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{Feed.name}}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{Feed.dm}}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{Feed.me}}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{Feed.tdn}}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{Feed.cp}}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{Feed.ndf}}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{Feed.ca}}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{Feed.p}}</td>
                                   
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{Feed.min}}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{Feed.max}}</td>

                                   <!-- Show if Admin or iFodder Content Manager -->
                                   <td class="text-sm border-0"  v-if="this.UserRoles.includes('Admin') || this.UserRoles.includes('IFodderContentManager')">
                                       <a @click="editSpecificIngredient(Feed)" data-toggle="modal" data-target="#ingredientsModal" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm">Edit <i class="fas fa-pen"></i> </a>
                                   </td>    

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    
                    <div class="col-12 mt-2 pl-0">
                        <pagination :links="Ingredients.links" :current_page="Ingredients.current_page" :prev_url="Ingredients.prev_page_url" :next_url="Ingredients.next_page_url" :total_page="Ingredients.last_page" :path="Ingredients.path"></pagination>
                    </div>


                </div>        

            </section>

        <!-- Adding Ingredient Modal -->
		<modal :id="createIngredientModal" :maxWidth="'md'">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title color-6-red" > Create an Ingredient </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetCreateForm()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Category </p>
                            <p>{{ Category.name }}</p>
                             <input type="text" v-model="createIngredientForm.category_id" class="form-control rounded-0" hidden>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Feed Name </p>
                            <input type="text" v-model="createIngredientForm.name" class="form-control rounded-0">
                             <div class="invalid-checked" v-if="errors.add">{{ errors.add.name }}</div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> DM </p>
                            <input type="text" v-model="createIngredientForm.dm" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.dm }}</div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> ME (MJ) </p>
                            <input type="text" v-model="createIngredientForm.me" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.me }}</div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> TDN </p>
                            <input type="text" v-model="createIngredientForm.tdn" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.tdn }}</div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> CP </p>
                            <input type="text" v-model="createIngredientForm.cp" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.cp }}</div>
                        </div>                        

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> NDF </p>
                            <input type="text" v-model="createIngredientForm.ndf" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.ndf }}</div>
                        </div>  

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Ca </p>
                            <input type="text" v-model="createIngredientForm.ca" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.ca }}</div>
                        </div>                          

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> P(g) </p>
                            <input type="text" v-model="createIngredientForm.p" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.p }}</div>
                        </div> 

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Min </p>
                            <input type="text" v-model="createIngredientForm.min" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.min }}</div>
                        </div> 

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Max </p>
                            <input type="text" v-model="createIngredientForm.max" class="form-control rounded-0">
                            <div class="invalid-checked" v-if="errors.add">{{ errors.add.max }}</div>
                        </div>                                                 


                    </div>
                </div>
                <div class="modal-footer">
                    
                   <button class="btn btn-outline-brown btn-sm rounded-pill" @click="createIngredient()">Create <i class="fas fa-paper-plane"></i></button>
                   <button class="btn btn-outline-secondary ml-auto btn-sm rounded-pill" data-dismiss="modal" @click="resetCreateForm()">Cancel <i class="fas fa-times"></i> </button>
               
                </div>
            </div>
        </modal>

        <!-- Editing Modal-->
		<modal :id="ingredientsModal" :maxWidth="'md'">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="flex-column">
                        <span class="text-xs my-0 color-6-red"> Currently Editing </span>
                        <h5 class="modal-title" v-if="specificIngredientData != null"> {{ specificIngredientData.name }}</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetEditForm()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row" >
                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Category</p>
                            <p>{{ Category.name }}</p>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Feed Name </p>
                             <div class="invalid-checked" v-if="errors.edit">{{ errors.edit.name }}</div>
                            <input type="text" v-model="editSpecificIngredientForm.name" class="form-control rounded-0">
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> DM </p>
                            <div class="invalid-checked" v-if="errors.edit">{{ errors.edit.dm }}</div>
                            <input type="text" v-model="editSpecificIngredientForm.dm" class="form-control rounded-0">
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> ME </p>
                            <div class="invalid-checked" v-if="errors.edit">{{ errors.edit.me }}</div>
                            <input type="text" v-model="editSpecificIngredientForm.me" class="form-control rounded-0">
                        </div>
                        
                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> TDN </p>
                            <div class="invalid-checked" v-if="errors.edit">{{ errors.edit.tdn }}</div>
                            <input type="text" v-model="editSpecificIngredientForm.tdn" class="form-control rounded-0">
                        </div>   

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> CP </p>
                            <div class="invalid-checked" v-if="errors.edit">{{ errors.edit.cp }}</div>
                            <input type="text" v-model="editSpecificIngredientForm.cp" class="form-control rounded-0">
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> NDF </p>
                            <div class="invalid-checked" v-if="errors.edit">{{ errors.edit.ndf }}</div>
                            <input type="text" v-model="editSpecificIngredientForm.ndf" class="form-control rounded-0">
                        </div>            

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> CA </p>
                            <div class="invalid-checked" v-if="errors.edit">{{ errors.edit.ca }}</div>
                            <input type="text" v-model="editSpecificIngredientForm.ca" class="form-control rounded-0">
                        </div>   

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> P </p>
                            <div class="invalid-checked" v-if="errors.edit">{{ errors.edit.p }}</div>
                            <input type="text" v-model="editSpecificIngredientForm.p" class="form-control rounded-0">
                        </div>                    

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Min </p>
                            <div class="invalid-checked" v-if="errors.edit">{{ errors.edit.min }}</div>
                            <input type="text" v-model="editSpecificIngredientForm.min" class="form-control rounded-0">
                        </div>   

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Max </p>
                            <div class="invalid-checked" v-if="errors.edit">{{ errors.edit.max }}</div>
                            <input type="text" v-model="editSpecificIngredientForm.max" class="form-control rounded-0">
                        </div>                           

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-dismiss="modal" @click="resetEditForm()">Cancel <i class="fas fa-times"></i> </button>
                    <button class="btn btn-outline-primary ml-auto btn-sm rounded-pill" @click="saveEditedIngredient()">Save <i class="fas fa-check"></i></button>
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
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import Swal from 'sweetalert2'

    export default {
        components: {
            AdminLayout,
            Modal,
            Pagination,
            Swal,
        },
        props:['UsersDetails','Ingredients', 'Category', 'errors', 'Filters', 'UserRoles'],
        data()  {
            return {
                params:{
                    field: this.Filters.field,
                    direction: this.Filters.direction, 
                },
                editor: ClassicEditor,
                ingredientsModal: 'ingredientsModal',
                createIngredientModal: 'createIngredientModal',
                createIngredientForm: this.$inertia.form({
                    name: '',
                    dm: 0.0,
                    me: 0.0,
                    cp: 0.0,
                    ndf: 0.0,
                    tdn: 0.0,
                    ca: 0.0,
                    p: 0.0,
                    min: 0.0,
                    max: 0.0,
                    category_id: this.Category.id,
                }),
                specificIngredientData:null,
                editSpecificIngredientForm: this.$inertia.form({
                    category_id: this.Category.id,
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.AdminIngredients', {category_id: this.Category.id, page: this.Ingredients.current_page}), params, {replace: true, preserveState: false});

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
            createIngredient(){
                console.log(this.createIngredientForm);
				this.$inertia.post(route(this.hasValidRole(this.UserRoles)+'.CreateIngredient'), this.createIngredientForm, {
                    forceFormData: true,
 					onSuccess: (res) => {
                        console.log("onSuccess");            
                        $('#createIngredientModal').modal('hide');
                        
                        //Success info (moved to onsuccess)
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your created ingredient has been saved successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });

                        this.createIngredientForm.reset();
                        this.$inertia.reload();
                        
                     }
				}).then(function (response) {
                    //Do nothing
            	}.bind(this));
            },
            sort(field){
                this.params.field = field;
                this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
            },
            resetCreateForm(){

                this.errors.add = null;
                this.createIngredientForm.reset();
            },
            resetEditForm(){
                this.errors.edit = null;
            },
            editSpecificIngredient(feed){
                
                console.log("Clicked Edit");

                this.specificIngredientData = feed;
                this.editSpecificIngredientForm = JSON.parse(JSON.stringify(feed));
                

                // Copy properties from specificIngredient to editIngredientSymptomForm
                //this.editSpecificIngreidentForm = JSON.parse(JSON.stringify(this.specificIngredientData));
            },
            saveEditedIngredient(){
                console.log(this.editSpecificIngredientForm);
				this.$inertia.post(route(this.hasValidRole(this.UserRoles)+'.SaveEditedIngredient'), this.editSpecificIngredientForm, {
					forceFormData: true,
 					onSuccess: (res) => {
                         $('#ingredientsModal').modal('hide');
                         
                         //Success info (moved to onsuccess)
                         Swal.fire({
                            title: 'Success!',
                            text: 'Your edited ingredient has been saved successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });console.log("onSuccess");

                        this.$inertia.reload();
                     }
				}).then(function (response) {
					
            	}.bind(this));
			},
        },
        created:function(){
            console.log("UserRoles");
            console.log(this.UserRoles);
        }
        
    }

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
