<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">
                <div class="row px-3">
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <inertia-link :href="'/'+this.UserRoles[0]" class="" :active="route().current('pcc_admin.AdminHealthGuide')"> <span class="color-5-orange">Dashboard</span> </inertia-link>
                            </li>
                        </ol>
                    </div>
                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            Top Health Issues 
                        </h5>
                    </div>
                    
                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control border-right-0 searching" v-model="params.searchTopHealthIssues" aria-label="Search..." style="border-top-left-radius:50px;border-bottom-left-radius:50px;" aria-describedby="basic-addon2">
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
                            <thead>
                                <tr class="color-6-red">
                                    <th scope="col" class="pt-3 pb-1 border-0">

                                    </th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('visit_date')">
                                            Date of Visit
                                            <i v-if="params.searchTopHealthIssues === null && params.direction === null || params.field != 'visit_date'" class="w-4 h-4 ml-auto fas fa-sort"></i>
                                            <i v-if="params.field === 'visit_date' && params.direction === 'asc'" class="w-4 h-4 ml-auto fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'visit_date' && params.direction === 'desc'" class="w-4 h-4 ml-auto fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('assessment')">
                                            Assessment
                                            <i v-if="params.searchTopHealthIssues === null && params.direction === null || params.field != 'assessment'" class="w-4 h-4 ml-auto fas fa-sort"></i>
                                            <i v-if="params.field === 'assessment' && params.direction === 'asc'" class="w-4 h-4 ml-auto fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'assessment' && params.direction === 'desc'" class="w-4 h-4 ml-auto fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Activity Type</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Condition Names</th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Notes</th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('joined_farmers_last_name')">
                                            Name of Farmer
                                            <i v-if="params.searchTopHealthIssues === null && params.direction === null || params.field != 'joined_farmers_last_name'" class="w-4 h-4 ml-auto fas fa-sort"></i>
                                            <i v-if="params.field === 'joined_farmers_last_name' && params.direction === 'asc'" class="w-4 h-4 ml-auto fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'joined_farmers_last_name' && params.direction === 'desc'" class="w-4 h-4 ml-auto fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('joined_admin_level_twos_name')">
                                            City/Municipality
                                            <i v-if="params.searchTopHealthIssues === null && params.direction === null || params.field != 'joined_admin_level_twos_name'" class="w-4 h-4 ml-auto fas fa-sort"></i>
                                            <i v-if="params.field === 'joined_admin_level_twos_name' && params.direction === 'asc'" class="w-4 h-4 ml-auto fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'joined_admin_level_twos_name' && params.direction === 'desc'" class="w-4 h-4 ml-auto fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>

                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('assigned_to_first_name')">
                                            Staff Name
                                            <i v-if="params.searchTopHealthIssues === null && params.direction === null || params.field != 'assigned_to_first_name'" class="w-4 h-4 ml-auto fas fa-sort"></i>
                                            <i v-if="params.field === 'assigned_to_first_name' && params.direction === 'asc'" class="w-4 h-4 ml-auto fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'assigned_to_first_name' && params.direction === 'desc'" class="w-4 h-4 ml-auto fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody class="nomatches border-0" v-if="Diagnosis.data.length == 0">
                                <tr class="border-0">
                                    <td colspan="9" class="text-center border-0">
                                        <h6 class="text-danger border-0">
                                            No matching results
                                        </h6>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody >
                                <tr class="color-6-red" v-for="diagnosis in Diagnosis.data" :key="diagnosis">
                                    <!-- <td class="block text-truncate text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="diagnosis.status"></td> -->
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;">
                                        <i class="far fa-bookmark"></i>
                                    </td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="dateFormat(diagnosis.visit_date)"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;">
                                        <span v-if="diagnosis.assessment == 'CR'" v-cloak> Critical &nbsp; <i class="text-danger fas fa-exclamation"></i> </span>
                                        <span v-if="diagnosis.assessment == 'IM'" v-cloak> Important </span>
                                        <span v-if="diagnosis.assessment == 'RE'" v-cloak> Report / Monitoring Visit </span>
                                    </td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;">
                                        <span v-if="diagnosis.activity_type == 'DV'" v-cloak> Diagnosis Visit </span>
                                        <span v-if="diagnosis.activity_type == 'RE'" v-cloak> Report / Monitoring Visit </span>
                                    </td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;">
                                        <span class="cursor-pointer" v-for="(items, index) in diagnosis.health_conditions" :key="index">
                                            <span class="badge badge-pill badge-custom-inverted text-wrap font-weight-lighter mx-1">
                                                {{ items.name }}
                                            </span>
                                            <span v-if="index+1 < diagnosis.health_conditions.length" v-html="','"></span>
                                        </span>
                                    </td>
                                    <td class="block text-truncate text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="diagnosis.notes"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="diagnosis.joined_farmers_last_name +', '+ diagnosis.joined_farmers_first_name"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="diagnosis.livestock.farmer.admin_level_three.admin_level_two.name"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="diagnosis.assigned_to_first_name +' '+ diagnosis.assigned_to_last_name">
                                        
                                    </td>
                                    <td class="block border-0" style="max-width: 150px; word-wrap: break-word;">
                                        <a @click="viewSpecificDiagnosis(diagnosis)" data-toggle="modal" data-target="#diagnosisModal" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm"> View <i class="fas fa-eye"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-9  mt-2 pl-0">
                        <pagination :links="Diagnosis.links" :current_page="Diagnosis.current_page" :prev_url="Diagnosis.prev_page_url" :next_url="Diagnosis.next_page_url" :total_page="Diagnosis.last_page" :path="Diagnosis.path"></pagination>
                    </div>

                    <div class="col-3  mt-2 pr-0">
                        <!-- <a v-bind:href="'/'+this.UserRoles[0]+'/DownloadLivestockList'"  class="btn btn-outline-brown btn-sm w-100" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">
                            Download Top Health Issues  <i class="fas fa-file-download"> 
                        </i></a> -->
                        <a v-if="this.roleExemption(this.UserRoles) == true" data-toggle="modal" data-target="#filterDowloadModalTopHealthIssues" href="#" class="btn btn-outline-brown btn-sm w-100" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;"> Download Top Health Issues <i class="fas fa-file-download"></i> </a>
                    </div>
                </div>
            </section>
        </div>

        <!-- Viewing Modal-->
        <modal :id="diagnosisModal" :maxWidth="'xl'">
            <div class="modal-content">
                
                <div class="modal-header border-0 pb-0 flex-column">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" v-if="specificDiagnosis.livestock" v-cloak>
                    <reports-health-visit-modal-content :specificDiagnosis="specificDiagnosis" ></reports-health-visit-modal-content>
                </div>
            </div>
        </modal>


        <!-- Viewing Modal-->
        <modal :id="filterDowloadModalTopHealthIssues" :maxWidth="'md'">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h6 class="modal-title font-weight-bold"> Filter Data </h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetFilterDates()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <jet-validation-errors class="mb-3" />

                    <form>
                        <div class="form-group">
                            <jet-label for="from" class="color-6-red text-sm" value="FROM" />
                            <!-- <input class="form-control rounded-pill" id="from" type="date" v-model="filterData.dateFrom" required> -->
                            <!-- <DatePicker mode="date" v-model="filterData.dateFrom">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input class="form-control rounded-pill"
                                    :value="inputValue"
                                    placeholder="mm/dd/yyyy"
                                    v-on="inputEvents"
                                    />
                                </template>
                            </DatePicker> -->
                            <jet-input class="rounded-pill" id="from" type="date" v-model="filterData.dateFrom" required/>
                        </div>

                        <div class="form-group">
                            <jet-label for="to" class="color-6-red text-sm" value="TO" />
                            <jet-input class="rounded-pill" id="to" type="date" v-model="filterData.dateTo" required/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill" data-dismiss="modal" type="button" @click="resetFilterDates()">Cancel <i class="fas fa-redo"></i> </button>
                    <a :href="route(this.UserRoles[0]+'.DownloadTopHealthIssuesList', {dateFrom:filterData.dateFrom, dateTo:filterData.dateTo})" :class="{'disabled' : filterData.dateFrom == null || filterData.dateTo == null || filterData.dateFrom.length == 0 || filterData.dateTo.length == 0}" class="btn btn-outline-brown btn-sm ml-auto" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">
                        Download Top Health Issues list  <i class="fas fa-file-download"> 
                    </i></a>
                </div>
            </div>
        </modal>

    </admin-layout>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'
    import Modal from '@/Jetstream/Modal'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from "@/Jetstream/Checkbox";
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'
    import ReportsHealthVisitModalContent from '@/CustomComponents/ReportsComp/ReportsHealthVisitModalContent.vue'
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
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
        },
        props: [ 'UsersDetails', 'Diagnosis', 'Filters', 'UserRoles' ],
        data() {
            return {
                params:{
                    searchTopHealthIssues: this.Filters.searchTopHealthIssues,
                    field: this.Filters.field,
                    direction: this.Filters.direction,
                },
                diagnosisModal: 'diagnosisModal',
                filterDowloadModalTopHealthIssues: 'filterDowloadModalTopHealthIssues',
                specificDiagnosis: [],
                specificDiagnosisLivestockFarmerObj: [],
                specificDiagnosisLivestockFarmerLocationObj: [],
                specificDiagnosis_visitDate: null,
                editSpecificDiagnosis: false,
                nomatches: 0,
                filterData: this.$inertia.form({
                    dateFrom: null,
                    dateTo: null,
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.AdminReportsHealthVisit'), params, {
                            replace: true,
                            preserveState: false,
                            onSuccess: (res) => {
                                console.log("AdminReportsHealthVisit");
                                // this.searching_data = res.props.Symptoms.data;
                                if(res.props.Diagnosis.data.length == 0){
                                    // $('.nomatches').removeAttr('hidden');
                                }else{
                                    // $('.nomatches').attr('hidden','hidden');
                                }
                            },
                        });
                    
                    }.bind(this), 500)
                },
                deep: true,
            },
        },
        methods: {
            viewSpecificDiagnosis(diagnosis_data){
                this.specificDiagnosis = diagnosis_data;
                this.specificDiagnosisLivestockFarmerObj = this.specificDiagnosis.livestock.farmer;
                this.specificDiagnosis_visitDate = this.dateFormat(this.specificDiagnosis.visit_date);
            },
            dateFormat(date_data){
                return moment(date_data).format('MMM DD YYYY');
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
            sort(field){
                this.params.field = field;
                this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
            },
            resetFilterDates(){
                this.filterData.reset();
            },
            filterDataDownload(){
                this.$inertia.get(route(this.UserRoles[0]+'.DownloadTopHealthIssuesList'), this.filterData, {
                        forceFormData: true,
                        onSuccess: (res) => {
                            $('#filterDowloadModalTopHealthIssues').modal('hide');
                            console.log("onSuccess");
                        },
                        onFinish: () => {
                            Swal.fire({
                                title: 'Info!',
                                text: 'Please wait for the file to download',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showCancelButton: false,
                                showConfirmButton: false
                            });

                            // Set all Upload Images inputs to empty
                            // this.filterData.reset();
                            console.log("onFinish");
                        },
                    }).then(function (response) {
                        // this.$inertia.reload();
                    }.bind(this));
            }
        },
        created:function(){
            console.log("Diagnosis");
            console.log(this.Diagnosis);
        }
        
    }
</script>
