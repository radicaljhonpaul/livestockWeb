<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent" v-if="this.LivestockDetails.data.length > 0">
            <section class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <a class="cursor-pointer" @click="viewSpecificFarmer(LivestockDetails.data[0].farmer_id, this.Prev_Url)"> <span class="color-5-orange"> Farmers Profile </span> </a>
                                <!-- <inertia-link :href="'/'+this.UserRoles[0]+'/SpecificFarmer?farmer_id='+LivestockDetails.data[0].farmer_id" class="" :active="route().current('pcc_admin.AdminFarmers')"> <span class="color-5-orange"> Farmers Profile </span> </inertia-link> -->
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <inertia-link href="#">
                                    <span class="color-5-orange"> Livestock Profile </span>
                                </inertia-link>
                            </li>
                        </ol>
                    </div>
                    <div class="col ml-3">
                        <h2 class="h4 font-weight-bold">
                            Visit Logs
                        </h2>

                        <div class="user-panel mt-3 pb-3 mb-2 d-flex">
                            <div class="image">
                                <img src="/images/default-profile-icon.png" style="width:50px;height:50px;object-fit:cover;border: 2px; border-color:#eee;" class="img-circle border-primary" alt="User Image">
                            </div>
                            <div class="info">
                                <label class="text-sm mb-0 pb-0">
                                    Carabao Code
                                </label>
                                <span class="text-lg color-6-red d-block text-wrap" style="text-transform:none;">{{ LivestockDetails.data[0].carabao_code }}</span>
                                
                                <div class="row">
                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Breed
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary">
                                            {{ LivestockDetails.data[0].breed }}
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Sex
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary" v-if="LivestockDetails.data[0].sex == 'F'">
                                            Female
                                        </p>
                                        <p class="my-0 text-sm d-block text-secondary" v-if="LivestockDetails.data[0].sex == 'M'">
                                            Male
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Year of Birth
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary">
                                            {{ LivestockDetails.data[0].year_of_birth }}
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Date of Registration
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary">
                                            {{ this.dateFormat(LivestockDetails.data[0].registration_date) }}
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Pregnant
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary" v-if="LivestockDetails.data[0].is_pregnant == 1">
                                            Currently Pregnant
                                        </p>
                                        <p class="my-0 text-sm d-block text-secondary" v-if="LivestockDetails.data[0].is_pregnant == 0">
                                            Not Pregnant
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Name of Farmer
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary" >
                                            {{ LivestockDetails.data[0].farmer.last_name +', '+ LivestockDetails.data[0].farmer.first_name }}
                                        </p>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <a v-if="LivestockDetails.data[0].sex == 'F' " @click="viewPregnancyInfo(LivestockDetails.data[0].carabao_code, this.Prev_Url)" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm cursor-pointer">View Pregnancy Info </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Searching -->
                    <div class="col-3 pb-5">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-outline-warning rounded-0" data-toggle="modal" data-target="#createSymptomsModal" style="border:1px solid #ced4da;">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control border-right-0 searching" v-model="params.searchLivestock" placeholder="Search Livestock..." aria-label="Search..." aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-light border-left-0 bg-white" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-right:1px solid #ced4da;">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="row justify-content-start px-2">
                    <div class="col">
                        <table class="table text-sm">
                            <thead>
                                <tr class="color-6-red">
                                    <th role="button" scope="col" class="pt-2 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('visit_date')">
                                            Date
                                            <i v-if="params.searchFarmers === null && params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'visit_date' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'visit_date' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th role="button" scope="col" class="pt-2 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center">
                                            Activity Type
                                        </span>
                                    </th>
                                    <th scope="col" class="pt-2 pb-1 border-0">Staff Name</th>
                                    <th scope="col" class="pt-2 pb-1 border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody class="nomatches" hidden="true" v-if="LivestockDetails.data.length == 0">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <h6 class="text-danger">
                                            No matching results
                                        </h6>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody >
                                <tr class="color-6-red" v-for="livestock_visit_logs in LivestockDetails.data[0].visit_logs" :key="livestock_visit_logs">
                                    <td class="text-wrap text-sm" style="max-width: 150px;" v-html="this.dateFormat(livestock_visit_logs.visit_date)"></td>
                                    <td class="text-wrap text-sm" style="max-width: 150px;">
                                        <span v-if="livestock_visit_logs.diagnosis_log_id != null">Diagnosis</span>
                                        <span v-if="livestock_visit_logs.feeding_log_id != null">Feeding</span>
                                    </td>
                                    <td class="text-wrap text-sm" style="max-width: 150px;" v-html="livestock_visit_logs.assigned_to.last_name+', '+livestock_visit_logs.assigned_to.first_name"></td>
                                    <td class="text-wrap text-sm" style="max-width: 150px;">
                                        <a v-if="livestock_visit_logs.diagnosis_log_id != null" @click="viewSpecificDiagnosis(livestock_visit_logs.diagnosis_log)" data-toggle="modal" data-target="#diagnosisModal" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm">View <i class="fas fa-eye"></i> </a>
                                        <a v-if="livestock_visit_logs.feeding_log_id != null" @click="viewSpecificFeedLog(livestock_visit_logs.feeding_log, livestock_visit_logs.feeding_log.created_by)" data-toggle="modal" data-target="#feedingModal" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm">View <i class="fas fa-eye"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12">
                        <pagination :links="LivestockDetails.links" :current_page="LivestockDetails.current_page" :prev_url="LivestockDetails.prev_page_url" :next_url="LivestockDetails.next_page_url" :total_page="LivestockDetails.last_page" :path="LivestockDetails.path"></pagination>
                    </div>
                </div>
            </section>
        </div>

        <div class="content-wrapper" v-if="this.LivestockDetails.data.length == 0">
            <section class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2>LIVESTOCK NOT FOUND</h2>
                    </div>
                </div>
            </section>
        </div>
                
        <!-- Viewing Diagnosis Modal-->
        <modal :id="diagnosisModal" :maxWidth="'xl'" v-if="this.LivestockDetails.data.length > 0">
            <div class="modal-content">
                
                <div class="modal-header flex-column">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="container px-0">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="font-weight-bolder color-6-red text-sm my-0"> Name of Farmer </h6>
                                <p class="my-0">
                                    {{ LivestockDetails.data[0].farmer.last_name +', '+ LivestockDetails.data[0].farmer.first_name }}
                                </p>
                            </div>
                            <div class="col-6">
                                <h6 class="font-weight-bolder color-6-red text-sm my-0"> Farmer Mobile Number </h6>
                                <span class="my-0" style="word-wrap: break-word;">
                                    {{ LivestockDetails.data[0].farmer.mobile_number }}
                                </span>
                            </div>                            
                        </div>
                    </div>
                </div>

                <div class="modal-body" v-if="specificDiagnosis.livestock" v-cloak>
                    <reports-health-visit-modal-content :specificDiagnosis="specificDiagnosis" ></reports-health-visit-modal-content>
                </div>
            </div>
        </modal>

        <!-- Insert here Viewing Feeding Modal-->
        <modal :id="feedingModal" :maxWidth="'xl'" v-if="this.LivestockDetails.data.length > 0">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="container px-0">
                        <div class="row">

                                <div class="col-6 form-group">
                                    <h6 class="font-weight-bolder color-6-red text-sm my-0"> Name of Farmer (Mobile Number)</h6>
                                    <span class="my-0" style="word-wrap: break-word;">
                                            {{ LivestockDetails.data[0].farmer.last_name +', '+ LivestockDetails.data[0].farmer.first_name }}
                                            ( {{ LivestockDetails.data[0].farmer.mobile_number }} )
                                    </span>
                                </div>

                           <div class="col-6">
                                    <h6 class="font-weight-bolder color-6-red text-sm my-0"> Location </h6>
                                    <span class="my-0" style="word-wrap: break-word;">
                                        {{ LivestockDetails.data[0].farmer.admin_level_three.name +', '+ LivestockDetails.data[0].farmer.admin_level_three.admin_level_two.name +', '+  LivestockDetails.data[0].farmer.admin_level_three.admin_level_two.admin_level_one.name }}
                                    </span>
                            </div>                                 

                            <div class="col-6 form-group">
                                    <h6 class="font-weight-bolder color-6-red text-sm my-0"> Carabao Code</h6>
                                    <span class="my-0" style="word-wrap: break-word;">
                                       {{ LivestockDetails.data[0].carabao_code }}  
                                    </span>
                            </div>

                            <div class="col-6 form-group">
                                    <h6 class="font-weight-bolder color-6-red text-sm my-0"> Carabao Sex </h6>
                                    <span class="my-0" style="word-wrap: break-word;">
                                       {{ LivestockDetails.data[0].sex }}
                                    </span>
                            </div>  

                           

                        </div>
                    </div>
                </div>
                   
                <div class="modal-body">
                    <reports-feed-visit-modal-content :specificFeedlog="specificFeedlog" :specificFeedlogCreatedBy="specificFeedlogCreatedBy"></reports-feed-visit-modal-content>
                </div>

                
            </div>
        </modal>



    </admin-layout>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'
    import Modal from '@/Jetstream/Modal'
    import ReportsHealthVisitModalContent from '@/CustomComponents/ReportsComp/ReportsHealthVisitModalContent.vue'
    import ReportsFeedVisitModalContent from '@/CustomComponents/ReportsComp/ReportsFeedVisitModalContent.vue'
    import Swal from 'sweetalert2'
    import Pagination from '@/CustomComponents/GeneralComp/Pagination.vue'
    import moment from 'moment';
    
    export default {
        components: {
            AdminLayout,
            Modal,
            Pagination,
            Swal,
            ReportsHealthVisitModalContent,
            ReportsFeedVisitModalContent
        },
        props: [ 'UsersDetails', 'LivestockDetails', 'Filters', 'UserRoles', 'Prev_Url' ],
        data() {
            return {    
                params:{
                    searchLivestock: this.Filters.searchLivestock,
                    field: this.Filters.field,
                    direction: this.Filters.direction,
                },
                diagnosisModal: 'diagnosisModal',
                feedingModal: 'feedingModal',
                specificDiagnosis: [],
                specificLivestock: [],
                editSpecificLivestock: false,
                specificFeedlog: [],
                specificFeedlogCreatedBy: [],
            }
        },
        methods: {
            viewPregnancyInfo(carabao_code, prev_url){
                console.log(prev_url);
                this.$inertia.get(route(this.UserRoles[0]+'.PregnancyInfo'),
                {
                    carabao_code: carabao_code,
                    prev_url : prev_url
                },
                { replace: true })
            },
            viewSpecificFarmer(farmer_id, prev_url){
                var currentUrl = window.location.href;
                // this.specificFarmer = farmer_data;
                this.$inertia.get(route(this.UserRoles[0]+'.SpecificFarmer'),
                {
                    farmer_id: farmer_id,
                    prev_url: prev_url,
                },
                { replace: true })
            },
            viewSpecificDiagnosis(diagnosis_data){
                console.log("diagnosis_data");
                this.specificDiagnosis = diagnosis_data;
                console.log(this.specificDiagnosis);
            },
            viewSpecificFeedLog(feed_log, created_by){
                console.log("feeding_data");
                this.specificFeedlog = feed_log;
                this.specificFeedlogCreatedBy = created_by;
                console.log(this.specificFeedlog);
                console.log(this.specificFeedlogCreatedBy);
            },
            yearDateFormat(date_data){
                return moment(date_data).format('YYYY');
            },
            dateFormat(date_data){
                // console.log(date_data);
                if(date_data == "0000-00-00"){
                    return "No Data";
                }else{
                    return moment(date_data).format('MMM DD YYYY');
                }
            },
            dateTimeFormat(date_data){
                return moment(date_data).format('MMM DD YYYY, h:mm:ss a');
            },
        },
        created:function(){
            console.log("this.Prev_Url");
            console.log(this.Prev_Url);
        }
    }
</script>
