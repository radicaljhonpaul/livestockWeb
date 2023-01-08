<template>
    <div class="row">
        <div class="col-7">
            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Zoonotic</h6>
                <div class="text-sm" v-if="specificHealthConditions.zoonotic == 1"> Yes </div>
                <div class="text-sm" v-if="specificHealthConditions.zoonotic == 0"> No </div>
            </div>

            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Description </h6>
                <div class="text-sm" v-html="specificHealthConditions.description"></div>
            </div>

            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> How to Diagnose </h6>
                <div class="text-sm" v-html="specificHealthConditions.how_to_diganose"></div>
            </div>

            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Treatment </h6>
                <div class="text-sm" v-html="specificHealthConditions.treatment"></div>
            </div>

            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Advice to Farmer </h6>
                <div class="text-sm" v-html="specificHealthConditions.advice_to_farmer"></div>
            </div>

            <div class="form-group">
                <h6 class="font-weight-bolder color-6-red text-sm"> Healthcare Interventions </h6>
                <ul class="text-sm">
                    <li v-for="(items, index) in specificHealthConditions.hc_interventions" :key="index">
                        {{ items.description }}
                        <span class="ml-1" v-show="items.need_license == 1"> 
                            <!-- <i class="fas fa-certificate color-6-red"></i> -->
                            <img :src="'/images/systems/hc-intervention/vet-icon.png'" style="width:25px;height:25px;" class="inline-block color-6-red" alt="">
                        </span>
                        <span class="ml-1" v-show="items.pregnant_applicable == 1">
                            <!-- <i class="fas fa-baby color-6-red"></i> -->
                            <img :src="'/images/systems/hc-intervention/pregnant-carabao.png'" style="width:30px;height:30px;" class="inline-block color-6-red" alt="">
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-5">
            <div class="d-block w-100">
                <div id="carouselExampleControlsNoTouching" class="carousel slide" data-touch="false" data-interval="false">
                    <div class="carousel-inner">
                        <div class="carousel-item" v-for="(img,key) in specificHealthConditions.media_health_condition" :class="{ 'active': key==0 }" :key="img">
                            <zoom-on-hover style="cursor:zoom-in;width:100%;height:300px;object-fit:scale-down;" :img-normal="'../storage/'+img.path_name"></zoom-on-hover>
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

            <div class="form-group mt-3">
                <h6 class="font-weight-bolder color-6-red text-sm"> Differential Symptoms </h6>
                <span class="cursor-pointer" v-for="(items, index) in specificHealthConditions_diff_symp" :key="index">
                    <span class="badge badge-pill badge-custom-inverted text-wrap font-weight-lighter mr-1">
                        {{ items.label }}
                    </span>
                    <span v-if="index+1 < specificHealthConditions_diff_symp.length" v-html="','"></span>
                </span>
            </div>
            <div class="form-group mt-3">
                <h6 class="font-weight-bolder color-6-red text-sm"> Symptoms </h6>
                <span class="cursor-pointer" v-for="(items, index) in specificHealthConditions_symp" :key="index">
                    <span class="badge badge-pill badge-custom-inverted text-wrap font-weight-lighter mr-1">
                        {{ items.label }}
                    </span>
                    <span v-if="index+1 < specificHealthConditions_symp.length" v-html="','"></span>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        components: {
            zoomOnHover: zoomOnHover,
        },
        props: [
            'specificHealthConditions',
            'specificHealthConditions_symp',
            'specificHealthConditions_diff_symp',
        ],
        data() {
            return {
                editor: ClassicEditor,
            }
        },
        methods: {
            
        },
		created: function(){
            
        }

        
    }
</script>
