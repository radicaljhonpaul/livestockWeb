<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent" v-if="this.PregnancyDetails.data.length > 0">
            <section class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <a class="cursor-pointer" @click="viewSpecificLivestock(PregnancyDetails.data[0].livestock.id, this.Prev_Url)"> <span class="color-5-orange"> Livestock Profile </span> </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <inertia-link href="#">
                                    <span class="color-5-orange"> Pregnancy Info </span>
                                </inertia-link>
                            </li>
                        </ol>
                    </div>

                    <div class="col ml-3">
                        <h2 class="h4 font-weight-bold">
                            Pregnancy Info
                        </h2>

                        <div class="user-panel mt-3 pb-3 mb-2 d-flex">
                            <div class="image">
                                <img src="/images/default-profile-icon.png" style="width:50px;height:50px;object-fit:cover;border: 2px; border-color:#eee;" class="img-circle border-primary" alt="User Image">
                            </div>
                            <div class="info">
                                <label class="text-sm mb-0 pb-0">
                                    Carabao Code
                                </label>
                                <span class="text-lg color-6-red d-block text-wrap" style="text-transform:none;">{{ PregnancyDetails.data[0].livestock.carabao_code }}</span>
                                
                                <div class="row">
                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Breed
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary">
                                            {{ PregnancyDetails.data[0].livestock.breed }}
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Sex
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary" v-if="PregnancyDetails.data[0].livestock.sex == 'F'">
                                            Female
                                        </p>
                                        <p class="my-0 text-sm d-block text-secondary" v-if="PregnancyDetails.data[0].livestock.sex == 'M'">
                                            Male
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Year of Birth
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary">
                                            {{ PregnancyDetails.data[0].livestock.year_of_birth }}
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Date of Registration
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary">
                                            {{ this.dateFormat(PregnancyDetails.data[0].livestock.registration_date) }}
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Pregnant
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary" v-if="PregnancyDetails.data[0].livestock.is_pregnant == 1">
                                            Currently Pregnant
                                        </p>
                                        <p class="my-0 text-sm d-block text-secondary" v-if="PregnancyDetails.data[0].livestock.is_pregnant == 0">
                                            Not Pregnant
                                        </p>
                                    </div>

                                    <div class="col">
                                        <label class="text-sm mb-0 pb-0">
                                            Name of Farmer
                                        </label>
                                        <p class="my-0 text-sm d-block text-secondary" >
                                            {{ PregnancyDetails.data[0].livestock.farmer.last_name +', '+ PregnancyDetails.data[0].livestock.farmer.first_name }}
                                        </p>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Searching -->
                    <!-- <div class="col-3 pb-5">
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
                        
                    </div> -->
                </div>

                <div class="row justify-content-start px-2">
                    <div class="col">
                        <table class="table text-sm">
                            <thead>
                                <tr class="color-6-red">
                                    <th scope="col" class="pt-2 pb-1 border-0">Start Date of Pregnancy or Date of Last AI/Bull Service</th>
                                    <th scope="col" class="pt-2 pb-1 border-0">Estimated Calving Date</th>
                                    <th scope="col" class="pt-2 pb-1 border-0">Actual Date of Calving</th>
                                    <th scope="col" class="pt-2 pb-1 border-0">Description</th>
                                </tr>
                            </thead>
                            <tbody class="nomatches" hidden="true" v-if="PregnancyDetails.data.length == 0">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <h6 class="text-danger">
                                            No matching results
                                        </h6>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody >
                                <tr class="color-6-red" v-for="pregnancy in PregnancyDetails.data" :key="pregnancy">
                                    <td class="text-wrap text-sm" style="max-width: 150px;" v-html="this.dateFormat(pregnancy.start_date)"></td>
                                    <td class="text-wrap text-sm" style="max-width: 150px;" v-html="this.calvingDate(pregnancy.start_date)"></td>
                                    <td class="text-wrap text-sm" style="max-width: 150px;" v-html="this.dateFormatEndDate(pregnancy.end_date)"></td>
                                    <td class="text-wrap text-sm" style="max-width: 150px;" v-html="pregnancy.description"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12">
                        <pagination :links="PregnancyDetails.links" :current_page="PregnancyDetails.current_page" :prev_url="PregnancyDetails.prev_page_url" :next_url="PregnancyDetails.next_page_url" :total_page="PregnancyDetails.last_page" :path="PregnancyDetails.path"></pagination>
                    </div>
                </div>
            </section>
        </div>

        <div class="content-wrapper bg-transparent" v-if="this.PregnancyDetails.data.length == 0">
            <section class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <a class="cursor-pointer" @click="viewSpecificLivestock(Livestock_ID, this.Prev_Url)"> <span class="color-5-orange"> Livestock Profile </span> </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <inertia-link href="#">
                                    <span class="color-5-orange"> Pregnancy Info </span>
                                </inertia-link>
                            </li>
                        </ol>
                    </div>

                    <div class="col ml-3">
                        <h2 class="h4 font-weight-bold">
                            Pregnancy Info
                        </h2>
                    </div>

                    <div class="col-12 text-center mt-5">
                        <h6 class="mt-5 text-danger">PREGNANCY DATA NOT FOUND</h6>
                    </div>
                </div>
            </section>
        </div>
                
        <!-- Viewing Pregnancy Modal-->
        <modal :id="pregnancyModal" :maxWidth="'xl'" v-if="this.PregnancyDetails.data.length > 0">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                   
                <div class="modal-body">
                </div>

                
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
    
    export default {
        components: {
            AdminLayout,
            Modal,
            Pagination,
            Swal,
        },
        props: [ 'UsersDetails', 'PregnancyDetails', 'Filters', 'UserRoles', 'Prev_Url', 'Livestock_ID' ],
        data() {
            return {    
                params:{
                    searchLivestock: this.Filters.searchLivestock,
                    field: this.Filters.field,
                    direction: this.Filters.direction,
                },
                pregnancyModal: 'pregnancyModal',
            }
        },
        methods: {
            viewSpecificLivestock(livestock_id, prev_url){
                console.log(livestock_id);
                console.log(prev_url);
                this.$inertia.get(route(this.UserRoles[0]+'.SpecificLivestock'),
                {
                    livestock_id: livestock_id,
                    prev_url : prev_url
                },
                { replace: true })
            },
            calvingDate(date){
                if(this.dateFormat(moment(date).add(308, 'd')) == 'Invalid date'){
                    return "No data";
                }else{
                    return this.dateFormat(moment(date).add(308, 'd'));
                }
                
            },
            yearDateFormat(date_data){
                return moment(date_data).format('YYYY');
            },
            dateFormat(date_data){
                if(moment(date_data).format('MMM DD YYYY') == 'Invalid date'){
                    return "No data";
                }else{
                    return moment(date_data).format('MMM DD YYYY');
                }
            },
            dateFormatEndDate(date_data){
                if(date_data == null){
                    return "";
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
        },
        created:function(){
            // console.log(this.PregnancyDetails);
            // console.log(this.Prev_Url);
            console.log("yawa");
            console.log(this.Livestock_ID);
            
        }
    }
</script>
