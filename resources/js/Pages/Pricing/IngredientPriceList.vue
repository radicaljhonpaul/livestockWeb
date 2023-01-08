<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">
                <div class="row px-3">

                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <inertia-link :href="route(this.UserRoles[0]+'.AdminPricing')" class="color-6-red" :active="route().current('pcc_admin.AdminPricing')">Pricing</inertia-link>
                            </li>
                        </ol>
                    </div>


                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            {{ SrpYear.year }} Pricelist
                        </h5>
                    </div>

                </div>


                <!--Feeds table-->
                <div class="row px-3 justify-content-start">
                    <div class="col-12 shadow rounded bg-light">
                        <table class="table text-sm">
                            <thead>
                                <tr class="color-6-red">
                                    <th class="pt-3 pb-1 border-0">Category</th>
                                    <th class="pt-3 pb-1 border-0">Feed Name</th>
                                    <th class="pt-3 pb-1 border-0">Price</th>
                                </tr>
                            </thead>
                    
                            <tbody>
                                <tr class="color-6-red" v-for="IngredientPrice in IngredientSrpYears.data" :key="IngredientPrice">
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ IngredientPrice.category_name }}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ IngredientPrice.name }}</td>
                                   <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ IngredientPrice.price }}</td>
                                </tr>
                            </tbody>
                            
                        </table>
                    </div>

                    <div class="mt-2 pl-0" :class="{ 'col-10':  this.hasValidRole(this.UserRoles) == false, 'col-8': this.hasValidRole(this.UserRoles) == true }">
                        <pagination :links="IngredientSrpYears.links" :current_page="IngredientSrpYears.current_page" :prev_url="IngredientSrpYears.prev_page_url" :next_url="IngredientSrpYears.next_page_url" :total_page="IngredientSrpYears.last_page" :path="IngredientSrpYears.path"></pagination>
                    </div>

                    <div class="col-2 mt-2 mr-auto pr-0">
                        <a v-bind:href="'/'+this.UserRoles[0]+'/DownloadPriceBySrp?srpyear_id=' + SrpYear.id +'&srp_name='+SrpYear.year"  class="btn btn-outline-brown btn-sm w-100" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">
                        Download Pricelist  <i class="fas fa-file-download"></i>
                        </a>
                    </div>

                    <div class="col-2 mt-2 mr-auto pr-0" v-if="this.UserRoles.includes('Admin') || this.UserRoles.includes('IFodderContentManager')">
                        <button  class="btn btn-outline-brown btn-sm w-100" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;" data-toggle="modal" data-target="#uploadPricelist" >
                            Upload Pricelist  <i class="fas fa-file-upload"> </i>
                        </button>
                    </div>
                </div>   
            </section>

        <!-- Upload Pricelist -->
		<modal :id="uploadPricelist" :maxWidth="'md'">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title color-6-red"> Upload Pricelist for <b>{{ SrpYear.year }}</b> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetUpload()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" @submit.prevent="UploadPricelist">
                    <div class="row">
                            <div class="col-12 mb-2 px-0">
                                <jet-validation-errors class="mb-3"/>
                                
                                <div v-if="fileUploadErr != null" class="alert alert-danger mb-3 rounded-0 text-xs" role="alert">
                                    {{ fileUploadErr }}
                                </div>

                                <div class="custom-file">
                                    <input type="file" ref="fileupload" class="custom-file-input" @change="handleExcelFile" @click="resetUpload" id="customFileLang">
                                    <label class="custom-file-label" for="customFileLang" v-html="csv_filename"></label>
                                </div>
                                <!-- <input type="file" class="form-control-file" @change="handleExcelFile()" id="exampleFormControlFile1"> -->
                            </div>
                    </div>

                    <div class="row">
                        <button class="btn btn-outline-secondary btn-sm rounded-pill" data-dismiss="modal" type="button" @click="resetUpload()">Cancel <i class="fas fa-redo"></i> </button>
                        <button type="submit" :disabled="showUploadBtn == false" class="btn btn-outline-brown btn-sm ml-auto" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">
                            Upload Pricelist  <i class="fas fa-file-upload"> 
                        </i></button>
                    </div>
                    </form>
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
    import JetValidationErrors from '@/Jetstream/ValidationErrors'


    export default {
        components: {
            AdminLayout,
            Pagination,
            Modal,
            Swal,
            JetValidationErrors,
        },
        props:['UsersDetails','IngredientSrpYears', 'SrpYear', 'UserRoles'],
        data()  {
            return {
                uploadPricelist: 'uploadPricelist',
                UploadPricelistForm: this.$inertia.form({
                    csv_file: null,
                    srpyear_id: null,
                }),
                csv_filename: null,
                showUploadBtn:false,
                fileUploadErr: null,
            }
        },
        methods:{
            hasValidRole(roles_arr){
                console.log("roles_arr");
                var hasIFodderContentManager = roles_arr.includes('IFodderContentManager');
                var hasAdmin = roles_arr.includes('Admin');
                if(hasIFodderContentManager === true){
                    return true;
                }else if(hasAdmin === true){
                    return true;
                }else{
                    return false;
                }
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
            UploadPricelist(){
                // v-bind:href="'/'+this.UserRoles[0]+'/UploadPriceBySrp'"
                this.UploadPricelistForm.post(this.route(this.hasIfodderRole(this.UserRoles)+'.UploadPriceBySrp'), {

                    onSuccess: (res) => {
                        console.log(res);
                        Swal.fire({
                            title: 'Success!',
                            text: 'Pricelist Uploaded Successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                        // reset form
                        this.resetUpload();
                         $('#uploadPricelist').modal('hide');
                    },
                    onError: errors => {
                        this.showUploadBtn = false;
                        this.fileUploadErr = null;
                    console.log("this.$page.props.errors");
                    console.log(this.$page.props.errors);
                    },
                })
            },
            handleExcelFile(uploaded){
                var fileObj = uploaded.target.files;
                // this.$page.props.errors = {};
                // console.log(fileObj[0].name);
                // console.log(this.csv_filename);
                this.UploadPricelistForm.csv_file = '';
                if(fileObj[0].name == this.csv_filename){
                    console.log("same file");
                }
                if(this.$page.props.errors != null){
                    console.log(" handleExcelFile -- this.$page.props.errors");
                    console.log(this.$page.props.errors);
                }

                if(fileObj.length > 0){
                    let filename = fileObj[0].name;
                    var ext = filename.split('.').pop()
                    if(ext == 'csv'){
                        this.showUploadBtn = true;
                        this.fileUploadErr = null;
                        this.UploadPricelistForm.csv_file = fileObj;
                        this.UploadPricelistForm.srpyear_id = this.SrpYear.id;
                        this.csv_filename = filename;
                        console.log(fileObj);
                    }else{
                        this.showUploadBtn = false;
                        this.csv_filename = 'File not supported';
                        this.fileUploadErr = "Please use CSV file to upload";
                    }
                }else{
                    this.fileUploadErr = "No attached file";
                    this.showUploadBtn = false;
                    this.csv_filename = 'No attached file, Select a file to upload';
                }
            },
            resetUpload(){
                console.log("fileObj");
                this.$refs.fileupload.value=null;
                this.UploadPricelistForm.reset();
                this.csv_filename = 'Select a file to upload';
                this.showUploadBtn = false;
                this.$page.props.errors = '';
                console.log(this.$page.props.errors);
            },
        },
        created:function() {
            // if(this.csv_filename == null || this.csv_filename == ''){
                this.csv_filename = 'Select a file to upload';
            // }
        }
        
    }
</script>
