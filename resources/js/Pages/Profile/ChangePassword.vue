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
                            Change Password
                        </h5>
                    </div>
                </div>

                <div class="row px-3 justify-content-start mt-3">
                    <div class="col-4 ml-3">

                        <jet-validation-errors class="mb-3" />
                        <div v-if="status" class="alert alert-success mb-3 rounded-0" role="alert">
                            {{ status }}
                        </div>

                        <form @submit.prevent="applyChangePassword">
                            <div class="form-group">
                                <jet-label for="password1" value="Current Password" />
                                <div class="input-group mb-3">
                                <jet-input id="password1" type="password" v-model="changePasswordForm.old_password" required autocomplete="new-password" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-left:1px solid #ced4da;"/>
                                    <div class="input-group-append">
                                        <span @click="toggleshowCurrentPassword()" type="button" class="toggleBtnPass bg-white">
                                            <i class="fas" :class="{ 'fa-eye-slash': showCurrentPassword == true, 'fa-eye': showCurrentPassword == false }" style="margin-top: 50%;margin-left:-50%;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <jet-label for="password2" value="New Password" />
                                <div class="input-group mb-3">
                                <jet-input id="password2" type="password" v-model="changePasswordForm.password" required autocomplete="new-password" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-left:1px solid #ced4da;"/>
                                    <div class="input-group-append">
                                        <span @click="toggleShowPassword()" type="span" class="toggleBtnPass bg-white">
                                            <i class="fas" :class="{ 'fa-eye-slash': showPassword == true, 'fa-eye': showPassword == false }" style="margin-top: 50%;margin-left:-50%;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="form-group">
                                <jet-label for="password_confirmation" value="Retype new password" />

                                <div class="input-group mb-3">
                                <jet-input id="password_confirmation" type="password" v-model="changePasswordForm.password_confirmation" required autocomplete="new-password" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-left:1px solid #ced4da;"/>
                                    <div class="input-group-append">
                                        <span @click="toggleShowPassword()" type="button" class="toggleBtnPass bg-white">
                                            <i class="fas" :class="{ 'fa-eye-slash': showPassword == true, 'fa-eye': showPassword == false }" style="margin-top: 50%;margin-left:-50%;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- :class="{ 'text-white-50': changePasswordForm.processing }" :disabled="changePasswordForm.password_confirmation == null || changePasswordForm.password_confirmation == '' || changePasswordForm.old_password == null || changePasswordForm.old_password == '' || changePasswordForm.password == null || changePasswordForm.password == '' || changePasswordForm.processing" -->
                            <button type="submit" class="btn btn-md w-100 btn-outline-success rounded-pill">
                                Submit <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>

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
                showPassword: false,
                showConfirmedPassword: false,
                showCurrentPassword: false,
                changePasswordForm: this.$inertia.form({
                    old_password: null, 
                    password: null, 
                    password_confirmation: null,
                })
                
            }
        },
        methods: {
            applyChangePassword(staff_id){
                this.changePasswordForm.post(this.route(this.UserRoles[0]+'.ChangePassword'), {

                    onSuccess: (res) => {
                        console.log(res);
                        Swal.fire({
                            title: 'Success!',
                            text: 'Password has been updated successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                        // reset form
                        this.changePasswordForm.reset();
                    },
                    // onFinish: () => this.changePasswordForm.reset('password', 'password_confirmation'),
                })

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
            toggleShowPassword() {
                console.log($('#password2').attr('type'));
                if($('#password2').attr('type') == 'password'){
                    $('#password2').attr('type', 'text');
                    this.showPassword = true;
                }else{
                    $('#password2').attr('type', 'password');
                    this.showPassword = false;
                }

                if($('#password_confirmation').attr('type') == 'password'){
                    $('#password_confirmation').attr('type', 'text');
                    this.showConfirmedPassword = true;
                }else{
                    $('#password_confirmation').attr('type', 'password');
                    this.showConfirmedPassword = false;
                }
                
            },
            toggleshowCurrentPassword() {
                console.log($('#password1').attr('type'));
                if($('#password1').attr('type') == 'password'){
                    $('#password1').attr('type', 'text');
                    this.showCurrentPassword = true;
                }else{
                    $('#password1').attr('type', 'password');
                    this.showCurrentPassword = false;
                }
            },
            
        },
        created:function(){
            console.log("ChangePassword");
        }
    }
</script>
