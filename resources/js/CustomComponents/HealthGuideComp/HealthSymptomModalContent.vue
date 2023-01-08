<template>
    <div class="col-6">
        <div style="display: flex;justify-content: center; align-items: center; height: 200px; border: 3px solid green; height:200px;border:1px solid #eee;" v-if="specificSymptom.media_symptoms.length == 0">
            <p class="text-danger text-center">
                <i class="fas fa-exclamation-triangle fa-3x" style="vertical-align:middle;"></i>
                No images available...
            </p>
        </div>
        <div class="d-block w-100" v-if="specificSymptom.media_symptoms.length > 0" >
            <div id="carouselExampleControlsNoTouching" class="carousel slide" data-touch="false" data-interval="false">
                <div class="carousel-inner">
                    <div class="carousel-item" v-for="(img,key) in specificSymptom.media_symptoms" :class="{ 'active': key==0 }" :key="img">
                        <zoom-on-hover style="cursor:zoom-in;width:100%;height:200px;object-fit:scale-down;border:1px solid #eee;" :img-normal="'../storage/'+img.path_name"></zoom-on-hover>
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
        <div class="form-group">
            <h6 class="font-weight-bolder color-6-red text-sm"> Parent Symptom </h6>
            <span class="color-6-red text-sm font-italic text-wrap text-sm " style="word-wrap: break-word;" v-if="specificSymptom.parent_symptom != null" v-html="specificSymptom.parent_symptom.name"></span>
            <span class="color-5-orange font-italic text-wrap text-sm" style="word-wrap: break-word;" v-if="specificSymptom.parent_symptom == null">No Parent Symptom</span>
        </div>

        <div class="form-group">
            <h6 class="font-weight-bolder color-6-red text-sm"> Local Terminology </h6>
            <div class="text-sm text-wrap" style="word-wrap: break-word;" v-html="specificSymptom.local_term"></div>
        </div>

        <div class="form-group">
            <h6 class="font-weight-bolder color-6-red text-sm"> System </h6>
            <span class="cursor-pointer" v-for="(items, index) in specificSymptom.organ_systems_symptoms" :key="index">
                <span class="badge badge-pill badge-custom-inverted text-wrap font-weight-lighter ml-0 mr-1">
                    {{ items.organ_systems[0].name }}
                </span>
                <span v-if="index+1 < specificSymptom.organ_systems_symptoms.length" v-html="', '"></span>
            </span>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        components: {
            zoomOnHover: zoomOnHover,
        },
        props:{
            specificSymptom:null,
        },
        data() {
            return {
                editor: ClassicEditor,
            }
        },
        created: function(){
            console.log("this.specificSymptom via HealthSymptomModalContent");
            console.log(this.specificSymptom);
        }
    }
</script>
