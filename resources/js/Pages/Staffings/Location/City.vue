<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper">
            <section class="container-fluid">
                <div class="row px-3">
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <inertia-link :href="route(UserRoles[0]+'.AdminStaff')" :active="route().current(UserRoles[0]+'.AdminStaff')" class=""> <span class="color-5-orange"> User Management </span> </inertia-link>
                            </li>
                            <li class="breadcrumb-item">
                                <inertia-link :href="route(UserRoles[0]+'.ViewProvinces')" :active="route().current(UserRoles[0]+'.AdminStaff')" class=""> <span class="color-5-orange"> Province </span> </inertia-link>
                            </li>
                        </ol>
                    </div>

                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            Cities/Municipalities under {{ CityList.data[0].admin_level_one.name }}
                        </h5>
                    </div>
                    
                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control border-right-0 searching" v-model="params.searchCity" placeholder="Search..." aria-label="Search..." style="border-top-left-radius:50px;border-bottom-left-radius:50px;" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-light border-left-0 bg-white" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-right:1px solid #ced4da;">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row px-3 justify-content-start">
                    <div class="col">
                        <table class="table text-sm">
                            <thead>
                                <tr class="color-6-red">
                                    <th scope="col" class="pt-3 pb-1 border-0">
                                        Province
                                    </th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('name')">
                                            City/Municipality
                                            <i v-if="params.searchSymptoms === null && params.direction === null || params.field != 'name' " class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0">
                                        Total Staff Assigned
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0" v-if="this.UserRoles.includes('Admin')">
                                        Assign
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody class="nomatches" hidden="true" v-if="CityList.data.length == 0">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <h6 class="text-danger">
                                            No matching results
                                        </h6>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody >
                                <tr v-for="city in CityList.data" :key="city">
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="city.admin_level_one.name"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="city.name"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="city.users_count"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-if="this.UserRoles.includes('Admin')">
                                        <a href="#" @click="AssignUsers(city.id)" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm">Assign <i class="fas fa-users"></i></a>
                                    </td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;">
                                        <a @click="viewBrgy(city.id)" data-toggle="modal" data-target="#usersProfileModal" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm">View <i class="fas fa-eye"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12">
                        <pagination :links="CityList.links" :current_page="CityList.current_page" :prev_url="CityList.prev_page_url" :next_url="CityList.next_page_url" :total_page="CityList.last_page" :path="CityList.path"></pagination>
                    </div>
                </div>
            </section>
        </div>

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
        props: [ 'UsersDetails', 'CityList', 'Filters', 'UserRoles' ],
        data() {
            return {
                params:{
                    prov_id: this.CityList.data[0].admin_level_one.id,
                    searchCity: this.Filters.searchCity,
                    field: this.Filters.field,
                    direction: this.Filters.direction,
                },
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.ViewCities'), params, {
                            replace: true,
                            preserveState: false,
                            onSuccess: (res) => {
                                console.log("ViewCities");
                                // this.searching_data = res.props.Symptoms.data;
                                if(res.props.CityList.data.length == 0){
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
            AssignUsers(city_id){
                // this.specificFarmer = farmer_data;
                this.$inertia.get(route(this.UserRoles[0]+'.AssignUsers'),
                {
                    // user_id: "",
                    city_id: city_id,
                })
            },
            viewBrgy(city_id){
                var currentUrl = window.location.href;
                // this.specificFarmer = farmer_data;
                this.$inertia.get(route(this.UserRoles[0]+'.ViewBrgy'),
                {
                    city_id: city_id,
                },
                { replace: true })
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
            console.log(this.CityList);
        }
    }
</script>
