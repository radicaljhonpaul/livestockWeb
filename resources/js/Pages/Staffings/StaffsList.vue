<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">
                <div class="row px-3">
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <inertia-link :href="'/'+this.UserRoles[0]" class="" :active="route().current('pcc_admin.AdminHealthGuide')">  <span class="color-5-orange"> Dashboard </span> </inertia-link>
                            </li>
                        </ol>
                    </div>
                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            User Management
                        </h5>
                    </div>
                    
                    <div class="col-2 text-right ml-auto">
                        <inertia-link :href="route(this.UserRoles[0]+'.ViewProvinces')" class="btn btn-outline-brown btn-sm" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">Location Mngt. <i class="fas fa-clipboard"></i></inertia-link>
                    </div>

                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend" v-if="this.UserRoles.includes('Admin')">
                                <button type="button" class="btn btn-outline-light border-left-0 bg-white" data-toggle="modal" data-target="#addStaff" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-left:1px solid #ced4da;">
                                    <i class="fas fa-plus color-5-orange"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control border-right-0 searching" v-model="params.searchUsers" placeholder="Search staffs..." aria-label="Search..." style="" aria-describedby="basic-addon2">
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
                        <table class="table text-sm">
                            <thead class="">
                                <tr class="color-6-red">
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('last_name')">
                                            Last Name
                                            <i v-if="params.searchUsers === null && params.direction === null || params.field != 'last_name'" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'last_name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'last_name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('first_name')">
                                            First Name
                                            <i v-if="params.searchUsers === null && params.direction === null || params.field != 'first_name'" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'first_name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'first_name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Username</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Roles</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Areas</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody class="nomatches" hidden="true" v-if="StaffList.data.length == 0">
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <h6 class="text-danger">
                                            No matching results
                                        </h6>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody >
                                <tr class="color-6-red" v-for="staffs in StaffList.data" :key="staffs">
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="staffs.last_name"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="staffs.first_name"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;" v-html="staffs.email"></td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;">
                                        <span v-for="role in staffs.user_roles" :key="role" class="badge badge-pill text-wrap badge-custom-yellow mr-1 my-1 text-xs">
                                            <span v-show="role.role == 'IFodderContentManager'">iFodder</span>
                                            <span v-show="role.role == 'IHealthContentManager'">iHealth</span>
                                            <span v-show="role.role == 'ReportsUser'">Reports</span>
                                            <span v-show="role.role == 'Vet'">Vet</span>
                                            <span v-show="role.role == 'VetAide'">Vet Aide</span>
                                            <span v-show="role.role == 'Admin'">Admin</span>
                                        </span>
                                    </td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;">
                                        <span v-for="areas in staffs.admin_level_twos" :key="areas" class="badge badge-pill text-wrap badge-custom-inverted mr-1 my-1 text-xs">
                                            <span v-html="areas.name"></span>
                                        </span>
                                    </td>
                                    <td class="text-wrap text-sm border-0" style="max-width: 150px;">
                                        <a @click="viewSpecificStaff(staffs.id)" data-toggle="modal" data-target="#usersProfileModal" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm">View <i class="fas fa-eye"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 mt-2 pl-0">
                        <pagination :links="StaffList.links" :current_page="StaffList.current_page" :prev_url="StaffList.prev_page_url" :next_url="StaffList.next_page_url" :total_page="StaffList.last_page" :path="StaffList.path"></pagination>
                    </div>
                </div>
            </section>
        </div>

        <!-- Viewing and Editing Modal-->
        <modal :id="addStaff" :maxWidth="'md'">
            <div class="modal-content color-6-red">
                
                <div class="modal-header">
                    <h5 class="modal-title" > Create User </h5>
                    <button @click="editStaffData = false, this.onHiddenModalCreateStaff()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <jet-validation-errors class="mb-3" />

                    <form @submit.prevent="AddNewPCCStaff">
                    <div class="form-group">
                        <jet-label for="first_name" value="First Name" />
                        <jet-input class="rounded-pill" id="first_name" type="text" v-model="createStaffForm.first_name" required autofocus autocomplete="name" />
                    </div>

                    <div class="form-group">
                        <jet-label for="last_name" value="Last Name" />
                        <jet-input class="rounded-pill" id="last_name" type="text" v-model="createStaffForm.last_name" required autofocus autocomplete="name" />
                    </div>
                
                    <div class="form-group">
                        <jet-label for="mobile_number" value="Mobile Number" />
                        <jet-input class="rounded-pill" id="mobile_number" type="text" v-model="createStaffForm.mobile_number" required />
                    </div>

                    <div class="form-group">
                        <jet-label for="username" value="Username" />
                        <jet-input class="rounded-pill" id="username" type="text" v-model="createStaffForm.username" required />
                    </div>

                    <div class="form-group">
                        <jet-label for="email" value="Email" />
                        <jet-input class="rounded-pill" id="email" type="email" v-model="createStaffForm.email" required />
                    </div>

                    <div class="form-group">
                        <jet-label for="password" value="Password" />

                        <div class="input-group mb-3">
                        <jet-input v-if="showPassword == false" id="password" type="password" v-model="createStaffForm.password" required autocomplete="new-password" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-left:1px solid #ced4da;"/>
                        <jet-input v-else id="password" type="text" v-model="createStaffForm.password" required autocomplete="new-password" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-left:1px solid #ced4da;"/>
                            <div class="input-group-append">
                                <button @click="toggleShowPassword()" type="button" class="btn btn-outline-light border-left-0 bg-white" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-right:1px solid #ced4da;">
                                    <i class="fas" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <jet-label for="password_confirmation" value="Confirm Password" />

                        <div class="input-group mb-3">
                        <jet-input v-if="showConfirmedPassword == false" id="password_confirmation" type="password" v-model="createStaffForm.password_confirmation" required autocomplete="new-password" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-left:1px solid #ced4da;"/>
                        <jet-input v-else id="password_confirmation" type="text" v-model="createStaffForm.password_confirmation" required autocomplete="new-password" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-left:1px solid #ced4da;"/>
                            <div class="input-group-append">
                                <button @click="toggleShowPasswordConfirm()" type="button" class="btn btn-outline-light border-left-0 bg-white" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-right:1px solid #ced4da;">
                                    <i class="fas" :class="{ 'fa-eye-slash': showConfirmedPassword, 'fa-eye': !showConfirmedPassword }"></i>
                                </button>
                            </div>
                        </div>
                        <!-- <jet-input v-if="showPassword == false" id="password_confirmation" type="password" v-model="createStaffForm.password_confirmation" required autocomplete="new-password" /> -->
                        <!-- <jet-input v-else id="password_confirmation" type="text" v-model="createStaffForm.password_confirmation" required autocomplete="new-password" /> -->
                    </div>

                    <div class="form-group">
                        <jet-label for="user_role" value="Roles" />
                        <br>
                        <!-- <select id="user_role" type="text" class="form-control" name="user_role" v-model="createStaffForm.user_role" required>
                            <option value="Admin">Admin</option>
                            <option value="Vet">Vet</option>
                            <option value="Vet Aide">Vet Aide</option>
                            <option value="iHealth Content Manager">iHealth Content Manager</option>
                            <option value="iFeed Content Manager">iFeed Content Manager</option>
                            <option value="Reports User">Reports User</option>
                        </select> -->
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" v-model="createStaffForm.user_role" id="Admin" value="Admin">
                                    <label class="form-check-label" for="Admin">Administrator</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" v-model="createStaffForm.user_role" id="Vet" value="Vet">
                                    <label class="form-check-label" for="Vet">Veterinarian</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" v-model="createStaffForm.user_role" id="VetAide" value="VetAide">
                                    <label class="form-check-label" for="VetAide">Veterinarian Aide</label>
                                </div>
                            </div>
                        
                            <div class="col-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" v-model="createStaffForm.user_role" id="iheatlh" value="IHealthContentManager">
                                    <label class="form-check-label" for="iheatlh">iHealth Content Manager</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" v-model="createStaffForm.user_role" id="ifodder" value="IFodderContentManager">
                                    <label class="form-check-label" for="ifodder">iFodder Content Manager</label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" v-model="createStaffForm.user_role" id="ReportsUser" value="ReportsUser">
                                    <label class="form-check-label" for="ReportsUser">Reports User</label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group" v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
                        <div class="custom-control custom-checkbox">
                        <jet-checkbox name="terms" id="terms" v-model:checked="createStaffForm.terms" />

                        <label class="custom-control-label" for="terms">
                            I agree to the <a target="_blank" :href="route('terms.show')">Terms of Service</a> and <a target="_blank" :href="route('policy.show')">Privacy Policy</a>
                        </label>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <button type="button" @click="this.onHiddenModalCreateStaff()"  class="btn btn-sm btn-outline-secondary rounded-pill" data-dismiss="modal" aria-label="Close">
                                Cancel <i class="fas fa-times-circle"></i>
                            </button>
                            
                            <button :class="{ 'text-white-50': createStaffForm.processing }" :disabled="createStaffForm.processing" type="submit" class="ml-4 btn btn-sm btn-outline-success rounded-pill">
                                Create <i class="fas fa-paper-plane"></i>
                            </button>

                            <!-- <jet-button class="ml-4" :class="{ 'text-white-50': createStaffForm.processing }" :disabled="createStaffForm.processing">
                                Create <i class="fas fa-user"></i>
                            </jet-button> -->
                        </div>
                    </div>
                    </form>
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
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from "@/Jetstream/Checkbox";
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            AdminLayout,
            Modal,
            Pagination,
            Swal,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors
        },
        props: [ 'UsersDetails', 'StaffList', 'Filters', 'UserRoles' ],
        data() {
            return {
                params:{
                    searchUsers: this.Filters.searchUsers,
                    field: this.Filters.field,
                    direction: this.Filters.direction,
                },
                addStaff: 'addStaff',
                specificFarmer: [],
                editSpecificFarmer: false,
                editStaffData: false,
                staffDetails: [],
                showPassword: false,
                showConfirmedPassword: false,
                createStaffForm: this.$inertia.form({
                    last_name: null,
                    first_name: null,
                    mobile_number: null,
                    username: null,
                    email: '',
                    password: '', 
                    user_role: [],
                    password_confirmation: '',
                    terms: false,
                })
                
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.AdminStaff'), params, {
                            replace: true,
                            preserveState: false,
                            onSuccess: (res) => {
                                console.log("AdminStaff");
                                // this.searching_data = res.props.Symptoms.data;
                                if(res.props.StaffList.data.length == 0){
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
            viewSpecificStaff(staff_id){
                var currentUrl = window.location.href;
                // this.specificFarmer = farmer_data;
                this.$inertia.get(route(this.UserRoles[0]+'.SpecificStaff'),
                {
                    staff_id: staff_id,
                    prev_url: currentUrl,
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
            AddNewPCCStaff() {
                this.createStaffForm.post(this.route(this.UserRoles+'.CreateStaff'), {

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
                    // onFinish: () => this.createStaffForm.reset('password', 'password_confirmation'),
                })
            },
            toggleShowPassword() {
                this.showPassword = !this.showPassword;
            },
            toggleShowPasswordConfirm() {
                this.showConfirmedPassword = !this.showConfirmedPassword;
            },
            onHiddenModalCreateStaff() {
                this.createStaffForm.reset();
                this.showPassword = false;
                this.showConfirmedPassword = false;
            }
        },
        created:function(){
            console.log("StaffList");
            console.log(this.StaffList);
        }
    }
</script>
