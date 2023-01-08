<style scoped>
    .bg-color-6-red {
        background-color: #78221e;
    }

    .color-white {
        color: #fff;
    }
</style>

<template>
    <nav aria-label="Page Navigation">
        <ul class="pagination">
            <li class="page-item" v-if="current_page > 1" :href="prev_url">
                <inertia-link class="page-link" v-if="current_page > 1" :href="prev_url">
                    <span aria-hidden="true"> <i class="fas fa-chevron-left"></i> </span>
                </inertia-link>
            </li>
            <li class="page-item" v-for="link in total_page" :key="link" >

                <!--Page link for numbers -->
                <inertia-link class="page-link" :class="{ 'bg-color-6-red color-white': current_page ==  link }" v-if="links[link] !== undefined && links[link].label.includes('Next') == false && links[link].label.includes('...') == false" :href="links[link].url" >
                    {{ links[link].label}}
               </inertia-link>

                <!--Page link for ... so that it does not display null-->
                <inertia-link class="page-link" :class="{ 'bg-color-6-red color-white': current_page ==  link }" v-if="links[link] !== undefined && links[link].label.includes('...') " :href="path">
                    {{ links[link].label}}
               </inertia-link>

            </li>
            <li class="page-item">
                <inertia-link class="page-link" v-if="total_page != current_page" :href="next_url">
                    <span aria-hidden="true"> <i class="fas fa-chevron-right"></i> </span>
                </inertia-link>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: {
            links: Array,
            current_page: Number,
            prev_url: "",
            next_url: "",
            total_page: Number,
            path: "",
        },
        created(){
            console.log("LINKS");
            console.log(this.links);
        },
        methods:{
        }
    }
</script>
