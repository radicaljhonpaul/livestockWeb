<template>
  <!-- Feeding Log Info-->
  <div class="row">
    <div class="col-6">
        <div  class ="form-group">
            <h6 class="font-weight-bolder color-6-red text-sm my-0">Visit Date</h6>
            <span class="my-0" style="word-wrap: break-word">
                {{  this.dateFormat(specificFeedlog.visit_date) }}
            </span>
        </div>
    </div>

    <div class="col-6">
      <div  class ="form-group">  
        <h6 class="font-weight-bolder color-6-red text-sm my-0">Ration Created By</h6>
        <span class="my-0" style="word-wrap: break-word">
            {{ specificFeedlogCreatedBy.last_name }},  {{ specificFeedlogCreatedBy.first_name }}
        </span>
      </div>
    </div>

    <div class="col-6 form-group">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">Category</h6>
      <span class="my-0" style="word-wrap: break-word">
        <span v-if="specificFeedlog.category == 'Calf (0 – 4 months) – lower than 100kg | Bulo (0 – 4 na buwan)'">Calf (0-4 months) - lower than 100kg</span>
        <span v-if="specificFeedlog.category == 'Growing Calves (5 – 12 months) | Lumalaking bulo (5 – 12 buwan)'">Growing Calves (5-12 months)</span>
        <span v-if="specificFeedlog.category == 'Heifer | Dumalaga'">Heifer</span>
        <span v-if="specificFeedlog.category == 'Junior Bull | Lumalaking bulugan (2 – 3 taon)'">Junior Bull</span>
        <span v-if="specificFeedlog.category == 'Senior Bull | Bulugan (> 3 taon)'">Senior Bull</span>
        <span v-if="specificFeedlog.category == 'Cow | Inahing kalabaw'">Cow</span>
      </span>
    </div>

    <!--Filler for readability and row grouping -->
    <div class="col-6 form-group">
      <h6 class="font-weight-bolder color-6-red text-sm my-0"></h6>
      <span class="my-0" style="word-wrap: break-word"> </span>
    </div>

    <div class="col-6 form-group">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">
        Body Weight (Kg)
      </h6>
      <span class="my-0" style="word-wrap: break-word">
        {{ specificFeedlog.body_weight }}
      </span>
    </div>

    <div class="col-6 form-group">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">
        Ave Daily Gain (Kg)
      </h6>
      <span class="my-0" style="word-wrap: break-word" v-if="specificFeedlog.ave_daily_gain != null && specificFeedlog.ave_daily_gain != -1">
        {{ specificFeedlog.ave_daily_gain }}
      </span>
       <span class="my-0" style="word-wrap: break-word" v-if="specificFeedlog.ave_daily_gain == null || specificFeedlog.ave_daily_gain == -1">
        N/A
      </span>

    </div>

    <!-- Lactating Question dependent on Cow type -->
        <div class="col-6 form-group"  v-if="specificFeedlog.category == 'Cow | Inahing kalabaw'">
        <h6 class="font-weight-bolder color-6-red text-sm my-0">Lactating or Dry</h6>
        <span class="my-0" style="word-wrap: break-word">
            <span v-if="specificFeedlog.is_lactating == 1">Lactating</span>
            <span v-if="specificFeedlog.is_lactating == 0">Dry</span>
        </span>
        </div>

        <div class="col-6 form-group"  v-if="specificFeedlog.category == 'Cow | Inahing kalabaw'">
        <h6 class="font-weight-bolder color-6-red text-sm my-0">
            <span v-if="specificFeedlog.is_lactating == 1">Lactation Stage</span>
        </h6>
        <span class="my-0" style="word-wrap: break-word" v-if="specificFeedlog.is_lactating == 1">
            <span v-if="specificFeedlog.lactation_stage == 'Early Lactation (1–100 days) | Unang bahagi ng paggagatas (1 – 100)'">Early (1-100 days)</span>
            <span v-if="specificFeedlog.lactation_stage == 'Mid Lactation (101 – 200 days) | Gitnaang bahagi ng paggagatas (101 – 200)'">Mid (101 - 200 days)</span>
            <span v-if="specificFeedlog.lactation_stage == 'Late Lactation (201 – 305 days) | Huling bahagi ng paggagatas (201 – 305)'">Late (201 - 305 days)</span>
        </span>
        </div>
    

    <div class="col-6 form-group"  v-if="specificFeedlog.category == 'Cow | Inahing kalabaw' || specificFeedlog.category == 'Heifer | Dumalaga'">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">Pregnant</h6>
      <span class="my-0" style="word-wrap: break-word">
        <span v-if="specificFeedlog.is_pregnant == 1">Yes</span>
        <span v-if="specificFeedlog.is_pregnant == 0">No</span>
      </span>
    </div>

    <div class="col-6 form-group" v-if="specificFeedlog.category == 'Cow | Inahing kalabaw' || specificFeedlog.category == 'Heifer | Dumalaga'">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">
       <span v-if="specificFeedlog.is_pregnant == 1"> Pregnancy Month</span>
      </h6>
      <span class="my-0" style="word-wrap: break-word">
        <span v-if="specificFeedlog.pregnancy_month != null && specificFeedlog.pregnancy_month >= '1'  && specificFeedlog.is_pregnant == 1">
            {{ specificFeedlog.pregnancy_month }}
        </span>
      </span>
    </div>

    <div class="col-6 form-group" v-if="specificFeedlog.is_lactating == 1">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">
        Milk Yield Per Day (Kg)
      </h6>
      <span class="my-0" style="word-wrap: break-word">
        {{ specificFeedlog.milk_yield_per_day }}
      </span>
    </div>

    <div class="col-6 form-group" v-if="specificFeedlog.is_lactating == 1">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">
        Fat and Protein Content (%)
      </h6>
      <span class="my-0" style="word-wrap: break-word" v-if="specificFeedlog.fat_protein != -1">
        {{ specificFeedlog.fat_protein }}
      </span>
    </div>

    <div class="col-6 form-group" v-if="specificFeedlog.is_lactating == 1">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">
        Milk Price (PHP)
      </h6>
      <span class="my-0" style="word-wrap: break-word"  v-if="specificFeedlog.milk_price != -1">
        {{ specificFeedlog.milk_price }}
      </span>
    </div>

    <!--Filler for readability and row grouping for 3 milk related questions -->
    <div class="col-6 form-group" v-if="specificFeedlog.is_lactating == 1">
      <h6 class="font-weight-bolder color-6-red text-sm my-0"></h6>
      <span class="my-0" style="word-wrap: break-word"> </span>
    </div>

    <div class="col-6 form-group">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">
        Total Feed Cost Per Day (PHP)
      </h6>
      <span class="my-0" style="word-wrap: break-word">
        {{ specificFeedlog.total_cost_per_day }}
      </span>
    </div>

    <div class="col-6 form-group">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">
        Feed Cost Per Kg (PHP)
      </h6>
      <span class="my-0" style="word-wrap: break-word" v-if="specificFeedlog.total_as_fed_kg != null && specificFeedlog.total_as_fed_kg != 0">
        {{ specificFeedlog.total_cost_per_day / specificFeedlog.total_as_fed_kg }}
      </span>
        <span class="my-0" style="word-wrap: break-word" v-if="specificFeedlog.total_as_fed_kg == null || specificFeedlog.total_as_fed_kg == 0">
        N/A
      </span>
    </div>

    <div class="col-6 form-group">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">Income (PHP)</h6>
      <span class="my-0" style="word-wrap: break-word">
        {{ specificFeedlog.income }}
      </span>
    </div>

    <!--For readabilty space-->
    <div class="col-6 form-group">
      <h6 class="font-weight-bolder color-6-red text-sm my-0"> </h6>
      <span class="my-0" style="word-wrap: break-word">
        
      </span>
    </div>

    <div class="col-6 form-group">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">
        Created Date
      </h6>
      <span class="my-0" style="word-wrap: break-word">
        {{ this.dateTimeFormat(specificFeedlog.created_at) }}
      </span>
    </div>

     <div class="col-6 form-group">
      <h6 class="font-weight-bolder color-6-red text-sm my-0">
        Modified Date
      </h6>
      <span class="my-0" style="word-wrap: break-word">
        {{ this.dateTimeFormat(specificFeedlog.updated_at) }}
      </span>
    </div> 

       <div class="col-6 form-group">
          <h6 class="font-weight-bolder color-6-red text-sm my-0">
            Ration Name
          </h6>

          <span class="my-0" style="word-wrap: break-word">
            {{ specificFeedlog.ration_name }}
          </span>


        </div>
  </div>
  

  <!-- Feeding Log Nutrient Detail Info-->
  <div class="container px-0">
    <h6 class="font-weight-bolder color-6-red text-sm my-0">
      Nutrient Details:
    </h6>

    <table class="table text-sm">
      <thead class="color-6-red">
        <tr>
          <th scope="col" class="pt-3 pb-1 border-0">
            <span class="d-flex justify-content-between align-items-center">
            Type              
            </span>
          </th>
          <th scope="col" class="pt-3 pb-1 border-0">
            <span class="d-flex justify-content-between align-items-center">
            DM (Kg)              
            </span>
          </th>
          <th scope="col" class="pt-3 pb-1 border-0">
            <span class="d-flex justify-content-between align-items-center">
            TDN (Kg)              
            </span>
          </th>
          <th scope="col" class="pt-3 pb-1 border-0">
            <span class="d-flex justify-content-between align-items-center">
            CP (g)              
            </span>
          </th>
          <th scope="col" class="pt-3 pb-1 border-0">
            <span class="d-flex justify-content-between align-items-center">
            Ca (g)              
            </span>
          </th>
          <th scope="col" class="pt-3 pb-1 border-0">
            <span></span>
            P (g)
          </th>
        </tr>
      </thead>

       <tbody>
        <tr v-for="nutrientDetail in specificFeedlog.nutrient_details" :key="nutrientDetail">
          <td
            class="text-wrap text-sm"
            style="max-width: 150px"
          >
            <span v-if="nutrientDetail.type == 'RA'" class="font-weight-bolder">
              Ration
            </span>

            <span v-if="nutrientDetail.type == 'RE'" class="color-5-orange font-weight-bolder">
              Requirement
            </span>
          
          </td>

          <td
            class="text-wrap text-sm"
            style="max-width: 150px"
            v-html="nutrientDetail.dm"
          ></td>

          <td
            class="text-wrap text-sm"
            style="max-width: 150px"
            v-html="nutrientDetail.tdn"
          ></td>

          <td
            class="text-wrap text-sm"
            style="max-width: 150px"
            v-html="nutrientDetail.cp"
          ></td>

          <td
            class="text-wrap text-sm"
            style="max-width: 150px"
            v-html="nutrientDetail.ca"
          ></td>

          <td
            class="text-wrap text-sm"
            style="max-width: 150px"
            v-html="nutrientDetail.p"
          ></td>

        </tr>
        </tbody>

    </table>



  </div>

  <!-- Feeding Log Ingredient Info -->

  <div class="container px-0">
    <h6 class="font-weight-bolder color-6-red text-sm my-0">
      Ration Recommended for Carabao:
    </h6>

    <table class="table text-sm">
      <thead class="color-6-red">
        <tr>
          <th scope="col" class="pt-3 pb-1 border-0">
            <span class="d-flex justify-content-between align-items-center">
              Category
            </span>
          </th>
          <th scope="col" class="pt-3 pb-1 border-0">
            <span class="d-flex justify-content-between align-items-center">
              Feed Name
            </span>
          </th>
          <th scope="col" class="pt-3 pb-1 border-0">
            <span class="d-flex justify-content-between align-items-center">
              Price at Date (per Kg)
            </span>
          </th>
          <th scope="col" class="pt-3 pb-1 border-0">
            <span class="d-flex justify-content-between align-items-center">
              As Fed (Kg)
            </span>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="ingredient in specificFeedlog.ingredients" :key="ingredient">
          <td
            class="text-wrap text-sm"
            style="max-width: 150px"
            v-html="ingredient.category_name"
          ></td>
          <td
            class="text-wrap text-sm"
            style="max-width: 150px"
            v-html="ingredient.ingredient_name"
          ></td>
          <td
            class="text-wrap text-sm"
            style="max-width: 150px"
            v-html="ingredient.pivot.price_at_date"
          ></td>
          <td
            class="text-wrap text-sm"
            style="max-width: 150px"
            v-html="ingredient.pivot.quantity"
          ></td>
        </tr>
      </tbody>
    </table>

  </div>







</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import moment from "moment";

export default {
  components: {
    zoomOnHover: zoomOnHover,
  },
  props: {
    specificFeedlog: [],
    specificFeedlogCreatedBy: [],
  },
  data() {
    return {
      editor: ClassicEditor,
    };
  },
  methods: {
    dateFormat(date_data) {
      return moment(date_data).format("MMM DD YYYY");
    },
    dateTimeFormat(date_data) {
      return moment(date_data).format("MMM DD YYYY, h:mm:ss a");
    },
  },
  created: function () {
    console.log("this.specificFeedlog");
    console.log(this.specificFeedlog);
  },
};
</script>
