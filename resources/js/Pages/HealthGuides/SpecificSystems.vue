<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">
                <div class="row px-3">
                    <div class="col-12 ">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <li class="breadcrumb-item">
                                <inertia-link :href="route(UserRoles[0]+'.AdminHealthGuide')" :active="route().current(UserRoles[0]+'.AdminHealthGuide')" class=""> <span class="color-5-orange"> Systems </span> </inertia-link>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <inertia-link href="#">
                                    <span class="color-5-orange"> {{ Type }} </span>
                                </inertia-link>
                            </li>
                        </ol>
                    </div>

                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            {{ Type }}
                        </h5>
                    </div>
                    

                    <!-- Symptoms List -->
                    <div class="col-2 text-right ml-auto" v-if="this.showCreateButton == true">
                        <button class="btn btn-outline-brown btn-sm" data-toggle="modal" data-target="#createHealthConditionModal" @click="initializedSymptoms()" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">
                            Create &nbsp; <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control border-right-0 searching" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border:1px solid #ced4da;" v-model="searchSystems" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon2">
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
                        <table class="table bg-light text-sm">
                            <thead>
                                <tr class="color-6-red">
                                    <th class="pt-3 pb-1 border-0"> Health Condition </th>
                                    <th class="pt-3 pb-1 border-0"> How to Diagnose </th>
                                    <th class="pt-3 pb-1 border-0"> Local Terminology </th>
                                    <th class="pt-3 pb-1 border-0"> Symptoms </th>
                                    <th class="pt-3 pb-1 border-0"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="color-6-red" v-for="hc in HealthConditions.data" :key="hc">
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="hc.name"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="hc.how_to_diganose"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;" v-html="hc.local_term"></td>
                                    <td class="block text-wrap text-sm border-0" style="max-width: 150px; word-wrap: break-word;">
                                        <span class="cursor-pointer" v-for="(items, index) in hc.symptoms" :key="index">
                                            <span class="badge badge-warning text-wrap font-weight-normal mx-1">
                                            {{ items.name }}
                                            </span>
                                            <span v-if="index+1 < hc.symptoms.length" v-html="','"></span>
                                        </span>
                                    </td>
                                    <td  class="block text-wrap text-sm border-0" >
                                        <a @click="viewSpecificHC(hc)" data-toggle="modal" data-target="#healthConditionModal" href="#" class="badge badge-pill badge-custom-yellow text-dark text-decoration-none float-left text-sm">View <i class="fas fa-eye"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 mt-2 pl-0">
                        <pagination :links="HealthConditions.links" :current_page="HealthConditions.current_page" :prev_url="HealthConditions.prev_page_url" :next_url="HealthConditions.next_page_url" :total_page="HealthConditions.last_page" :path="HealthConditions.path"></pagination>
                    </div>
                </div>
            </section>
        </div>
        <!-- Content Wrapper -->

		<!-- Modal for Creating Health Condition -->
		<modal :id="createHealthConditionModal" :maxWidth="'xl'">
            <div class="modal-content">
                <form @submit.prevent="SaveCreatedHC">

                    <div class="modal-header flex-column border-0">
                        <div class="container px-0">
                            <div class="row justify-content-start">
                                <div class="col-10 mb-0">
                                    <h3 class="my-0">Create Health Condition</h3>
                                    <p class="text-sm color-6-red">{{ Type }}</p>
                                </div>

                                <div class="col-2 mb-0" v>
                                    <button type="button" class="close" @click="resetCreateSpecificHCForm()" aria-label="Close">
                                        <span @click="resetCreateSpecificHCForm()" aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">   
                            <div class="col-12">
                                <jet-validation-errors class="mb-3" />
                                <div v-if="status" class="alert alert-success mb-3 rounded-0" role="alert">
                                    {{ status }}
                                </div>
                            </div> 
                            <div class="col-6">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Health Condition </p>
                                    <input type="text" class="border-radius-10rem form-control" v-model="createSpecificHCForm.name">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Local Terminology </p>
                                    <input type="text" class="border-radius-10rem form-control" v-model="createSpecificHCForm.local_term">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Zoonotic </p>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" v-model="createSpecificHCForm.zoonotic" :value="createSpecificHCForm.zoonotic" @change="checkbox()" id="zoonoticCheck">
                                        <label class="form-check-label" for="zoonoticCheck">Is zoonotic?</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Description </p>
                                    <ckeditor class="border-radius-10rem" :editor="editor" v-model="createSpecificHCForm.description" :config="editorConfig" tag-name="textarea"></ckeditor>
                                </div>

                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> How to Diagnose </p>
                                    <ckeditor :editor="editor" v-model="createSpecificHCForm.how_to_diganose" :config="editorConfig" tag-name="textarea"></ckeditor>
                                </div>

                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Treatment </p>
                                    <ckeditor :editor="editor"  v-model="createSpecificHCForm.treatment" :config="editorConfig" tag-name="textarea"></ckeditor>
                                </div>

                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Advice to Farmer </p>
                                    <ckeditor :editor="editor"  v-model="createSpecificHCForm.advice_to_farmer" :config="editorConfig" tag-name="textarea"></ckeditor>
                                </div>

                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Differential Symptoms </p>

                                    <div>
                                        <Multiselect
                                        v-model="differentialSymptomsTags.value"
                                        mode="tags"
                                        :searchable="true"
                                        :create-option="false"
                                        :options="differentialSymptomsTags.options"
                                        @select="selectValue_createSpecificHCForm_differentialSymptoms()"
                                        @deselect="deselectValue_createSpecificHCForm_differentialSymptoms()"
                                        />
                                    </div>

                                    <div v-if="this.HasDupps == true" class="alert alert-danger text-xs px-1 py-1 mt-1" role="alert">
                                        There are duplicate symptoms entered in differential symptoms and symptoms. Kindly correct these values.
                                    </div>
                                </div>

                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Symptoms </p>

                                    <div>
                                        <Multiselect
                                        v-model="symptomsTags.value"
                                        mode="tags"
                                        :searchable="true"
                                        :create-option="false"
                                        :options="symptomsTags.options"
                                        @select="selectValue_createSpecificHCForm()"
                                        @deselect="deselectValue_createSpecificHCForm()"
                                        />
                                    </div>

                                    <div v-if="this.HasDupps == true" class="alert alert-danger text-xs px-1 py-1 mt-1" role="alert">
                                        There are duplicate symptoms entered in differential symptoms and symptoms. Kindly correct these values.
                                    </div>
                                </div>

                                <div class="form-group">
                                    <p class="text-sm font-weight-normal color-6-red mb-0"> Healthcare Interventions </p>
                                    <table class="table table-borderless text-sm">
                                        <thead>
                                            <tr>
                                                <th class="py-1 px-0">Description</th>
                                                <th class="py-1 px-0">For Licensed Vet?</th>
                                                <th class="py-1 px-0">For Pregnant?</th>
                                                <th class="py-1 px-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(items, index) in createSpecificHCForm.hc_interventions" :key="index">
                                                <td class="py-1 pl-0">
                                                    <textarea name="" :id="index+'desc'" v-model="items.description" class="form-control isEdit" disabled></textarea>
                                                </td>

                                                <td class="py-1 pl-0">
                                                    <div :id="index+'nl_showed'">
                                                        <span v-if="items.need_license == 1 && editSpecificHealthConditionsIntervention == false"> Yes </span>
                                                        <span v-if="items.need_license == 0 && editSpecificHealthConditionsIntervention == false"> No </span>
                                                    </div>
                                                    <div :id="index+'nl_edit'" hidden>
                                                        <select name="" id="" class="form-control" v-model="items.need_license">
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td class="py-1 pl-0">
                                                    <div :id="index+'pl_showed'">
                                                        <span v-if="items.pregnant_applicable == 1 && editSpecificHealthConditionsIntervention == false"> Yes </span>
                                                        <span v-if="items.pregnant_applicable == 0 && editSpecificHealthConditionsIntervention == false"> No </span>
                                                    </div>
                                                    <div :id="index+'pl_edit'" hidden>
                                                        <select name="" id="" class="form-control" v-model="items.pregnant_applicable">
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td class="py-1 pl-0">
                                                    <button type="button" :id="index+'edit_button'" class="btn btn-outline-yellow btn-sm rounded-pill text-xs mr-1" @click="editSpecificHCInterventions(index,items)">
                                                        Edit <i class="fas fa-pen"></i>
                                                    </button>

                                                    <button type="button" :id="index+'saveHCI_button'" class="btn btn-outline-success btn-sm rounded-pill text-xs mr-1" @click="cancelSpecificHCInterventions(index,items)" hidden>
                                                        Save <i class="fas fa-check"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-outline-red btn-sm rounded-pill text-xs mx-1" @click="deleteHCInterventions_createSpecificHCForm(index,items.id)">
                                                        Delete <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-1 px-0" colspan="4">
                                                    <textarea placeholder="Enter an intervention..." type="text" class="form-control rounded-0" v-model="SpecificHealthConditionsInterventionAddDesc" cols="30" rows="3"></textarea>
                                                    <div class="mt-1 mr-auto">
                                                        <input type="checkbox" v-model="SpecificHealthConditionsInterventionAddNeedLicense" id="need_license" checked data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="warning" data-offstyle="secondary">
                                                        <label for="need_license" class="mx-1">Intervention needs Licensed Vet?</label>
                                                    </div>

                                                    <div class="mt-1 mr-auto">
                                                        <input type="checkbox" v-model="SpecificHealthConditionsInterventionAddPregnantApplicable" id="pregnant_applicable" checked data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="warning" data-offstyle="secondary">
                                                        <label for="pregnant_applicable" class="mx-1">Applicable for pregnant?</label>
                                                    </div>

                                                </td>
                                            </tr>
                                            <tfoot class="border-0">
                                                <td class="px-0">
                                                    <button :disabled="SpecificHealthConditionsInterventionAddDesc.length == 0 || SpecificHealthConditionsInterventionAddDesc == null" type="button" class="btn btn-outline-brown btn-sm rounded-pill mx-0" style="width:100px;" @click="addHCInterventions_createSpecificHCForm(SpecificHealthConditionsInterventionAddDesc,specificHealthConditions.id)">
                                                        Add <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>

                                            </tfoot>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12">
                                <p class="text-sm font-weight-normal text-warning mb-0"> Images </p>
                                <UploadImages @change="handleImages_createSpecificHCForm" :max="5" maxError="Max files exceed" fileError="Please upload an image file. (.PNG, .JPEG, .JPG)" uploadMsg="Add files to attach, Max: 500 KB / JPG, JPEG or PNG format" ref="resetPics"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" v-if="this.UserRoles.includes('Admin') || this.UserRoles.includes('IHealthContentManager')">
                        <button @click="resetCreateSpecificHCForm()" type="button" class="btn btn-outline-secondary btn-sm rounded-pill">Cancel <i class="fas fa-redo"></i> </button>
                        <button type="submit" class="btn btn-outline-red ml-auto btn-sm rounded-pill">Save <i class="fas fa-save"></i></button>
                    </div>
                </form>
            </div>
		</modal>

		<!-- Modal for Health Condition Content -->
		<modal :id="healthConditionModal" :maxWidth="'xl'">
            <div class="modal-content">
                <form @submit.prevent="SaveEditedHC">

                    <div class="modal-header flex-column border-0">
                        <button @click="editSpecificHealthConditions = false" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="container px-0">
                            <div class="row">
                                <div class="col-7" v-if="editSpecificHealthConditions == false">
                                    <h6 class="font-weight-bolder color-6-red text-sm my-0"> Health Condition </h6>
                                    <p class="my-0">{{ specificHealthConditions.name }}</p>
                                </div>
                                <div class="col-5" v-if="editSpecificHealthConditions == false">
                                    <h6 class="font-weight-bolder color-6-red text-sm my-0"> Local Terminology </h6>
                                    <span class="my-0" style="word-wrap: break-word;">
                                        {{ specificHealthConditions.local_term }}
                                    </span>
                                </div>
                                <div class="col-12 text-center mb-0" v-if="editSpecificHealthConditions == true">
                                    <p class="my-0 text-xs">Currently Editing</p>
                                    <h5 class="modal-title font-weight-light" id="exampleModalLabel"> <span class="color-6-red">{{ specificHealthConditions.name }}</span> </h5>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-body">
                        <div v-show="editSpecificHealthConditions == false">
                            <health-condition-modal-content :specificHealthConditions="specificHealthConditions" :specificHealthConditions_symp="specificHealthConditions_symp" :specificHealthConditions_diff_symp="specificHealthConditions_diff_symp"></health-condition-modal-content>
                        </div>
                        <div v-show="editSpecificHealthConditions == true" v-cloak>
                            <div class="row">                             

                                <div class="col-6">
                                    <div class="form-group">
                                        <p class="text-sm font-weight-normal color-6-red mb-0"> Health Condition </p>
                                        <input type="text" class="border-radius-10rem form-control" v-model="editSpecificHCForm.name">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <p class="text-sm font-weight-normal color-6-red mb-0"> Local Terminology </p>
                                        <input type="text" class="border-radius-10rem form-control" v-model="editSpecificHCForm.local_term">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <p class="text-sm font-weight-normal color-6-red mb-0"> Zoonotic </p>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" v-model="editSpecificHCForm.zoonotic" :value="editSpecificHCForm.zoonotic" @change="checkbox()" id="zoonoticCheck">
                                            <label class="form-check-label" for="zoonoticCheck">Is zoonotic?</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <p class="text-sm font-weight-normal color-6-red mb-0"> Description </p>
                                        <ckeditor class="border-radius-10rem" :editor="editor" v-model="editSpecificHCForm.description" :config="editorConfig" tag-name="textarea"></ckeditor>
                                    </div>

                                    <div class="form-group">
                                        <p class="text-sm font-weight-normal color-6-red mb-0"> How to Diagnose </p>
                                        <ckeditor :editor="editor" v-model="editSpecificHCForm.how_to_diganose" :config="editorConfig" tag-name="textarea"></ckeditor>
                                    </div>

                                    <div class="form-group">
                                        <p class="text-sm font-weight-normal color-6-red mb-0"> Treatment </p>
                                        <ckeditor :editor="editor"  v-model="editSpecificHCForm.treatment" :config="editorConfig" tag-name="textarea"></ckeditor>
                                    </div>

                                    <div class="form-group">
                                        <p class="text-sm font-weight-normal color-6-red mb-0"> Advice to Farmer </p>
                                        <ckeditor :editor="editor"  v-model="editSpecificHCForm.advice_to_farmer" :config="editorConfig" tag-name="textarea"></ckeditor>
                                    </div>

                                    <div class="form-group">
                                        <p class="text-sm font-weight-normal color-6-red mb-0"> Differential Symptoms </p>

                                        <div>
                                            <!-- v-bind="differentialSymptomsTags" -->
                                            <Multiselect
                                            v-model="differentialSymptomsTags.value"
                                            v-bind="differentialSymptomsTags"
                                            mode="tags"
                                            :searchable="true"
                                            :close-on-select="false"
                                            :options="differentialSymptomsTags.options"
                                            @select="selectValue_differentialSymptoms()"
                                            @deselect="deselectValue_differentialSymptoms()"
                                            />
                                        </div>

                                        <div v-if="this.HasDupps == true" class="alert alert-danger text-xs px-1 py-1 mt-1" role="alert">
                                            There are duplicate symptoms entered in differential symptoms and symptoms. Kindly correct these values.
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <p class="text-sm font-weight-normal color-6-red mb-0"> Symptoms </p>
                                        <div v-if="editSpecificHealthConditions == true">
                                            <Multiselect
                                            v-model="symptomsTags.value"
                                            v-bind="symptomsTags"
                                            mode="tags"
                                            :searchable="true"
                                            :close-on-select="false"
                                            :options="symptomsTags.options"
                                            @select="selectValue()"
                                            @deselect="deselectValue()"
                                            ></Multiselect>
                                        </div>
                                        
                                        <div v-if="this.HasDupps == true" class="alert alert-danger text-xs px-1 py-1 mt-1" role="alert">
                                            There are duplicate symptoms entered in differential symptoms and symptoms. Kindly correct these values.
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <p class="text-sm font-weight-normal color-6-red mb-0"> Healthcare Interventions </p>
                                        <table class="table table-borderless text-sm">
                                            <thead>
                                                <tr>
                                                    <th class="py-1 px-0">Description</th>
                                                    <th class="py-1 px-0">For Licensed Vet?</th>
                                                    <th class="py-1 px-0">For Pregnant?</th>
                                                    <th class="py-1 px-0">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(items, index) in editSpecificHCForm.hc_interventions" :key="index">
                                                    <td class="py-1 pl-0">
                                                        <textarea name="" :id="index+'desc'" v-model="items.description" class="form-control isEdit" disabled></textarea>
                                                    </td>

                                                    <td class="py-1 pl-0">
                                                        <div :id="index+'nl_showed'">
                                                            <span v-if="items.need_license == 1 && editSpecificHealthConditionsIntervention == false"> Yes </span>
                                                            <span v-if="items.need_license == 0 && editSpecificHealthConditionsIntervention == false"> No </span>
                                                        </div>
                                                        <div :id="index+'nl_edit'" hidden>
                                                            <select name="" id="" class="form-control" v-model="items.need_license">
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                            </select>
                                                        </div>
                                                    </td>

                                                    <td class="py-1 pl-0">
                                                        <div :id="index+'pl_showed'">
                                                            <span v-if="items.pregnant_applicable == 1 && editSpecificHealthConditionsIntervention == false"> Yes </span>
                                                            <span v-if="items.pregnant_applicable == 0 && editSpecificHealthConditionsIntervention == false"> No </span>
                                                        </div>
                                                        <div :id="index+'pl_edit'" hidden>
                                                            <select name="" id="" class="form-control" v-model="items.pregnant_applicable">
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                            </select>
                                                        </div>
                                                    </td>

                                                    <td class="py-1 pl-0">
                                                        <button type="button" :id="index+'edit_button'" class="btn btn-outline-yellow btn-sm rounded-pill text-xs mr-1" @click="editSpecificHCInterventions(index,items)">
                                                            Edit <i class="fas fa-pen"></i>
                                                        </button>

                                                        <button type="button" :id="index+'saveHCI_button'" class="btn btn-outline-success btn-sm rounded-pill text-xs mx-1" @click="cancelSpecificHCInterventions(index,items)" hidden>
                                                            Save <i class="fas fa-check"></i>
                                                        </button>

                                                        <button type="button" class="btn btn-outline-red btn-sm rounded-pill text-xs mx-1" @click="deleteHCInterventions(index,items.id)">
                                                            Delete <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="py-1 pl-0" colspan="4">
                                                        <textarea placeholder="Enter an intervention..." type="text" class="form-control rounded-0" v-model="SpecificHealthConditionsInterventionAddDesc" cols="30" rows="3"></textarea>
                                                        
                                                        <div class="mt-1 mr-auto">
                                                            <input type="checkbox" v-model="SpecificHealthConditionsInterventionAddNeedLicense" id="need_license1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="warning" data-offstyle="secondary">
                                                            <label for="need_license1" class="mx-1">Intervention needs Licensed Vet?</label>
                                                        </div>

                                                        <div class="mt-1 mr-auto">
                                                            <input type="checkbox" v-model="SpecificHealthConditionsInterventionAddPregnantApplicable" id="pregnant_applicable1" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="warning" data-offstyle="secondary">
                                                            <label for="pregnant_applicable1" class="mx-1">Applicable for pregnant?</label>
                                                        </div>

                                                    </td>
                                                </tr>
                                                <tfoot class="border-0">
                                                    <td class="pl-0">
                                                        <button :disabled="SpecificHealthConditionsInterventionAddDesc.length == 0 || SpecificHealthConditionsInterventionAddDesc == null" type="button" class="btn btn-outline-brown btn-sm rounded-pill mx-0" style="width:100px;" @click="addHCInterventions(SpecificHealthConditionsInterventionAddDesc,specificHealthConditions.id)">
                                                            Add <i class="fas fa-plus"></i>
                                                        </button>
                                                    </td>

                                                </tfoot>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <p class="text-sm font-weight-normal text-warning mb-0"> Images </p>
                                    <UploadImages @change="handleImages" :max="5" maxError="Max files exceed" fileError="Please upload an image file. (.PNG, .JPEG, .JPG)" uploadMsg="Add files to attach, Max: 500 KB / JPG, JPEG or PNG format" ref="resetPics"/>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="row mb-1 justify-content-between">
                                        <span class="col text-sm font-weight-normal text-warning mb-0"> Current Images </span>
                                        <span v-if="editSpecificHCForm.editedSpecificHCFormMediaArr.length > 0" class="col text-right text-sm font-weight-normal text-dark mb-0 text-gray-500 cursor-pointer mr-0" @click="undoDeletedHCCurrentMedia()">
                                            Undo <i class="fas fa-undo"></i>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <div class="col" v-for="(img,index) in editSpecificHCForm.media_health_condition" :key="index">
                                            <img style="width:100px;height:100px;object-fit:contain;" class="img-fluid border-bottom" :src="'/storage/'+img.path_name" />
                                            <button type="button" class="btn btn-outline-brown btn-xs rounded-pill mt-2 text-xs" style="width:100px;display:block;" @click="deleteHCCurrentMedia(index,img.id)">
                                                Remove <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" v-if="this.UserRoles.includes('Admin') || this.UserRoles.includes('IHealthContentManager')">
                        <!-- Show when edit is editSpecificHealthConditions == true -->
                        <button v-show="editSpecificHealthConditions == true" @click="editSpecificHealthConditions = false" type="button" class="btn btn-outline-secondary btn-sm rounded-pill">Cancel <i class="fas fa-redo"></i> </button>
                        
                        <!-- Show when edit is editSpecificHealthConditions == false -->
                        <button v-show="editSpecificHealthConditions == false" @click="edit()" type="button" class="btn btn-outline-brown ml-auto btn-sm rounded-pill">Edit <i class="fas fa-pen"></i></button>
                        <!-- Show when edit is editSpecificHealthConditions == true -->
                        <button v-show="editSpecificHealthConditions == true" :disabled="this.HasDupps == true" type="submit" class="btn btn-outline-red ml-auto btn-sm rounded-pill">Save <i class="fas fa-save"></i></button>
                    </div>
                </form>
            </div>
		</modal>
    </admin-layout>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'
    import Modal from '@/Jetstream/Modal'
    import HealthConditionModalContent from '@/CustomComponents/HealthGuideComp/HealthConditionModalContent.vue'
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
	import UploadImages from "vue-upload-drop-images"
    import Swal from 'sweetalert2'
    import Multiselect from '@vueform/multiselect'
    import Pagination from '@/CustomComponents/GeneralComp/Pagination.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'


    export default {
        components: {
            AdminLayout,
            Modal,
            HealthConditionModalContent,
            UploadImages,
            Multiselect,
            Pagination,
            JetValidationErrors,
        },
        props: [ 'Identification', 'Type', 'OrganSystemID', 'HealthConditions', 'Symptoms', 'HealthCareInterventions', 'UserRoles' ],
        data() {
            return {
                healthConditionModal: 'healthConditionModal',
                createHealthConditionModal: 'createHealthConditionModal',
                searchHealthConditions: null,
                specificHealthConditions: [],
                specificHealthConditions_symp: [],
                specificHealthConditions_diff_symp: [],
                editSpecificHealthConditions: false,
                cancelSpecificHealthConditions: false,
                editSpecificHealthConditionsIntervention: false,
                SpecificHealthConditionsInterventionAddDesc: "",
                SpecificHealthConditionsInterventionAddNeedLicense: "",
                SpecificHealthConditionsZoonotic: "",
                SpecificHealthConditionsInterventionAddPregnantApplicable: "",
                editor: ClassicEditor,
				editSpecificHCForm: this.$inertia.form({
                    editedSpecificHCFormMediaArr: [],
                }),
                createSpecificHCForm: this.$inertia.form({
                    createSpecificHCFormMediaArr: [],
                    hc_interventions: [],
                    organ_system_id: this.OrganSystemID,
                    attached_media_health_condition: [],
                    name: null,
                    local_term: null,
                    zoonotic: null,
                    description: '',
                    how_to_diganose: '',
                    treatment: '',
                    advice_to_farmer: '',
                    hc_interventions: [],
                }),
                createSpecificHCFormErrors: [],
                editorData: null,
                editorConfig:null,
                symptomsTags: {
                    mode: 'tags',
                    value: [],
                    closeOnSelect: false,
                    options: [],
                    searchable: true,
                    object: true,
                },
                differentialSymptomsTags: {
                    mode: 'tags',
                    value: [],
                    closeOnSelect: false,
                    options: [],
                    searchable: true,
                    object: true,
                },
                interventionsArray:[],
                HasDupps: false,
                HasDuppsArray: [],
                showCreateButton: false,
            }
        },
        methods: {
            hasValidRoleForCreateBtn(roles_arr){
                var hasIHealthContentManager = roles_arr.includes('IHealthContentManager');
                var hasAdmin = roles_arr.includes('Admin');
                if(hasIHealthContentManager === true){
                    console.log("IFodderContentManager");
                    this.showCreateButton = true;
                }else if(hasAdmin === true){
                    console.log("Admin");
                    this.showCreateButton = true;
                }else{
                    this.showCreateButton = false;
                }
                
            },
            viewSpecificHC(HC){
                this.specificHealthConditions = HC;
				console.log(this.specificHealthConditions);
                console.log(this.specificHealthConditions.zoonotic);
                // Getting Symptoms Default/Initiatialized Values
                var symptomsArrDefValues = [];
                var symptomsArrOptions = [];

                var diff_SymptomsArrDefValues = [];
                
                console.log("HC.symptoms");
                console.log(HC.symptoms);
                $.each(this.Symptoms, function(key,value) {
                    symptomsArrOptions.push({
                        'value':value.id,
                        'id': value.id,
                        'label': value.name,
                        'local_term': value.local_term,
                        'pivot': value.pivot,
                    });
                });

                $.each(HC.symptoms.filter(
                    function (el) { 
                        // return el.pivot.differential == 1 ;
                        if(el.pivot.differential == 1){
                            diff_SymptomsArrDefValues.push({
                                'value':el.id,
                                'id': el.id,
                                'label': el.name,
                                'local_term': el.local_term,
                                'pivot': el.pivot,
                            });
                            // console.log("el");+
                            // console.log(el);+
                        }else{
                            symptomsArrDefValues.push({
                                'value':el.id,
                                'id': el.id,
                                'label': el.name,
                                'local_term': el.local_term,
                                'pivot': el.pivot,
                            });
                        }
                    })
                );
                this.specificHealthConditions_symp = symptomsArrDefValues;
                this.specificHealthConditions_diff_symp = diff_SymptomsArrDefValues;

                this.symptomsTags.value = symptomsArrDefValues;
                this.symptomsTags.options = symptomsArrOptions;
                this.differentialSymptomsTags.value = diff_SymptomsArrDefValues;
                this.differentialSymptomsTags.options = symptomsArrOptions;
                
            },
            initializedSymptoms(){
                // Getting Symptoms Default/Initiatialized Values
                var symptomsArrDefValues = [];
                var symptomsArrOptions = [];

                $.each(this.Symptoms, function(key,value) {
                    symptomsArrOptions.push({
                        'value':value.id,
                        'id': value.id,
                        'label': value.name,
                        'local_term': value.local_term,
                        'pivot': value.pivot,
                    });
                });
                this.symptomsTags.value = symptomsArrDefValues;
                this.symptomsTags.options = symptomsArrOptions;
                this.differentialSymptomsTags.value = symptomsArrDefValues;
                this.differentialSymptomsTags.options = symptomsArrOptions;

            },
            edit(){
                // Show edit div once edit button is clicked
                console.log("Clicked Edit");
                this.editSpecificHealthConditions = true;
                this.cancelSpecificHealthConditions = true;

                // Copy properties from specificHealthConditions to editSpecificHCForm
                this.editSpecificHCForm = JSON.parse(JSON.stringify(this.specificHealthConditions));
                this.editSpecificHCForm.editedSpecificHCFormMediaArr = [];
                this.editSpecificHCForm.attached_media_health_condition = [];

                // this.editSpecificHCForm.zoonotic = this.specificHealthConditions;
                // console.log("editSpecificHCForm");
                // console.log("zoonotic specificHealthConditions");
                // console.log(this.specificHealthConditions.zoonotic);
                // console.log("zoonotic editSpecificHCForm");
                // console.log(this.editSpecificHCForm.zoonotic);
                if(this.editSpecificHCForm.zoonotic == 1){
                    console.log("zoonotic editSpecificHCForm");
                    console.log(this.editSpecificHCForm.zoonotic);
                    this.editSpecificHCForm.zoonotic = true;
                }


                // Append if media hc exists
                if(this.editSpecificHCForm.media_health_condition.length > 0){
                    console.log(this.editSpecificHCForm.media_health_condition);
                    // this.handleImages(this.editSpecificHCForm.media_health_condition);
                }
                
                var mediaHC = this.specificHealthConditions.media_health_condition; 
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
                // console.log(obj);
                // this.handleImages(obj);
            },
            addHCInterventions_createSpecificHCForm(description,hc_id){
                this.SpecificHealthConditionsInterventionAddNeedLicense = $('#need_license')[0].checked;
                this.SpecificHealthConditionsInterventionAddPregnantApplicable = $('#pregnant_applicable')[0].checked;
                
                var SpecificHealthConditionsInterventionAddNeedLicense = 0;
                var SpecificHealthConditionsInterventionAddPregnantApplicable = 0;
                if(this.SpecificHealthConditionsInterventionAddNeedLicense == true){
                    SpecificHealthConditionsInterventionAddNeedLicense = 1;
                }
                if(this.SpecificHealthConditionsInterventionAddPregnantApplicable == true){
                    SpecificHealthConditionsInterventionAddPregnantApplicable = 1;
                }
				var my_object = {
					health_condition_id:hc_id,
					description:description,
					need_license:SpecificHealthConditionsInterventionAddNeedLicense,
					pregnant_applicable:SpecificHealthConditionsInterventionAddPregnantApplicable,
				};
				this.createSpecificHCForm.hc_interventions.push(my_object);
                this.SpecificHealthConditionsInterventionAddDesc = "";

                // this.SpecificHealthConditionsInterventionAddNeedLicense = false;
                // this.SpecificHealthConditionsInterventionAddPregnantApplicable = false;
                console.log(this.createSpecificHCForm.hc_interventions);

                $('#need_license').prop('checked', false).change();
                $('#pregnant_applicable').prop('checked', false).change();

            },
            addHCInterventions(description,hc_id){

                this.SpecificHealthConditionsInterventionAddNeedLicense = $('#need_license1')[0].checked;
                this.SpecificHealthConditionsInterventionAddPregnantApplicable = $('#pregnant_applicable1')[0].checked;
                console.log(this.SpecificHealthConditionsInterventionAddNeedLicense);
                var SpecificHealthConditionsInterventionAddNeedLicense = 0;
                var SpecificHealthConditionsInterventionAddPregnantApplicable = 0;
                
                if(this.SpecificHealthConditionsInterventionAddNeedLicense == true){
                    SpecificHealthConditionsInterventionAddNeedLicense = 1;
                }
                if(this.SpecificHealthConditionsInterventionAddPregnantApplicable == true){
                    SpecificHealthConditionsInterventionAddPregnantApplicable = 1;
                }
				var my_object = {
					health_condition_id:hc_id,
					description:description,
					need_license:SpecificHealthConditionsInterventionAddNeedLicense,
					pregnant_applicable:SpecificHealthConditionsInterventionAddPregnantApplicable,
				};
				this.editSpecificHCForm.hc_interventions.push(my_object);
                this.SpecificHealthConditionsInterventionAddDesc = "";


                $('#need_license1').prop('checked', false).change();
                $('#pregnant_applicable1').prop('checked', false).change();
                console.log(this.editSpecificHCForm.hc_interventions);
            },
            deleteHCInterventions_createSpecificHCForm(index){
				console.log(index);
				this.createSpecificHCForm.hc_interventions.splice(index, 1);
            },
            deleteHCInterventions(index){
				console.log(index);
				this.editSpecificHCForm.hc_interventions.splice(index, 1);
            },
            editSpecificHCInterventions(index){
                $('#'+index+'desc').removeAttr('disabled',false);
                $('#'+index+'nl_showed').hide();
                $('#'+index+'nl_edit').removeAttr('hidden','false');

                $('#'+index+'pl_showed').hide();
                $('#'+index+'pl_edit').removeAttr('hidden','false');
                
                $('#'+index+'edit_button').hide();
                $('#'+index+'saveHCI_button').removeAttr('hidden','false');
                console.log($('#'+index+'desc'));
            },
            cancelSpecificHCInterventions(index){
                $('#'+index+'desc').attr('disabled','disabled');
                $('#'+index+'nl_showed').show();
                $('#'+index+'nl_edit').attr('hidden','hidden');

                $('#'+index+'pl_showed').show();
                $('#'+index+'pl_edit').attr('hidden','hidden');

                $('#'+index+'edit_button').show();
                $('#'+index+'saveHCI_button').attr('hidden','hidden');
                console.log($('#'+index+'desc'));
            },
            deleteHCCurrentMedia(index, media_id){
				console.log(this.editSpecificHCForm.media_health_condition);
                // push editedSpecificHCFormMediaArr
                function findObjectByKey(array, key, value) {
                    for (var i = 0; i < array.length; i++) {
                        if (array[i][key] === value) {
                            return array[i];
                        }
                    }
                    return null;
                }
                var obj = findObjectByKey(this.editSpecificHCForm.media_health_condition, 'id', media_id);
                // push object containing media_id editSpecificHCForm
                this.editSpecificHCForm.editedSpecificHCFormMediaArr.push(obj);
				console.log(this.editSpecificHCForm.editedSpecificHCFormMediaArr);
                // delete to media_health_condition
				this.editSpecificHCForm.media_health_condition.splice(index, 1);
            },
            undoDeletedHCCurrentMedia(index, media_id){
                // push back to media_health_condition
				this.editSpecificHCForm.media_health_condition.push(this.editSpecificHCForm.editedSpecificHCFormMediaArr[this.editSpecificHCForm.editedSpecificHCFormMediaArr.length - 1]);
                this.editSpecificHCForm.editedSpecificHCFormMediaArr.pop();
                console.log(this.editSpecificHCForm.editedSpecificHCFormMediaArr);
            },
            checkbox(){
                console.log(this.editSpecificHCForm.zoonotic);
                
            },
			handleImages(files){
                console.log("files");
                console.log(files);
				// for(var i=0;i<files.length;i++){
				// 	this.editSpecificHCForm.media_health_condition.push(files[i]);
				// }
				// console.log(this.editSpecificHCForm.media_health_condition);
				// if(files.length > this.editSpecificHCForm.media_health_condition.length || files.length < this.editSpecificHCForm.media_health_condition.length){
					for(var i=0;i<files.length;i++){
						this.editSpecificHCForm.attached_media_health_condition.push(files[i]);
					}
					// console.log(this.editSpecificHCForm.attached_media_health_condition);
				// }
			},
            handleImages_createSpecificHCForm(files){
                console.log("files");
                console.log(files);
                // this.createSpecificHCForm.attached_media_health_condition = [];

                for(var i=0;i<files.length;i++){
                    this.createSpecificHCForm.attached_media_health_condition.push(files[i]);
                }
	
			},
            // Edit HC
            selectValue(){
                this.editSpecificHCForm.symptoms = this.symptomsTags.value;

                var symArr = [];
                $.each(this.symptomsTags.value, function(key,value) {
                    symArr.push(value.label);
                });
                this.HasDupps = this.findCommonElement(this.differentialSymptomsTags.value,symArr);
                console.log("this.HasDupps");
                console.log(this.HasDupps);
            },
            deselectValue(value){
                this.editSpecificHCForm.symptoms = this.symptomsTags.value;

                var symArr = [];
                $.each(this.symptomsTags.value, function(key,value) {
                    symArr.push(value.label);
                });
                this.HasDupps = this.findCommonElement(this.differentialSymptomsTags.value,symArr);
                console.log("this.HasDupps");
                console.log(this.HasDupps);
            },
            selectValue_differentialSymptoms(){
                this.editSpecificHCForm.differentialSymptoms = this.differentialSymptomsTags.value;
                // HasDupps: false,
                var symArr = [];
                $.each(this.symptomsTags.value, function(key,value) {
                    symArr.push(value.label);
                });

                this.HasDupps = this.findCommonElement(this.differentialSymptomsTags.value,symArr);
                console.log("this.HasDupps");
                console.log(this.HasDupps);
            },
            deselectValue_differentialSymptoms(value){
                this.editSpecificHCForm.differentialSymptoms = this.differentialSymptomsTags.value;

                var symArr = [];
                $.each(this.symptomsTags.value, function(key,value) {
                    symArr.push(value.label);
                });
                this.HasDupps = this.findCommonElement(this.differentialSymptomsTags.value,symArr);
                console.log("this.HasDupps");
                console.log(this.HasDupps);
            },
            // Function definition with passing two arrays
            findCommonElement(array1, array2) {
                const contains = array1.some(element => {
                    return array2.includes(element.label);
                });

                return contains;
            },
            // End of Edit HC
            // Create HC
            findCommonElement_createSpecificHCForm(array1, array2) {
                const contains = array1.some(element => {
                    return array2.includes(element);
                });

                return contains;
            },
            selectValue_createSpecificHCForm(){
                this.createSpecificHCForm.symptoms = this.symptomsTags.value;

                var symArr = [];
                $.each(this.symptomsTags.value, function(key,value) {
                    symArr.push(value);
                });
                this.HasDupps = this.findCommonElement_createSpecificHCForm(this.differentialSymptomsTags.value,symArr);
                console.log("this.HasDupps");
                console.log(this.HasDupps);
            },
            deselectValue_createSpecificHCForm(value){
                this.createSpecificHCForm.symptoms = this.symptomsTags.value;

                var symArr = [];
                $.each(this.symptomsTags.value, function(key,value) {
                    symArr.push(value);
                });
                this.HasDupps = this.findCommonElement_createSpecificHCForm(this.differentialSymptomsTags.value,symArr);
                console.log("this.HasDupps");
                console.log(this.HasDupps);
            },
            selectValue_createSpecificHCForm_differentialSymptoms(){
                this.createSpecificHCForm.differentialSymptoms = this.differentialSymptomsTags.value;

                var symArr = [];
                $.each(this.symptomsTags.value, function(key,value) {
                    symArr.push(value);
                });
                this.HasDupps = this.findCommonElement_createSpecificHCForm(this.differentialSymptomsTags.value,symArr);
                console.log("this.HasDupps");
                console.log(this.HasDupps);
            },
            deselectValue_createSpecificHCForm_differentialSymptoms(value){
                this.createSpecificHCForm.differentialSymptoms = this.differentialSymptomsTags.value;

                var symArr = [];
                $.each(this.symptomsTags.value, function(key,value) {
                    symArr.push(value);
                });
                this.HasDupps = this.findCommonElement_createSpecificHCForm(this.differentialSymptomsTags.value,symArr);
                console.log("this.HasDupps");
                console.log(this.HasDupps);
            },
            // end of Create HC
            
			SaveEditedHC(){
                console.log(this.editSpecificHCForm);

                if(this.UserRoles.includes("IHealthContentManager")){
                    console.log("IHealthContentManager");
                    this.$inertia.post(route('IHealthContentManager.SaveEditedHC'), this.editSpecificHCForm, {
                        forceFormData: true,
                        onSuccess: (res) => {
                            $('#healthConditionModal').modal('hide');
                            console.log("onSuccess");
                        },
                        onFinish: () => {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your edited content has been saved successfully.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showCancelButton: false,
                                showConfirmButton: false
                            });

                            // Set default view in HC Modal
                            this.editSpecificHealthConditions = false;

                            // Set all Upload Images inputs to empty
                            this.$refs.resetPics.reset();
                            console.log("onFinish");
                        },
                    }).then(function (response) {
                        this.$inertia.reload();
                    }.bind(this));
                }

                if(this.UserRoles.includes("Admin")){
                    console.log("Admin");
                    this.$inertia.post(route('Admin.SaveEditedHC'), this.editSpecificHCForm, {
                        forceFormData: true,
                        onSuccess: (res) => {
                            $('#healthConditionModal').modal('hide');
                            console.log("onSuccess");
                        },
                        onFinish: () => {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your edited content has been saved successfully.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showCancelButton: false,
                                showConfirmButton: false
                            });

                            // Set default view in HC Modal
                            this.editSpecificHealthConditions = false;

                            // Set all Upload Images inputs to empty
                            this.$refs.resetPics.reset();
                            console.log("onFinish");
                        },
                    }).then(function (response) {
                        this.$inertia.reload();
                    }.bind(this));
                }
			},
            resetCreateSpecificHCForm(){
                console.log("reset");
                this.createSpecificHCForm.reset();
                this.symptomsTags.value = [];

                console.log( $('#need_license').prop('checked') );
                $('#need_license').prop('checked', false);
                console.log( $('#need_license').prop('checked') );

                console.log( $('#pregnant_applicable').prop('checked') );
                $('#pregnant_applicable').prop('checked', false).change();
                console.log( $('#pregnant_applicable').prop('checked') );

                
                this.SpecificHealthConditionsInterventionAddDesc = "";
                this.$refs.resetPics.reset();
                this.$page.props.errors = '';
                $('#createHealthConditionModal').modal('hide');
            },
            SaveCreatedHC(){               
                console.log(this.createSpecificHCForm);
                if(this.UserRoles.includes("IHealthContentManager")){
                    console.log("IHealthContentManager");
                    this.$inertia.post(route('IHealthContentManager.SaveCreatedHC'), this.createSpecificHCForm, {
                        forceFormData: true,
                        onSuccess: (res) => {
                            $('#createHealthConditionModal').modal('hide');
                            this.createSpecificHCForm.reset();
                            console.log("onSuccess");
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your Health Condition content has been saved successfully.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showCancelButton: false,
                                showConfirmButton: false
                            });

                        },
                        onFinish: () => {
                            // Set default view in HC Modal
                            this.editSpecificHealthConditions = false;

                            // Set all Upload Images inputs to empty
                            this.$refs.resetPics.reset();
                            console.log("onFinish");
                        },
                    }).then(function (response) {
                        // this.$inertia.reload();
                    }.bind(this));
                }

                if(this.UserRoles.includes("Admin")){
                    console.log("Admin");
                    this.$inertia.post(route('Admin.SaveCreatedHC'), this.createSpecificHCForm, {
                        forceFormData: true,
                        onSuccess: (res) => {
                            $('#createHealthConditionModal').modal('hide');
                            this.createSpecificHCForm.reset();
                            console.log("onSuccess");

                            Swal.fire({
                                title: 'Success!',
                                text: 'Your Health Condition content has been saved successfully.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showCancelButton: false,
                                showConfirmButton: false
                            });
                        },
                        onFinish: () => {
                            // Set default view in HC Modal
                            this.editSpecificHealthConditions = false;

                            // Set all Upload Images inputs to empty
                            this.$refs.resetPics.reset();
                            console.log("onFinish");
                        },
                    }).then(function (response) {
                        // this.$inertia.reload();
                    }.bind(this));
                }
			},
        },
		created: function(){

            console.log("zoonotic");
            console.log(this.specificHealthConditions.zoonotic);

            // For Image Zoom Plugin
			var options = {
                width: 400,
                zoomWidth: 500,
                offset: {vertical: 0, horizontal: 10}
            };

            this.editorConfig = {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
            };

            console.log("hasValidRoleForCreateBtn");
            this.hasValidRoleForCreateBtn(this.UserRoles);
            console.log("hasValidRoleForCreateBtn");
        }
    }
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
