<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper bg-transparent">
            <section class="container-fluid">
                <div class="row px-3">
                    <div class="col-12">
                        <ol class="breadcrumb bg-transparent pt-0 mb-0 text-sm float-right">
                            <!-- <li class="breadcrumb-item">
                                <inertia-link :href="route(UserRoles[0]+'.AdminHealthGuide')" :active="route().current(UserRoles[0]+'.AdminHealthGuide')" class="">Systems</inertia-link>
                            </li> -->
                        </ol>
                    </div>
                    <div class="col ml-3">
                        <h5 class="color-6-red font-weight-bold">
                            Systems 
                        </h5>
                    </div>
                    
                    <!-- Symptoms List -->
                    <div class="col-2 text-right ml-auto">
                        <inertia-link :href="route(UserRoles[0]+'.Symptoms')" class="btn btn-outline-brown btn-sm" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">Symptoms List <i class="fas fa-clipboard"></i></inertia-link>
                        <!-- <button class="btn btn-outline-warning btn-sm" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-bottom-left-radius:50px;border-top-left-radius:50px;">
                            Symptoms List <i class="fas fa-clipboard"></i>
                        </button> -->
                    </div>
                    
                    <!-- Searching -->
                    <div class="col-3 text-right ml-auto">
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control border-right-0 searching" v-model="searchSystems" placeholder="Search..." aria-label="Search..." style="border-top-left-radius:50px;border-bottom-left-radius:50px;" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-light border-left-0 bg-white" style="border-top-right-radius:50px;border-bottom-right-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-right:1px solid #ced4da;">
                                    <i class="fas fa-search color-4-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row justify-content-start">
                    <div class="d-flex flex-row flex-wrap">
                        <div class="col spec_systems" style="margin-bottom:30px;" v-for="Organs in OrganSystems" :key="Organs">
                            <a href="#" @click="viewSpecificSystem(Organs.name,Organs.id)">
                                <div class="content text-center">
                                    <img :src="'/images/systems/'+Organs.id+'.png'" alt="" style="width:70px;height:70px;">
                                    <p class="mt-0 mb-0 text-sm font-italic">{{ Organs.name }}</p>
                                    <p class="mb-0 text-xs">({{ Organs.local_term }})</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>


            </section>
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from '@/Layouts/AdminLayout'

    export default {
        components: {
            AdminLayout,
        },
        props: [ 'UsersDetails', 'OrganSystems', 'UserRoles'],
        data() {
            return {
                searchSystems: null,
            }
        },
        methods: {
            viewSpecificSystem(Type,Key){
                this.$inertia.get(route(this.UserRoles[0]+'.SpecificSystem'),
                    {
                        type: Type,
                        id: Key,
                    },
                    { replace: true })
            }
        },
        created: function(){
            // console.log(this.UserRoles);
        },
    }
</script>
