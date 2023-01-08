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
                                <inertia-link :href="route(UserRoles[0]+'.ViewProvinces')" :active="route().current(UserRoles[0]+'.AdminStaff')" class=""> <span class="color-5-orange"> Province </span></inertia-link>
                                <!-- <inertia-link href="/pcc_admin/ViewProvinces" class="" :active="route().current('pcc_admin.ViewProvinces')">Province</inertia-link> -->
                            </li>
                            <li class="breadcrumb-item">
                                <inertia-link href="#" @click="viewCity(this.BrgyList.data[0].admin_level_two.admin_level_one.id)"> <span class="color-5-orange"> Cities </span> </inertia-link>
                            </li>
                            <li class="breadcrumb-item">
                                <inertia-link href="#" class="text-secondary"> <span class="color-5-orange"> Brgy </span> </inertia-link>
                            </li>
                        </ol>
                    </div>
                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            Barangays under {{ BrgyList.data[0].admin_level_two.name }} 
                        </h5>
                    </div>
                    
                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control border-right-0 searching" v-model="params.searchBrgy" placeholder="Search..." aria-label="Search..." style="border-top-left-radius:50px;border-bottom-left-radius:50px;" aria-describedby="basic-addon2">
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
                            <thead >
                                <tr class="color-6-red">
                                    <th scope="col" class="pt-3 pb-1 border-0">Province</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Municipality/City</th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('name')">
                                            Barangay
                                            <i v-if="params.searchBrgy === null && params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Total Farmers</th>
                                </tr>
                            </thead>
                            <tbody class="nomatches" hidden="true" v-if="BrgyList.data.length == 0">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <h6 class="text-danger">
                                            No matching results
                                        </h6>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody >
                                <tr class="color-6-red" v-for="city in BrgyList.data" :key="city">
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="city.admin_level_two.admin_level_one.name"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="city.admin_level_two.name"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="city.name"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="city.farmers_count"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12">
                        <pagination :links="BrgyList.links" :current_page="BrgyList.current_page" :prev_url="BrgyList.prev_page_url" :next_url="BrgyList.next_page_url" :total_page="BrgyList.last_page" :path="BrgyList.path"></pagination>
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
        props: [ 'UsersDetails', 'BrgyList', 'Filters', 'UserRoles' ],
        data() {
            return {
                params:{
                    city_id: this.BrgyList.data[0].admin_level_two.id,
                    searchBrgy: this.Filters.searchBrgy,
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.ViewBrgy'), params, {
                            replace: true,
                            preserveState: false,
                            onSuccess: (res) => {
                                console.log("ViewCities");
                                // this.searching_data = res.props.Symptoms.data;
                                if(res.props.BrgyList.data.length == 0){
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
            back() {
                window.history.back();
            },
            viewCity(prov_id){
                console.log(prov_id);
                
                this.$inertia.get(route(this.UserRoles[0]+'.ViewCities'),
                {
                    prov_id: prov_id,
                },
                { replace: true })
            },
        },
        created:function(){
            console.log(this.BrgyList);
        }
    }
</script>
