<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">

                <div class="row px-3">

                    <div class="col mr-auto">
                        <h5 class="color-6-red font-weight-bold">
                            Visit Ratings
                        </h5>
                    </div>
        
                </div>


                <!--Visit Ratings table-->
            
                <div class="row px-3 justify-content-start">
                    <div class="col shadow rounded bg-light">
                        <table class="table text-sm">
                            <thead>
                                <tr class="color-6-red">
                                    <th role="button" scope="col" class="pt-3 pb-1 border-0">
                                        <span class="d-flex justify-content-between align-items-center" @click="sort('visit_date')">
                                        Visit Date
                                        <i v-if="params.field === null && params.direction === null" class="w-4 h-4 fas fa-sort"></i>
                                            <i v-if="params.field === 'visit_date' && params.direction === 'asc'" class="w-4 h-4 fas fa-sort-amount-up"></i>
                                            <i v-if="params.field === 'visit_date' && params.direction === 'desc'" class="w-4 h-4 fas fa-sort-amount-down"></i>
                                        </span>
                                    </th>
                                    <th scope="col" class="pt-3 pb-1 border-0"> Rating </th>
                                    <th scope="col" class="pt-3 pb-1 border-0"> Farmer Reply </th>
                                    <th scope="col" class="pt-3 pb-1 border-0"> Rating For </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="color-6-red" v-for="VisitRating in VisitRatings" :key="VisitRating">
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ VisitRating.visit_date }}</td>
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ VisitRating.rating }}</td>
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ VisitRating.farmer_reply }}</td>
                                    <td class="text-truncate text-sm border-0" style="max-width: 150px;">{{ VisitRating.assigned_to.last_name }}, {{ VisitRating.assigned_to.first_name}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>   

       
            </section>

        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'
     import Modal from '@/Jetstream/Modal'
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import Swal from 'sweetalert2'
    import Pagination from '@/CustomComponents/GeneralComp/Pagination.vue'

    export default {
        components: {
            AdminLayout,
            Modal,
            Swal,
        },
        props:['UsersDetails','VisitRatings', 'Filters', 'errors', 'UserRoles'],
        data()  {
            return {
                 params:{
                    field: this.Filters.field,
                    direction: this.Filters.direction, 
                },    
                editor: ClassicEditor,
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
                        this.$inertia.get(this.route(this.UserRoles[0]+'.AdminVisitRatings'), params, {replace: true, preserveState: false});
                    }.bind(this), 500)
                },
                deep: true,
            },
        },
        methods:{
            sort(field){
                this.params.field = field;
                this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc';
            }
        }     
        
    }
</script>
