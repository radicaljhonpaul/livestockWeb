<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent" v-if="this.FarmerDetails.length > 0">
            <section class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <inertia-link :href="this.PrevUrl" class="" :active="route().current(this.UserRoles[0]+'.AdminFarmers')"> <span class="color-5-orange">Farmers List</span> </inertia-link>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <inertia-link href="#">
                                    <span class="color-5-orange"> Farmers Profile </span>
                                </inertia-link>
                            </li>
                        </ol>
                    </div>
                    <!-- Symptoms List -->
                    <div class="col-2 text-right ml-auto pr-3">
                        
                        <button class="btn btn-outline-brown btn-sm" data-toggle="modal" data-target="#addLivestocksModal" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">
                            Add Livestock &nbsp; <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="row ml-2 mt-3 mb-2 bg-color-1-yellow py-3 pl-2" style="border-radius:25px;">
                            <div class="col-2 py-3 text-center align-middle image-parent">
                                <img class="img-fluid img-circle" src="/images/default-profile-icon.png" style="height:100px; object-fit:cover;" alt="User Image">
                            </div>

                            <div class="col-10">
                                <p class="text-lg mb-0 font-weight-bold d-block">{{ FarmerDetails[0].last_name +', '+ FarmerDetails[0].first_name }}  <a v-if="this.roleExemption(this.UserRoles) == true" @click="editFarmerProfile(FarmerDetails)" data-toggle="modal" data-target="#editFarmerProfileModal" href="#" class=""> <i class="fas fa-edit color-6-red cursor-pointer"></i> </a> </p>
                                <div class="row">
                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Barangay, Municipality/City
                                        </label>
                                        <p class="my-0 text-sm d-block">
                                            {{ FarmerDetails[0].admin_level_three.name +', '+ FarmerDetails[0].admin_level_three.admin_level_two.name }}
                                        </p>

                                        <label class="text-sm mb-0 pb-0">
                                            Province
                                        </label>
                                        <p class="my-0 text-sm d-block">
                                            {{ FarmerDetails[0].admin_level_three.admin_level_two.admin_level_one.name }}
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Birthdate
                                        </label>
                                        <p class="my-0 text-sm d-block">
                                            {{ this.dateFormat(FarmerDetails[0].birthdate) }}
                                        </p>

                                        <label class="text-sm mb-0 pb-0">
                                            Gender
                                        </label>
                                        <p class="my-0 mb-0 text-sm d-block" v-if="FarmerDetails[0].gender == 'F'">
                                            Female
                                        </p>
                                        <p class="my-0 mb-0 text-sm d-block" v-if="FarmerDetails[0].gender == 'M'">
                                            Male
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Mobile Number
                                        </label>
                                        <p class="my-0 mb-0 text-sm d-block">
                                            <span v-if="FarmerDetails[0].mobile_number != ''">
                                                {{ FarmerDetails[0].mobile_number }}
                                            </span>
                                            <span v-if="FarmerDetails[0].mobile_number == ''">
                                                &nbsp;
                                            </span>
                                        </p>

                                        <label class="text-sm mb-0 pb-0">
                                            Phone Number
                                        </label>
                                        <p class="my-0 mb-0 text-sm d-block">
                                            <span v-if="FarmerDetails[0].phone_number != ''">
                                                {{ FarmerDetails[0].phone_number }}
                                            </span>
                                            <span v-if="FarmerDetails[0].phone_number == ''">
                                                &nbsp;
                                            </span>
                                        </p>
                                    </div>
                                    
                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            GPS
                                        </label>
                                        <p class="my-0 mb-0 text-sm d-block">
                                            {{ "Long: " + FarmerDetails[0].longitude +", Lat: "+ FarmerDetails[0].lat }}
                                        </p>

                                        <label class="text-sm mb-0 pb-0">
                                            FB Profile Id
                                        </label>
                                        <p class="my-0 mb-0 text-sm d-block">
                                            {{ FarmerDetails[0].fb_profile }}
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Program Consent
                                        </label>
                                        <p class="my-0 mb-0 text-sm d-block">
                                            <span v-if="FarmerDetails[0].program_consent == '0' || FarmerDetails[0].program_consent == 0">
                                                No
                                            </span>
                                            <span v-if="FarmerDetails[0].program_consent == '1' || FarmerDetails[0].program_consent == 1">
                                                Yes - {{ this.dateFormat(FarmerDetails[0].program_consent_date) }}
                                            </span>
                                        </p>

                                        <label class="text-sm mb-0 pb-0">
                                            SMS Consent
                                        </label>
                                        <p class="my-0 mb-0 text-sm d-block">
                                            
                                            <span v-if="FarmerDetails[0].sms_consent == '0' || FarmerDetails[0].sms_consent == 0">
                                                No
                                            </span>
                                            <span v-if="FarmerDetails[0].sms_consent == '1' || FarmerDetails[0].sms_consent == 1">
                                                Yes - {{ this.dateFormat(FarmerDetails[0].sms_consent_date) }}
                                            </span>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row ml-2 mb-2  py-3 ">
                            <h6 class="color-6-red"> Carabao List </h6>
                            <table class="table table-sm text-sm">
                                <thead>
                                    <tr class="color-6-red">
                                    <th class="pt-3 pb-1 border-0" scope="col">Carabao Code</th>
                                    <th class="pt-3 pb-1 border-0" scope="col">Breed</th>
                                    <th class="pt-3 pb-1 border-0" scope="col">Sex</th>
                                    <th class="pt-3 pb-1 border-0" scope="col">Year of Birth</th>
                                    <th class="pt-3 pb-1 border-0" scope="col">Date of Registration</th>
                                    <th class="pt-3 pb-1 border-0" scope="col">Pregnant</th>
                                    <th class="pt-3 pb-1 border-0" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="color-6-red" v-for="livestock in FarmerDetails[0].livestocks" :key="livestock">
                                        <td class="text-wrap text-sm" style="max-width: 150px;" v-html="livestock.carabao_code"></td>
                                        <td class="text-wrap text-sm" style="max-width: 150px;">
                                            <p class="my-0 mb-0 text-sm d-block" v-if="livestock.breed == '' || livestock.breed == null ">
                                                
                                            </p>
                                            <p class="my-0 mb-0 text-sm d-block" v-else>
                                                {{ livestock.breed }}
                                            </p>
                                        </td>
                                        <td class="text-wrap text-sm" style="max-width: 150px;">
                                            <p class="my-0 mb-0 text-sm d-block" v-if="livestock.sex == 'F'">
                                                Female
                                            </p>
                                            <p class="my-0 mb-0 text-sm d-block" v-if="livestock.sex == 'M'">
                                                Male
                                            </p>
                                        </td>
                                        <td class="text-wrap text-sm" style="max-width: 150px;" v-html="livestock.year_of_birth"></td>
                                        <td class="text-wrap text-sm" style="max-width: 150px;" v-html="this.dateFormat(livestock.registration_date)"></td>
                                        <td class="text-wrap text-sm" style="max-width: 150px;">
                                            <p class="my-0 mb-0 text-sm d-block" v-if="livestock.is_pregnant == 1">
                                                Pregnant    
                                            </p>
                                            <p class="my-0 mb-0 text-sm d-block" v-if="livestock.is_pregnant == 0">
                                                Not Pregnant
                                            </p>
                                        </td>
                                        <td class="text-wrap text-sm" style="max-width: 150px;">
                                            <a @click="viewSpecificLivestock(livestock.id, this.PrevUrl)" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm"> View <i class="fas fa-eye"></i> </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- Search,Calendar,Recents -->
                    <div class="col-4">
                        <div class="row mx-3 mt-3 mb-2 px-2 pt-2 pb-3">
                            <!-- Calendar -->
                            <div class="col-12 bg-color-1-yellow px-0">
                                <label class="color-6-red px-1">Pregnancy Dates</label>
                                <Calendar class="color-6-red rounded-0"
                                is-expanded
                                title-position="right"
                                :columns="$screens({ default: 1, xs: 1 })"
                                :attributes="this.attrs"
                                
                                />
                            </div>
                        </div>

                        <div class="row mx-3 mt-3 mb-2 shadow-sm px-2 pt-2 pb-3">
                            <div class="col-12">
                                <label class="my-2 color-6-red">Recent Activity</label>
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action" v-for="visit_log in visitLogs" :key="visit_log" aria-current="true" @click="viewSpecificLivestock(visit_log.livestock_id, this.PrevUrl)">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="mb-0 text-xs mr-auto">
                                                {{ this.dateFormat(visit_log.visit_date) }}
                                            </p>
                                            <span class="badge badge-pill ml-auto badge-custom-inverted" v-if="visit_log.diagnosis_log_id != null">Health Visit</span>
                                            <span class="badge badge-pill ml-auto badge-custom-yellow" v-if="visit_log.feeding_log_id != null">Feeding Visit</span>
                                        </div>
                                        <p class="text-primary text-xs mb-0">Carabao Code</p>
                                        <p class="my-0 text-xs"> {{ visit_log.carabao_code }} </p>
                                    </a>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </section>
        </div>

        <div class="content-wrapper" v-if="this.FarmerDetails.length == 0">
            <section class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2>FARMER NOT FOUND</h2>
                    </div>
                </div>
            </section>
        </div>

        <!-- Viewing Modal-->
        <modal :id="editFarmerProfileModal" :maxWidth="'md'">
            <div class="modal-content">
                <form @submit.prevent="SaveEditedFarmerProfile">

                    <div class="modal-header flex-column border-0">
                        <div class="container px-0">
                            <div class="row justify-content-start">
                                <div class="col-10 mb-0">
                                    <h4 class="my-0">Edit Farmer Profile</h4>
                                </div>

                                <div class="col-2 mb-0">
                                    <button @click="reseteditFarmerProfileForm()" type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">                             
                            <div class="col-12">

                                <jet-validation-errors class="mb-3" />
                                <div v-if="status" class="alert alert-success mb-3 rounded-0" role="alert">
                                    {{ status }}
                                </div>
      
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Program Consent </p>
                                    <select name="" id="" class="border-radius-10rem form-control" v-model="editFarmerProfileForm.program_consent" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> SMS Consent </p>
                                    <select name="" id="" class="border-radius-10rem form-control" v-model="editFarmerProfileForm.sms_consent" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Mobile Number </p>
                                    <input type="text" class="border-radius-10rem form-control" @change="regexMobileNumber(editFarmerProfileForm.mobile_number)" v-model="editFarmerProfileForm.mobile_number" required>
                                    <span class="text-xs text-success" v-if="this.mobilePrompt == true"> Mobile Number is valid. </span>
                                    <span class="text-xs text-danger" v-if="this.mobilePrompt == false"> Mobile Number is invalid. Not allowed characters "a-z, A-Z or !@#\$%\^\&*\)\(+=._-"  </span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Phone Number </p>
                                    <input type="text" class="border-radius-10rem form-control" @change="regexPhoneNumber(editFarmerProfileForm.phone_number)" v-model="editFarmerProfileForm.phone_number">
                                    <span class="text-xs text-success" v-if="this.phonePrompt == true"> Phone Number is valid. </span>
                                    <span class="text-xs text-danger" v-if="this.phonePrompt == false"> Phone Number is invalid. Not allowed characters "a-z, A-Z or !@#\$%\^\&*\)\(+=._-"  </span>
                                </div>
                            </div>

                            <div class="col-12">

                                <div class="form-group">
                                <p class="text-sm font-weight-normal color-6-red mb-0"> FB Profile ID </p>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="border-top-left-radius: 10rem;border-bottom-left-radius: 10rem;" id="basic-addon3">https://www.facebook.com/</span>
                                    </div>
                                    <input type="text" style="border-top-right-radius: 10rem;border-bottom-right-radius: 10rem;" class="form-control font-weight-bold" id="fb_profile" v-model="editFarmerProfileForm.fb_profile">
                                </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="reseteditFarmerProfileForm()" type="button" class="btn btn-outline-secondary btn-sm rounded-pill">Cancel <i class="fas fa-redo"></i> </button>
                        <button type="submit" class="btn btn-outline-red ml-auto btn-sm rounded-pill" :disabled="this.phonePrompt == false || this.mobilePrompt == false || !this.editFarmerProfileForm" > Save <i class="fas fa-save"></i></button>
                    </div>
                </form>
            </div>
        </modal>


        <!-- Viewing Modal-->
        <modal :id="addLivestocksModal" :maxWidth="'md'">
            <div class="modal-content">
                <form @submit.prevent="SaveCreatedLivestock">

                    <div class="modal-header flex-column border-0">
                        <div class="container px-0">
                            <div class="row justify-content-start">
                                <div class="col-10 mb-0">
                                    <h4 class="my-0">Create Livestock</h4>
                                </div>

                                <div class="col-2 mb-0">
                                    <button @click="resetLivestockForm()" type="button" class="close" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">                             
                            <div class="col-12">
                                <jet-validation-errors class="mb-3" />
                                <div v-if="status" class="alert alert-success mb-3 rounded-0" role="alert">
                                    {{ status }}
                                </div>
      
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Carabao Code </p>
                                    <input type="text" class="border-radius-10rem form-control" v-model="createLivestockForm.carabao_code" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Breed </p>
                                    <select name="" id="" class="border-radius-10rem form-control" v-model="createLivestockForm.breed">
                                        <option value="RV">RV</option>
                                        <option value="CB">CB</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Sex </p>
                                    <select name="" id="" class="border-radius-10rem form-control" v-model="createLivestockForm.sex" required>
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Year of Birth </p>
                                    <input type="number" class="border-radius-10rem form-control" min="1900" step="1" v-model="createLivestockForm.year_of_birth">
                                </div>
                            </div>



                            <div class="col-12">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Registration Date </p>
                                    <input type="date" class="border-radius-10rem form-control" v-model="createLivestockForm.registration_date" required>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex">
                            <span class="text-xs color-6-red">
                                WARNING: Only add livestock which has been officially registered and has an ear tag. You will not be allowed to edit this record after adding, so please ensure correctness.
                            </span>
                        </div>

                        <button @click="resetLivestockForm()" type="button" class="btn btn-sm btn-outline-secondary rounded-pill" data-dismiss="modal" aria-label="Close">
                            Cancel <i class="fas fa-times-circle"></i>
                        </button>
                        
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
    import Swal from 'sweetalert2'
    import Pagination from '@/CustomComponents/GeneralComp/Pagination.vue'
    import moment from 'moment';
    import JetValidationErrors from '@/Jetstream/ValidationErrors'
    
    export default {
        components: {
            AdminLayout,
            Modal,
            Pagination,
            Swal,
            JetValidationErrors,
        },
        props: {
            status: String
        },
        props: [ 'UsersDetails', 'FarmerDetails', 'Filters', 'PrevUrl', 'UserRoles'],
        data() {
            return {
                editFarmerProfileModal: 'editFarmerProfileModal',
                addLivestocksModal: 'addLivestocksModal',
                params:{
                    searchLivestock: this.Filters.searchLivestock,
                    field: this.Filters.field,
                    direction: this.Filters.direction,
                },
                specificLivestock: [],
                editSpecificLivestock: false,
                pregnancyDatesArr: [],
                attrs: [],
                visitLogs: [],
                editFarmerProfileForm: this.$inertia.form({
                    program_consent: '',
                    sms_consent: '',
                    mobile_number: '',
                    phone_number: '',
                    fb_profile: '',
                }),
                mobilePrompt: null,
                phonePrompt: null,
                createLivestockForm: this.$inertia.form({
                    carabao_code: '',
                    breed: '',
                    sex: '',
                    year_of_birth: '',
                    registration_date: '',
                    farmer_id: this.FarmerDetails[0].id,
                }),
            }
        },
        methods: {
            viewSpecificLivestock(livestock_id, prev_url){
                console.log(prev_url);
                this.$inertia.get(route(this.UserRoles[0]+'.SpecificLivestock'),
                {
                    livestock_id: livestock_id,
                    prev_url : prev_url
                },
                { replace: true })
            },
            pregnancyDates(pregnancy_obj){
                console.log("pregnancy_obj");
                // console.log(pregnancy_obj);
                var data_attrs = [];
                var visit_logs = [];

                pregnancy_obj.forEach(element => {
                    // console.log(element);
                    var eachPregnancies = element.pregnancies;
                    eachPregnancies.forEach(pregnancies => {
                        console.log("pregnancies");
                        console.log();
                        
                        var label_popover = pregnancies.carabao_code +"\n"+ pregnancies.description;
                        data_attrs.push({
                            highlight:{
                                color:'yellow',
                                fillMode: 'light',
                            },
                            dates: pregnancies.start_date,
                            popover: {
                             label: label_popover,
                            },
                        });
                        data_attrs.push({
                            highlight:{
                                color:'red',
                                fillMode: 'light',
                            },
                            dates: pregnancies.end_date,
                            popover: {
                             label: label_popover,
                            },
                        });

                    });


                    var eachVisitLogs = element.visit_logs;
                    eachVisitLogs.forEach(VisitLogs => {
                        // console.log(VisitLogs);
                        visit_logs.push(VisitLogs);
                    });
                    
                });
                // console.log("Unsorted");
                // console.log(visit_logs);
                
                visit_logs.sort(function (a, b) {
                    var dateA = new Date(a.visit_date), dateB = new Date(b.visit_date)
                    return dateB - dateA
                });
                // console.log("Sorted");
                console.log(visit_logs);
                // console.log(visit_logs.slice(Math.max(visit_logs.length - 5, 0)));

                // arr.slice(Math.max(arr.length - 5, 0))
                this.visitLogs = visit_logs.slice(Math.max(visit_logs.length - 5, 0));
                this.attrs = data_attrs;
                // this.pregnancyDatesArr
            },
            regexPhoneNumber(str){
                var regex = /^\d+$/;
                var result;
                if(str == ''){
                    this.phonePrompt = null;
                }

                if(str) {
                    if(str.match(regex)){
                        console.log("ok");
                        this.editFarmerProfileForm.phone_number = str;
                        this.phonePrompt = true;
                    }else{
                        console.log("not ok");
                        this.editFarmerProfileForm.phone_number = str;
                        this.phonePrompt = false;
                    }
                }
            },
            regexMobileNumber(str) {
                var regex1 = /^[^0][a-zA-Z!@#\$%\^\&*\)\(+=._-]+$/gm;
                var regex2 = /^(9)\d{9}$/g;
                var result;
                
                if(str) {
                    if(str.match(regex1)){
                        console.log("char");
                        // result = str.replace(regex1, '');
                        this.editFarmerProfileForm.mobile_number = result;
                        this.mobilePrompt = false;
                    }else if(str.match(regex2)){
                        console.log(str);
                        this.editFarmerProfileForm.mobile_number = str;

                        this.mobilePrompt = true;
                    }else{
                        this.editFarmerProfileForm.mobile_number = str;
                        // this.editFarmerProfileForm.mobile_number = '';
                        this.mobilePrompt = false;
                    }
                }
                
            },
            reseteditFarmerProfileForm(){
                $('#editFarmerProfileModal').modal('hide');
                this.editFarmerProfileForm.reset();
            },           
            editFarmerProfile(farmer_details){
                this.editFarmerProfileForm = JSON.parse(JSON.stringify(farmer_details[0]));
                this.regexPhoneNumber(this.editFarmerProfileForm.phone_number);
                this.regexMobileNumber(this.editFarmerProfileForm.mobile_number);

                this.editFarmerProfileForm.fb_profile = this.editFarmerProfileForm.fb_profile.replace("https://www.facebook.com/", "");
                console.log("editFarmerProfileForm");
            },
            resetLivestockForm(){
                $('#addLivestocksModal').modal('hide');
                // set errors to empty object
                this.$page.props.errors = {};
                this.createLivestockForm.reset();
            },
            SaveCreatedLivestock(){
                this.$inertia.post(route(this.UserRoles[0]+'.SaveCreatedLivestock'), this.createLivestockForm, {
                    forceFormData: true,
                    onSuccess: (res) => {
                        $('#addLivestocksModal').modal('hide');
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your livestock been saved successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                        console.log("onSuccess");
                        this.createLivestockForm.reset();
                        console.log($page.props);
                        // $page.props
                    },
                    onError: (errors) => {
                        console.log(errors);
                        
                        console.log("onError");
                    },
                    onFinish: () => {
                        console.log("onFinish");
                    },
                }).then(function (response) {
                }.bind(this));
            },
            SaveEditedFarmerProfile(){
                var fb = "https://www.facebook.com/";
                if(this.editFarmerProfileForm.fb_profile != null && this.editFarmerProfileForm.fb_profile != ''){
                    this.editFarmerProfileForm.fb_profile = fb+this.editFarmerProfileForm.fb_profile;
                }
                console.log(this.editFarmerProfileForm.facebook_profile);
                if(this.editFarmerProfileForm.mobile_number == null){
                    console.log("null");
                }else{
                    console.log("not null");
                }
                $('#fb_profile').attr('hidden', true);

                this.$inertia.post(route(this.UserRoles[0]+'.SaveEditedFarmerProfile'), this.editFarmerProfileForm, {
                    forceFormData: true,
                    onSuccess: (res) => {
                        $('#editFarmerProfileModal').modal('hide');
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your farmer profile content has been saved successfully.',
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

                        this.editFarmerProfileForm.fb_profile = this.editFarmerProfileForm.fb_profile.replace("https://www.facebook.com/", "");

                    },
                    onFinish: () => {
                        console.log("onFinish");
                        $('#fb_profile').removeAttr('hidden', 'hidden');
                        console.log(this.editFarmerProfileForm);
                    },
                }).then(function (response) {
                }.bind(this));
            },
            dateFormat(date_data){
                if(moment(date_data).format('MMM DD YYYY') == 'Invalid date'){
                    return "No data";
                }else{
                    return moment(date_data).format('MMM DD YYYY');
                }
            },
            dateTimeFormat(date_data){
                if(date_data == "0000-00-00" || date_data == null){
                    return "No Data";
                }else{
                    return moment(date_data).format('MMM DD YYYY, h:mm:ss a');
                }
            },
            getInitials(name){
                console.log(name);
                var initials = name.split(' ');
                
                if(initials.length > 1) {
                    initials = initials.shift().charAt(0) + initials.pop().charAt(0);
                } else {
                    initials = name.substring(0, 1);
                }
                
                return initials.toUpperCase();
            },
            roleExemption(roles_arr){
                // Customize this arrqay according to requirement
                var rolesArr = ['Vet', 'VetAide', 'Admin'];
                console.log("roles_arr");
                // console.log(roles_arr);
                // console.log($.inArray('ReportsUser',roles_arr));
                    var value = 0;
                    roles_arr.forEach(function(role){
                        console.log("role");
                        console.log(role);
                        value = value + rolesArr.includes(role);
                    });
                    return (value === 1)
                return roles_arr.includes('Vet', 'ReportsUser', 'VetAide', 'Admin');
            },
        },
        created:function(){
            console.log("FarmerDetails");
            this.pregnancyDates(this.FarmerDetails[0].livestocks);
            console.log("PrevUrl");
            console.log(this.PrevUrl);
        }
    }
</script>
