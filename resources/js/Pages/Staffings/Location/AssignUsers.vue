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
                                <inertia-link href="#" @click="viewCity(City[0].admin_level_one_id)" :active="route().current(UserRoles[0]+'.ViewBrgy')"> <span class="color-5-orange"> Cities </span> </inertia-link>
                            </li>
                        </ol>
                    </div>
                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            <!-- {{ this.AssignedDefault }} -->
                            <!-- {{ this.Filters }} -->
                            Assign Users to {{ this.City[0].name+', '+ this.City[0].admin_level_one.name}}
                        </h5>
                    </div>
                    
                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control border-right-0 searching" v-model="params.searchStaff" placeholder="Search..." aria-label="Search..." style="border-top-left-radius:50px;border-bottom-left-radius:50px;" aria-describedby="basic-addon2">
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
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('last_name')">
                                            Last Name
                                            <i v-if="params.searchStaff === null && params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'last_name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'last_name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('first_name')">
                                            First Name
                                            <i v-if="params.searchStaff === null && params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'first_name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'first_name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Username</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Roles</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody class="nomatches" hidden="true" v-if="CityAndUsersList.data.length == 0">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <h6 class="text-danger">
                                            No matching results
                                        </h6>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody >
                                <tr class="color-6-red" v-for="staffs in CityAndUsersList.data" :key="staffs">
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="staffs.last_name"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="staffs.first_name"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="staffs.email"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;">
                                        <span v-for="role in staffs.user_roles" :key="role" class="badge badge-pill text-wrap badge-warning mr-1 my-1 text-xs">
                                            <span v-show="role.role == 'IFodderContentManager'">iFodder</span>
                                            <span v-show="role.role == 'IHealthContentManager'">iHealth</span>
                                            <span v-show="role.role == 'ReportsUser'">Reports</span>
                                            <span v-show="role.role == 'Vet'">Vet</span>
                                            <span v-show="role.role == 'VetAide'">Vet Aide</span>
                                            <span v-show="role.role == 'Admin'">Admin</span>
                                        </span>
                                    </td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;">
                                        <label v-if="this.appendCheckBox(staffs.id) == true" class="flex items-center">
                                            <input :id="'check_'+staffs.id" type="checkbox" class="form-checkbox" @change="onChangeCheckBox($event,staffs.id,staffs.first_name+' '+staffs.last_name)" checked>
                                        </label>
                                        <label v-else class="flex items-center">
                                            <input :id="'check_'+staffs.id" type="checkbox" class="form-checkbox" @change="onChangeCheckBox($event,staffs.id,staffs.first_name+' '+staffs.last_name)">
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12">
                        <div class="row justify-row-between">
                            <div class="col-10">
                                <pagination :links="CityAndUsersList.links" :current_page="CityAndUsersList.current_page" :prev_url="CityAndUsersList.prev_page_url" :next_url="CityAndUsersList.next_page_url" :total_page="CityAndUsersList.last_page" :path="CityAndUsersList.path"></pagination>
                            </div>
                            <div class="col">
                                <!-- <button type="button" class="btn btn-outline-success btn-block" @click="saveAssignUsers()"> Save <i class="fas fa-paper-plane"></i> </button> -->
                            </div>
                        </div>
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
        props: [ 'UsersDetails', 'CityAndUsersList', 'City', 'Filters', 'UserRoles', 'AssignedDefault' ],
        data() {
            return {
                params:{
                    city_id: this.Filters.city_id,
                    searchStaff: this.Filters.searchStaff,
                    field: this.Filters.field,
                    direction: this.Filters.direction,
                },
                staffDetails: [],
                staffAlreadyAssigned: [],
                staffAlreadyAssigned_Sesh: [],
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.AssignUsers'), params, {
                            replace: true,
                            preserveState: false,
                            onSuccess: (res) => {
                                // this.searching_data = res.props.Symptoms.data;
                                if(res.props.CityAndUsersList.data.length == 0){
                                    $('.nomatches').removeAttr('hidden');
                                }else{
                                    $('.nomatches').attr('hidden','hidden');
                                }
                                console.log("res");
                                console.log(res);
                            },
                        });
                        
                    
                    }.bind(this), 500)
                },
                deep: true,
            },
        },
        methods: {
            appendCheckBox(admin_level_2_obj){
                var arr = [];
                var arr_sesh = [];
                var result;

                if(this.staffAlreadyAssigned_Sesh.includes(admin_level_2_obj)){
                    // console.log("true");
                    result = true;
                }else{
                    // console.log("false");
                    result = false;
                }
                
                return result;
            },
            saveAssignUsers(staff_array_id){
                console.log(this.staffAlreadyAssigned);

                this.$inertia.post(route(this.UserRoles[0]+'.SaveAssignedUsers'), [this.AssignedDefault, this.City[0].id], {
 					onSuccess: (res) => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Staff Location Assignment Updated Successfully!',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                         console.log(res);
                     },
					onFinish: () => {
                        

                    },
				}).then(function (response) {
					// this.$inertia.reload();
            	}.bind(this));
            },
            onChangeCheckBox($event,user_id,staff_name) {
                if($('#check_'+user_id).is(":checked")){
                    Swal.fire({
                        title: 'Assign '+staff_name+' to this area?', 
                        showDenyButton: true,
                        confirmButtonText: 'Yes',
                        denyButtonText: `No`,
                    }).then((result) => {
                        if (result.isConfirmed) {

                            this.$inertia.post(route(this.UserRoles[0]+'.DirectSaveAssignedUsers'),
                            {
                                user_id: parseInt(user_id),
                                city_id: this.City[0].id,
                                type: 1,
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

                    console.log(this.staffAlreadyAssigned);
                }else if($('#check_'+user_id).is(":not(:checked)")){
                    Swal.fire({
                        title: 'Remove this assigned area for '+staff_name+'?', 
                        showDenyButton: true,
                        confirmButtonText: 'Yes',
                        denyButtonText: `No`,
                    }).then((result) => {
                        if (result.isConfirmed) {

                            this.$inertia.post(route(this.UserRoles[0]+'.DirectSaveAssignedUsers'),
                            {
                                user_id: parseInt(user_id),
                                city_id: this.City[0].id,
                                type: 2,
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
            AddNewPCCStaff() {
                this.createStaffForm.post(this.route(this.UserRoles[0]+'.CreateStaff'), {

                    onSuccess: (res) => {
                        console.log(res);
                        Swal.fire({
                            title: 'Success!',
                            text: 'Account Created Successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                        // reset form
                        this.createStaffForm.reset();
                         $('#addStaff').modal('hide');
                        // this.createStaffForm.reset('password', 'password_confirmation')
                    },
                })
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
            console.log("CityAndUsersList");
            console.log(this.CityAndUsersList);
            
            if(this.AssignedDefault != null){
                // Check AssignedDefault
                this.AssignedDefault.forEach(element => {
                console.log("this.AssignedDefault");
                    this.staffAlreadyAssigned_Sesh.push(element);
                });
                console.log("this.staffAlreadyAssigned_Sesh");
                console.log(this.staffAlreadyAssigned_Sesh);
            }

        }
    }
</script>
