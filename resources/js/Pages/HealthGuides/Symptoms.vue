<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">
                <div class="row px-3">
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <inertia-link :href="route(UserRoles[0]+'.AdminHealthGuide')" :active="route().current(UserRoles[0]+'.AdminHealthGuide')" class=""> <span class="color-5-orange">Systems</span> </inertia-link>
                            </li>
                        </ol>
                    </div>
                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            Symptoms
                        </h5>
                    </div>
                    
                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="input-group input-group-sm mb-3">
                            <!-- Shot Button if Admin or iHealth Content Manager -->
                            <div v-if="this.UserRoles.includes('Admin') || this.UserRoles.includes('IHealthContentManager')" class="input-group-prepend">
                                <button type="button" class="btn btn-outline-light border-left-0 bg-white" data-toggle="modal" data-target="#createSymptomsModal" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-left:1px solid #ced4da;">
                                    <i class="fas fa-plus color-5-orange"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control border-right-0 searching" v-model="params.searchSymptoms" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon2">
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
                            <thead >
                                <tr class="color-6-red">
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('name')">
                                            Symptom Name
                                            <i v-if="params.searchSymptoms === null && params.direction === null || params.field != 'name' " class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'name' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'name' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th role="button" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('local_term')">
                                        Local Terminology
                                            <i v-if="params.searchSymptoms === null && params.direction === null || params.field != 'local_term' " class="w-4 h-4 fas fa-sort"></i>
                                        <i v-if="params.field === 'local_term' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                        <i v-if="params.field === 'local_term' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('local_term')">
                                        Parent Symptom
                                        </span>
                                    </th>
                                    <th class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('local_term')">
                                        System
                                        </span>
                                    </th>
                                    <th class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('local_term')">
                                        Action
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="nomatches" hidden="true" v-if="Symptoms.data.length == 0">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <h6 class="text-danger">
                                            No matching results
                                        </h6>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody >
                                <tr class="color-6-red" v-for="symptoms in Symptoms.data" :key="symptoms">
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="symptoms.name"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="symptoms.local_term"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;">
                                        <span class="color-5-orange" v-if="symptoms.parent_symptom != null" v-html="symptoms.parent_symptom.name"></span>
                                        <span class="color-6-red font-italic text-xs" v-if="symptoms.parent_symptom == null">No Parent Symptom</span>
                                    </td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;">
                                        <span v-for="sys in symptoms.organ_systems_symptoms" :key="sys" class="badge badge-pill badge-custom-inverted mr-1">
                                            {{ sys.organ_systems[0].name }}
                                        </span>
                                    </td>
                                    <td class="border-0">
                                        <a @click="viewSpecificSymptom(symptoms)" data-toggle="modal" data-target="#symptomsModal" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm">View <i class="fas fa-eye"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 mt-2 pl-0">
                        <pagination :links="Symptoms.links" :current_page="Symptoms.current_page" :prev_url="Symptoms.prev_page_url" :next_url="Symptoms.next_page_url" :total_page="Symptoms.last_page" :path="Symptoms.path"></pagination>
                    </div>
                </div>
            </section>
        </div>

        <!-- Viewing and Editing Modal-->
		<modal :id="symptomsModal" :maxWidth="'md'">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="flex-column">
                        <span class="text-xs my-0" v-if="editSpecificSymptomData == true"> Currently Editing </span>
                        <h6 class="font-weight-bolder color-6-red text-sm my-0"> Symptom Name </h6>
                        <h5 class="modal-title" style="max-width: 150px; word-wrap: break-word;" v-if="specificSymptomData != null"> {{ specificSymptomData.name }} </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-show="editSpecificSymptomData == false">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <button type="button" class="close" v-show="editSpecificSymptomData == true" @click="editSpecificSymptomData = false">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" v-if="specificSymptomData != null && editSpecificSymptomData == false">
                        <health-symptom-modal-content :specificSymptom="specificSymptomData"></health-symptom-modal-content>
                    </div>

                    <div class="row" v-if="editSpecificSymptomData == true">
                        <div class="col-12">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Images </p>
                            <UploadImages @change="handleImages" :max="5" maxError="Max files exceed" fileError="Please upload an image file. (.PNG, .JPEG, .JPG)" uploadMsg="Add files to attach" ref="resetPics"/>
                        </div>

                        <div class="col-12 mt-2">
                            <div class="row mb-1 justify-content-between">
                                <span class="col text-sm font-weight-light color-6-red mb-0"> Current Images </span>
                                <span v-if="editSpecificSymptomForm.editedSpecificSymptomFormMediaArr.length > 0" class="col text-right text-sm font-weight-light text-dark mb-0 text-gray-500 cursor-pointer mr-0" @click="undoDeletedHCCurrentMedia()">
                                    Undo <i class="fas fa-undo"></i>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col" v-for="(img,index) in editSpecificSymptomForm.media_symptoms" :key="index">
                                    <img style="width:100px;height:100px;object-fit:contain;" class="img-fluid border-bottom" :src="'/storage/'+img.path_name" />
                                    <button type="button" class="btn btn-outline-danger btn-xs rounded-0 text-xs" style="width:100px;display:block;" @click="deleteHCCurrentMedia(index,img.id)">
                                        Delete <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Symptom Name </p>
                            <input type="text" v-model="editSpecificSymptomForm.name" class="form-control rounded-0">
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Local Terminology </p>
                            <input type="text" v-model="editSpecificSymptomForm.local_term" class="form-control rounded-0">
                        </div>
                        
                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Parent Symptom </p>
                            <Multiselect
                            v-model="parentSymptomTags.value"
                            v-bind="parentSymptomTags"
                            :searchable="true"
                            :close-on-select="true"
                            :options="parentSymptomTags.options"
                            @select="symptomParentSymptomselectValue()"
                            @deselect="symptomParentSymptomdeselectValue()"
                            @clear="clearSymptomsValues()"
                            ></Multiselect>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> System </p>
                            <Multiselect
                            v-model="systemsTags.value"
                            v-bind="systemsTags"
                            :searchable="true"
                            :close-on-select="false"
                            :options="systemsTags.options"
                            @select="selectValue()"
                            @deselect="deselectValue()"
                            ></Multiselect>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" v-if="this.UserRoles.includes('Admin') || this.UserRoles.includes('IHealthContentManager')">
                    <button v-show="editSpecificSymptomData == true" @click="editSpecificSymptomData = false" type="button" class="btn btn-outline-secondary btn-sm rounded-pill">Cancel <i class="fas fa-redo"></i> </button>
                    <button v-show="editSpecificSymptomData == false" class="btn btn-outline-brown ml-auto btn-sm rounded-pill" @click="edit()">Edit <i class="fas fa-pen"></i></button>
                    <button v-show="editSpecificSymptomData == true" class="btn btn-outline-brown ml-auto btn-sm rounded-pill" @click="saveEditedSymptom()">Save <i class="fas fa-check"></i></button>
                </div>
            </div>
        </modal>

        <!-- Adding Symptoms Modal -->
		<modal :id="createSymptomsModal" :maxWidth="'md'">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" > Create a Symptom </h5>
                    <button type="button" @click="cancelCreateSymptom()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Images </p>
                            <UploadImages @change="createSymptomHandleImages" :max="5" maxError="Max files exceed" fileError="Please upload an image file. (.PNG, .JPEG, .JPG)" uploadMsg="Add files to attach" ref="resetPics"/>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Symptom Name </p>
                            <input type="text" v-model="createSymptomForm.name" class="form-control rounded-0">
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Local Terminology </p>
                            <input type="text" v-model="createSymptomForm.local_term" class="form-control rounded-0">
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> Parent Symptom </p>
                            <Multiselect
                            v-model="createSymptomParentSymptomTags.value"
                            v-bind="createSymptomParentSymptomTags"
                            :searchable="true"
                            :close-on-select="true"
                            :options="createSymptomParentSymptomTags.options"
                            @select="createSymptomParentSymptomselectValue()"
                            @deselect="createSymptomParentSymptomdeselectValue()"
                            ></Multiselect>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="text-sm font-weight-light color-6-red mb-0"> System </p>
                            <Multiselect
                            v-model="createSymptomSystemsTags.value"
                            v-bind="createSymptomSystemsTags"
                            :searchable="true"
                            :close-on-select="false"
                            :options="createSymptomSystemsTags.options"
                            @select="createSymptomselectValue()"
                            @deselect="createSymptomdeselectValue()"
                            ></Multiselect>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill" data-dismiss="modal" type="button" @click="cancelCreateSymptom()">Cancel <i class="fas fa-redo"></i> </button>
                    <button class="btn btn-outline-brown ml-auto btn-sm rounded-pill" @click="createSymptom()">Create <i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </modal>
    </admin-layout>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'
    import Pagination from '@/CustomComponents/GeneralComp/Pagination.vue'
    import Modal from '@/Jetstream/Modal'
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import HealthSymptomModalContent from '@/CustomComponents/HealthGuideComp/HealthSymptomModalContent.vue'
    import Multiselect from '@vueform/multiselect'
	import UploadImages from "vue-upload-drop-images"
    import Swal from 'sweetalert2'

    export default {
        components: {
            AdminLayout,
            Modal,
            Pagination,
            zoomOnHover: zoomOnHover,
            HealthSymptomModalContent,
            Multiselect,
            UploadImages,
            Swal,
        },
        props: [ 'Symptoms', 'Systems', 'AllSymptoms', 'Filters', 'UserRoles'],
        data()  {
            return {
                params:{
                    searchSymptoms: this.Filters.searchSymptoms,
                    field: this.Filters.field,
                    direction: this.Filters.direction,
                },
                editor: ClassicEditor,
                symptomsModal: 'symptomsModal',
                createSymptomsModal: 'createSymptomsModal',
                specificSymptomData: null,
                editSpecificSymptomData: false,
				editSpecificSymptomForm: this.$inertia.form({
                    name: null,
                    local_term: "",
                    parent_symptom: {
                        id:null,
                        name:null
                    },
                    editedSpecificSymptomFormMediaArr: [],
                }),
				createSymptomForm: this.$inertia.form({
                    name: null,
                    local_term: "",
                    parent_symptom: null,
                    attached_media_symptoms: [],
                }),
                systemsTags: {
                    mode: 'tags',
                    value: [],
                    closeOnSelect: false,
                    options: [],
                    searchable: true,
                    object: true,
                },
                parentSymptomTags: {
                    value: [],
                    options: [],
                    object: true,
                    closeOnSelect: false,
                    searchable: true,

                },
                createSymptomSystemsTags: {
                    mode: 'tags',
                    value: [],
                    closeOnSelect: false,
                    options: [],
                    searchable: true,
                    object: true,
                },
                createSymptomParentSymptomTags: {
                    value: [],
                    options: [],
                    searchable: true,
                    object: true,
                },
                searching_results: false,
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.Symptoms'), params, {
                            replace: true,
                            preserveState: false,
                            onSuccess: (res) => {
                                console.log("xxxxSymptoms");
                                // this.searching_data = res.props.Symptoms.data;
                                if(res.props.Symptoms.data.length == 0){
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
            initializedSystem(){
                // Create System Symptoms
                var systemsArrDefValues = [];
                var systemsArrOptions = [];
                $.each(this.Systems, function(key,value) {
                    systemsArrOptions.push({
                        'value':value.id,
                        'id': value.id,
                        'organ_system_id': value.id,
                        'label': value.name,
                        'local_term': value.local_term,
                        'pivot': value.pivot,
                    });
                });
                this.createSymptomSystemsTags.value = systemsArrDefValues;
                this.createSymptomSystemsTags.options = systemsArrOptions;
            },
            initializedParentSymptoms(){
               // Create Parent Symptoms   
                var createSymptomParentSymptomTagsDefValues = [];
                var createSymptomParentSymptomTagsOptions = [];
                $.each(this.AllSymptoms, function(key,value) {
                    createSymptomParentSymptomTagsOptions.push({
                        'value':value.id,
                        'id': value.id,
                        'symptom_id': value.id,
                        'label': value.name,
                        'local_term': value.local_term,
                    });
                });
                this.createSymptomParentSymptomTags.value = createSymptomParentSymptomTagsDefValues; 
                this.createSymptomParentSymptomTags.options = createSymptomParentSymptomTagsOptions; 
            },
            createSymptom(){
                console.log(this.createSymptomForm);
                if(this.UserRoles.includes("IHealthContentManager")){
                    console.log("IHealthContentManager");
                    this.$inertia.post(route('IHealthContentManager.CreateSymptom'), this.createSymptomForm, {
                        forceFormData: true,
                        onSuccess: (res) => {
                            $('#createSymptomsModal').modal('hide');
                            console.log("onSuccess");
                        },
                        onFinish: () => {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your edited symptom has been saved successfully.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showCancelButton: false,
                                showConfirmButton: false
                            });

                            // Set all Upload Images inputs to empty
                            this.$refs.resetPics.reset();
                            this.createSymptomForm.reset();
                            this.createSymptomSystemsTags.value = null;
                            this.createSymptomParentSymptomTags.value = null;
                            console.log("onFinish");
                        },
                    }).then(function (response) {
                        this.$inertia.reload();
                    }.bind(this));

                }

                if(this.UserRoles.includes("Admin")){
                    console.log("Admin");
                    this.$inertia.post(route('Admin.CreateSymptom'), this.createSymptomForm, {
                        forceFormData: true,
                        onSuccess: (res) => {
                            $('#createSymptomsModal').modal('hide');
                            console.log("onSuccess");
                        },
                        onFinish: () => {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your edited symptom has been saved successfully.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showCancelButton: false,
                                showConfirmButton: false
                            });

                            // Set all Upload Images inputs to empty
                            this.$refs.resetPics.reset();
                            this.createSymptomForm.reset();
                            this.createSymptomSystemsTags.value = null;
                            this.createSymptomParentSymptomTags.value = null;
                            console.log("onFinish");
                        },
                    }).then(function (response) {
                        this.$inertia.reload();
                    }.bind(this));

                }
            },
            cancelCreateSymptom(){
                this.$refs.resetPics.reset();
                this.createSymptomForm.reset();
                this.createSymptomSystemsTags.value = null;
                this.createSymptomParentSymptomTags.value = null;
            },
            viewSpecificSymptom(symptom){
                this.specificSymptomData = symptom;
                console.log("viewSpecificSymptom");
                console.log(this.specificSymptomData);
                // Initialize
                var systemsArrDefValues = [];
                var systemsArrOptions = [];

                // Getting Organ System Symptoms Default/Initiatialized Values
                $.each(symptom.organ_systems_symptoms, function(key,value) {
                    // console.log("symptom.organ_systems_symptoms");
                    // console.log(value);
                    $.each(value.organ_systems, function(k,v){
                        systemsArrDefValues.push({
                            'value':v.id,
                            'id': v.id,
                            'organ_system_id': v.id,
                            'label': v.name,
                            'local_term': v.local_term,
                        });
                    })
                });

                // Getting Organ System Options
                $.each(this.Systems, function(key,value) {
                    systemsArrOptions.push({
                        'value':value.id,
                        'id': value.id,
                        'organ_system_id': value.id,
                        'label': value.name,
                        'local_term': value.local_term,
                        'pivot': value.pivot,
                    });
                });
                this.systemsTags.value = systemsArrDefValues;
                this.systemsTags.options = systemsArrOptions;

                // Initialize
                var parentSymptomTagsOptions = [];
                // Initialize Parent Symptom value
                if(this.specificSymptomData.parent_symptom != null){
                    this.parentSymptomTags.value = {
                        'value':this.specificSymptomData.parent_symptom,
                        'id': this.specificSymptomData.parent_symptom.id,
                        'symptom_id': this.specificSymptomData.parent_symptom.id,
                        'label': this.specificSymptomData.parent_symptom.name,
                        'local_term': this.specificSymptomData.parent_symptom.local_term,
                    };
                }

                // Get Parent Symptom Options
                $.each(this.AllSymptoms, function(key,value) {
                    parentSymptomTagsOptions.push({
                        'value':value.id,
                        'id': value.id,
                        'symptom_id': value.id,
                        'label': value.name,
                        'local_term': value.local_term,
                    });
                });
                this.parentSymptomTags.options = parentSymptomTagsOptions;
            },
            edit(){
                // Show edit div once edit button is clicked
                console.log("Clicked Edit");
                this.editSpecificSymptomData = true;

                // Copy properties
                this.editSpecificSymptomForm = JSON.parse(JSON.stringify(this.specificSymptomData));
                this.editSpecificSymptomForm.editedSpecificSymptomFormMediaArr = [];
                this.editSpecificSymptomForm.attached_media_symptoms = [];

                console.log(this.editSpecificSymptomForm);
                // Append if media hc exists
                if(this.editSpecificSymptomForm.media_symptoms.length > 0){
                    console.log(this.editSpecificSymptomForm.media_symptoms);
                }
                
                var mediaHC = this.specificSymptomData.media_symptoms; 
                var obj = []; 

                // handleImages
                // Loop
                for(var x=0; x < mediaHC.length; x++){
                    obj.push({
                        File : {
                            name:split(mediaHC[x].path_name),
                            size:'',
                            type:'image/png',
                            lastModified: ''
                        }
                    })
                }
                // Split "/"
                function split(path){
                    var res = path.split("/");
                    return res[3];
                }
            },
            deleteHCCurrentMedia(index, media_id){
				console.log(this.editSpecificSymptomForm.media_symptoms);
                // push editedSpecificSymptomFormMediaArr
                function findObjectByKey(array, key, value) {
                    for (var i = 0; i < array.length; i++) {
                        if (array[i][key] === value) {
                            return array[i];
                        }
                    }
                    return null;
                }
                var obj = findObjectByKey(this.editSpecificSymptomForm.media_symptoms, 'id', media_id);
                // push object containing media_id editSpecificSymptomForm
                this.editSpecificSymptomForm.editedSpecificSymptomFormMediaArr.push(obj);
				console.log(this.editSpecificSymptomForm.editedSpecificSymptomFormMediaArr);
                // delete to media_symptoms
				this.editSpecificSymptomForm.media_symptoms.splice(index, 1);
            },
            undoDeletedHCCurrentMedia(index, media_id){
                // push back to media_symptoms
				this.editSpecificSymptomForm.media_symptoms.push(this.editSpecificSymptomForm.editedSpecificSymptomFormMediaArr[this.editSpecificSymptomForm.editedSpecificSymptomFormMediaArr.length - 1]);
                this.editSpecificSymptomForm.editedSpecificSymptomFormMediaArr.pop();
                console.log(this.editSpecificSymptomForm.editedSpecificSymptomFormMediaArr);
            },
			handleImages(files){
                console.log(files);
                for(var i=0;i<files.length;i++){
                    this.editSpecificSymptomForm.attached_media_symptoms.push(files[i]);
                }
			},
            createSymptomHandleImages(files){
                console.log(files);
                for(var i=0;i<files.length;i++){
                    this.createSymptomForm.attached_media_symptoms.push(files[i]);
                }
            },
            createSymptomselectValue(){
                console.log(this.systemsTags.value);
                this.createSymptomForm.organ_systems_symptoms = this.createSymptomSystemsTags.value;
            },
            createSymptomdeselectValue(value){
                console.log(value);
                this.createSymptomForm.organ_systems_symptoms = this.createSymptomSystemsTags.value;
            },
            createSymptomParentSymptomselectValue(){
                console.log(this.systemsTags.value);
                this.createSymptomForm.parent_symptom = this.createSymptomParentSymptomTags.value;
            },
            createSymptomParentSymptomdeselectValue(value){
                console.log(value);
                this.createSymptomForm.parent_symptom = this.createSymptomParentSymptomTags.value;
            },
            selectValue(){
                console.log(this.systemsTags.value);
                this.editSpecificSymptomForm.organ_systems_symptoms = this.systemsTags.value;
            },
            deselectValue(value){
                console.log(value);
                this.editSpecificSymptomForm.organ_systems_symptoms = this.systemsTags.value;
            },
            symptomParentSymptomselectValue(){
                console.log("symptomParentSymptomselectValue");
                console.log(this.parentSymptomTags.value);
                this.editSpecificSymptomForm.parent_symptom = this.parentSymptomTags.value;
            },
            symptomParentSymptomdeselectValue(value){
                this.editSpecificSymptomForm.parent_symptom = this.parentSymptomTags.value;
            },
            clearSymptomsValues(){
                this.editSpecificSymptomForm.parent_symptom = null;
                this.specificSymptomData.parent_symptom = null;
            },
			saveEditedSymptom(){
                console.log(this.editSpecificSymptomForm);
                
                if(this.UserRoles.includes("IHealthContentManager")){
                    this.$inertia.post(route('IHealthContentManager.SaveEditedSymptom'), this.editSpecificSymptomForm, {
                        forceFormData: true,
                        onSuccess: (res) => {
                            $('#symptomsModal').modal('hide');
                            console.log("onSuccess");
                        },
                        onFinish: () => {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your edited symptom has been saved successfully.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showCancelButton: false,
                                showConfirmButton: false
                            });

                            // Set default view in HC Modal
                            this.editSpecificSymptomData = false;

                            // Set all Upload Images inputs to empty
                            this.$refs.resetPics.reset();
                            console.log("onFinish");
                        },
                    }).then(function (response) {
                        this.$inertia.reload();
                    }.bind(this));
                }

                if(this.UserRoles.includes("Admin")){
                    this.$inertia.post(route('Admin.SaveEditedSymptom'), this.editSpecificSymptomForm, {
                        forceFormData: true,
                        onSuccess: (res) => {
                            $('#symptomsModal').modal('hide');
                            console.log("onSuccess");
                        },
                        onFinish: () => {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your edited symptom has been saved successfully.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showCancelButton: false,
                                showConfirmButton: false
                            });

                            // Set default view in HC Modal
                            this.editSpecificSymptomData = false;

                            // Set all Upload Images inputs to empty
                            this.$refs.resetPics.reset();
                            console.log("onFinish");
                        },
                    }).then(function (response) {
                        this.$inertia.reload();
                    }.bind(this));
                }
			},
            sort(field){
                this.params.field = field;
                this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
            }
        },
        created: function(){
            console.log(this.Symptoms);
            this.initializedSystem();
            this.initializedParentSymptoms();
        }
    }
</script>
<style src="@vueform/multiselect/themes/default.css"></style>

