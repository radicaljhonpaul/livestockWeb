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
                            Farmers List 
                        </h5>
                    </div>
                    
                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="input-group input-group-sm mb-3">
                            <!-- <div class="input-group-prepend">
                                <button type="button" class="btn btn-outline-warning rounded-0" data-toggle="modal" data-target="#createSymptomsModal" style="border:1px solid #ced4da;">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div> -->
                            <input type="text" class="form-control border-right-0 searching" v-model="params.searchFarmers" placeholder="Search Farmers..." aria-label="Search..." style="border-top-left-radius:50px;border-bottom-left-radius:50px;" aria-describedby="basic-addon2">
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
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('last_name')">
                                            Last Name
                                            <i v-if="params.searchFarmers === null && params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'last_name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'last_name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('first_name')">
                                            First Name
                                            <i v-if="params.searchFarmers === null && params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'first_name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'first_name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Province</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">City / Municipality</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Barangay</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Mobile Number</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody class="nomatches border-0" v-if="FarmersList.data.length == 0">
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <h6 class="text-danger">
                                            No matching results
                                        </h6>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody >
                                <tr class="color-6-red" v-for="farmers in FarmersList.data" :key="farmers">
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px;" v-html="farmers.last_name"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px;" v-html="farmers.first_name"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px;" v-html="farmers.admin_level_three.admin_level_two.admin_level_one.name"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px;" v-html="farmers.admin_level_three.admin_level_two.name"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px;" v-html="farmers.admin_level_three.name"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px;" v-html="farmers.mobile_number"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px;">
                                        <a @click="viewSpecificFarmer(farmers.id)" data-toggle="modal" data-target="#farmersProfileModal" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm">View <i class="fas fa-eye"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-8  mt-2 pl-0">
                        <pagination :links="FarmersList.links" :current_page="FarmersList.current_page" :prev_url="FarmersList.prev_page_url" :next_url="FarmersList.next_page_url" :total_page="FarmersList.last_page" :path="FarmersList.path"></pagination>
                    </div>

                    <div class="col-2  mt-2 mr-auto pr-0">
                        <a v-if="this.roleExemption(this.UserRoles) == true" v-bind:href="'/'+this.UserRoles[0]+'/DownloadFarmerList'"  class="btn btn-outline-brown btn-sm w-100" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">
                            Download Farmer list  <i class="fas fa-file-download"> 
                        </i></a>
                    </div>

                    <div class="col-2  mt-2 mr-auto pr-0">
                        <a v-if="this.roleExemption(this.UserRoles) == true" v-bind:href="'/'+this.UserRoles[0]+'/DownloadLivestockList'"  class="btn btn-outline-brown btn-sm w-100" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">
                            Download Carabao list  <i class="fas fa-file-download"> 
                        </i></a>
                    </div>
                </div>
            </section>
        </div>


        <!-- Viewing and Editing Modal-->
        <!-- <modal :id="farmersProfileModal" :maxWidth="'xl'">
            <div class="modal-content">
                
                <div class="modal-header flex-column">
                    <button @click="editSpecificHealthConditions = false" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" v-if="specificFarmer.livestock" v-cloak>
                </div>
                <div class="modal-footer">  
                </div>
            </div>
        </modal> -->
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
        props: [ 'UsersDetails', 'FarmersList', 'Filters', 'UserRoles' ],
        data() {
            return {
                params:{
                    searchFarmers: this.Filters.searchFarmers,
                    field: this.Filters.field,
                    direction: this.Filters.direction,
                },
                farmersProfileModal: 'farmersProfileModal',
                specificFarmer: [],
                editSpecificFarmer: false,
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.AdminFarmers'), params, {
                            replace: true,
                            preserveState: false,
                            onSuccess: (res) => {
                                console.log("AdminFarmers");
                                // this.searching_data = res.props.Symptoms.data;
                                if(res.props.FarmersList.data.length == 0){
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
        methods: {
            viewSpecificFarmer(farmer_id){
                var currentUrl = window.location.href;
                // this.specificFarmer = farmer_data;
                this.$inertia.get(route(this.UserRoles[0]+'.SpecificFarmer'),
                {
                    farmer_id: farmer_id,
                    prev_url: currentUrl,
                },
                { replace: true })
            },
            roleExemption(roles_arr){
                // Customize this arrqay according to requirement
                var rolesArr = ['Vet', 'ReportsUser', 'VetAide', 'Admin'];
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
            dateFormat(date_data){
                return moment(date_data).format('MMM DD YYYY');
            },
            dateTimeFormat(date_data){
                return moment(date_data).format('MMM DD YYYY, h:mm:ss a');
            },
            sort(field){
                this.params.field = field;
                this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
            },
        },
        created:function(){
            console.log("FarmersList");
            console.log(this.FarmersList);
        }

    }
</script>
