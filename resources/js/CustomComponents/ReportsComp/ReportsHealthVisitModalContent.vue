<template>
    <div class="row">

        <div class="col-12 pb-4" v-if="specificDiagnosis.media_diagnosis_logs.length > 0">
            <h6 class="font-weight-bolder color-6-red text-sm"> Attached Media (photo) </h6>
            <div class="d-block w-100">
                <div id="carouselExampleControlsNoTouching" class="carousel slide" data-touch="false" data-interval="false">
                    <div class="carousel-inner">
                        <div class="carousel-item" v-for="(img,key) in specificDiagnosis.media_diagnosis_logs" :class="{ 'active': key==0 }" :key="img">
                            <img v-if="img.path_name.includes('jpg') || img.path_name.includes('png')" :src="'../storage/'+img.path_name" style="width:100%;height:300px;object-fit:contain;" alt="">
                            <video v-else style="width:100%;height:300px;object-fit:contain;padding-left:170px;padding-right:170px;" controls>
                                <source :src="'../storage/'+img.path_name" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <!-- <zoom-on-hover style="cursor:zoom-in;width:100%;height:300px;object-fit:scale-down;" :img-normal="'../storage/'+img.path_name"></zoom-on-hover> -->
                        </div>

                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControlsNoTouching" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControlsNoTouching" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-6">
            <h6 class="font-weight-bolder color-6-red text-sm my-0"> Name of Farmer </h6>
            <p class="my-0">{{ specificDiagnosis.joined_farmers_first_name +' '+ specificDiagnosis.joined_farmers_last_name }}</p>
        </div>
        <div class="col-6">
            <h6 class="font-weight-bolder color-6-red text-sm my-0"> Farmer Mobile Number </h6>
            <span class="my-0" style="word-wrap: break-word;">
                {{ specificDiagnosis.joined_farmers_mobile_number }}
            </span>
        </div>

        <div class="col-6" >
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm my-0"> Location </h6>
                <span class="my-0" style="word-wrap: break-word;">
                    {{ specificDiagnosis.livestock.farmer.admin_level_three.name +', '+ specificDiagnosis.livestock.farmer.admin_level_three.admin_level_two.name +', '+ specificDiagnosis.livestock.farmer.admin_level_three.admin_level_two.admin_level_one.name }}
                </span>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm my-0"> Carabao ID </h6>
                <p class="my-0">{{ specificDiagnosis.carabao_code }}</p>
            </div>
        </div>

        <div class="col-6" >
            <div class="form-group">
            <h6 class="font-weight-bolder color-6-red text-sm my-0"> Staff that conducted visit </h6>
            <span class="my-0" style="word-wrap: break-word;">
                {{ specificDiagnosis.assigned_to_first_name +' '+ specificDiagnosis.assigned_to_last_name }}
            </span>
            </div>
        </div>

        <div class="col-6" >
            <div class="form-group">
            <h6 class="font-weight-bolder color-6-red text-sm my-0"> Date of Visit </h6>
            <span class="my-0" style="word-wrap: break-word;">
                {{ this.dateFormat(specificDiagnosis.visit_date) }}
            </span>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Activity Type </h6>
                <!--<p class="my-0">{{ specificDiagnosis.activity_type }}</p>-->
                <span class="text-bold" v-if="specificDiagnosis.activity_type == 'DV'"> Diagnosis Visit </span>
                <span class="text-bold" v-if="specificDiagnosis.activity_type == 'RE'"> Report / Monitoring Visit </span>
            </div>
        </div>

        <div class="col-6" >
            <h6 class="font-weight-bolder color-6-red text-sm my-0"> Assessment </h6>
            <span class="my-0" style="word-wrap: break-word;">
                <!--{{ specificDiagnosis.assessment }}-->
                <span class="text-bold" v-if="specificDiagnosis.assessment == 'CR'"> Critical </span>
                <span class="text-bold" v-if="specificDiagnosis.assessment == 'IM'"> Important </span>
                <span class="text-bold" v-if="specificDiagnosis.assessment == 'RE'"> Report / Monitoring Visit </span>
            </span>
        </div>

        <div class="col-lg-6 col-12-sm">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Matching Condition Name(s) </h6>
                <span class="cursor-pointer" v-for="(items, index) in specificDiagnosis.health_conditions" :key="index">
                    <span class="badge badge-pill badge-custom-inverted text-wrap font-weight-lighter mr-1">
                        {{ items.name }}
                    </span>
                    <span v-if="index+1 < specificDiagnosis.health_conditions.length" v-html="','"></span>
                </span>
            </div>
        </div>

        <div class="col-lg-6 col-12-sm">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> List of Symptoms Selected </h6>
                <span class="cursor-pointer" v-for="(items, index) in specificDiagnosis.symptoms" :key="index">
                    <span class="badge badge-pill badge-custom-inverted text-wrap font-weight-lighter mr-1">
                        {{ items.name }}
                    </span>
                    <span v-if="index+1 < specificDiagnosis.symptoms.length" v-html="','"></span>
                </span>
            </div>
        </div>
        
        <div class="col-lg-6 col-12-sm">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Intervention Done </h6>
                <span class="cursor-pointer" v-for="(items, index) in specificDiagnosis.interventions" :key="index">
                    <span class="badge badge-pill badge-custom-inverted text-wrap font-weight-lighter mx-1" v-if="items.need_license == 0">
                        {{ items.description }}
                    </span>
                    <!-- <span v-if="index+1 < specificDiagnosis.interventions.length" v-html="','"></span> -->
                </span>
            </div>
        </div>

        <div class="col-lg-6 col-12-sm">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Intervention Done by Veterinarian </h6>
                <span class="cursor-pointer" v-for="(items, index) in specificDiagnosis.interventions" :key="index">
                    <span class="badge badge-pill badge-custom-inverted text-wrap font-weight-lighter mx-1" v-if="items.need_license == 1">
                        {{ items.description }}
                    </span>
                    <!-- <span v-if="index+1 < specificDiagnosis.interventions.length" v-html="','"></span> -->
                </span>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Other Notes </h6>
                <p class="my-0 text-wrap text-sm" style="word-wrap: break-word;">{{ specificDiagnosis.notes }}</p>

              
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">   
                <h6 class="font-weight-bolder color-6-red text-sm my-0"> Is Carabao Pregnant During Visit? </h6>
                <p class="my-0" v-if="specificDiagnosis.is_pregnant == 0 ">
                    No
                </p>

                 <p class="my-0" v-if="specificDiagnosis.is_pregnant == 1 ">
                    Yes
                </p>
            </div>
        </div>                


        <div class="col-6">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Authorization By </h6>
                <p class="my-0" v-if="specificDiagnosis.authorized_by != null && specificDiagnosis.authorized_by != '0'">
                    {{ specificDiagnosis.authorized_by.first_name +' '+ specificDiagnosis.authorized_by.last_name }}
                </p>

                <p class="my-0" v-if="specificDiagnosis.authorized_by == null || specificDiagnosis.authorized_by == '0'">
                    N/A
                </p>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Authorization Via  (On Site / SMS / Call) </h6>
                <p class="my-0" v-if="specificDiagnosis.authorization_via == 1"> On-Site </p>
                <p class="my-0" v-if="specificDiagnosis.authorization_via == 2"> SMS </p>
                <p class="my-0" v-if="specificDiagnosis.authorization_via == 3"> Call </p>

            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm my-0"> Created Date </h6>
                <p class="my-0">
                    {{ this.dateTimeFormat(specificDiagnosis.created_at) }}
                </p>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm my-0"> Modified Date </h6>
                <p class="my-0">
                    {{ this.dateTimeFormat(specificDiagnosis.updated_at) }}
                </p>
            </div>
        </div>


        <div class="col-6">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm my-0"> Diagnosis Log External Id </h6>
                <p class="my-0">
                    {{ specificDiagnosis.external_id }}
                </p>
            </div>
        </div>
        
        <div class="col-6">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm my-0"> Status </h6>
                <p class="my-0" v-if="specificDiagnosis.status == 'OP'">
                    OPEN
                </p>
                <p class="my-0" v-if="specificDiagnosis.status == 'CL'">
                    CLOSED - {{ this.dateFormat(specificDiagnosis.date_closed) }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import moment from 'moment';

    export default {
        components: {
            zoomOnHover: zoomOnHover,
        },
        props:{
            specificDiagnosis:[],
        },
        data() {
            return {
                editor: ClassicEditor,
            }
        },
        methods:{
            dateFormat(date_data){
                return moment(date_data).format('MMM DD YYYY');
            },
            dateTimeFormat(date_data){
                return moment(date_data).format('MMM DD YYYY, h:mm:ss a');
            }
        },
        created: function(){
            console.log("this.specificDiagnosis");
            console.log(this.specificDiagnosis);
        }
    }
</script>
