<template>
    <admin-layout :UserRoles="UserRoles">
        <div class="content-wrapper">
            <section class="container-fluid">
                <h2 class="h4 font-weight-bold">
                    Staff Profile
                </h2> 
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
        props: [ 'StaffsDetails', 'UsersDetails', 'Filters', 'PrevUrl', 'UserRoles' ],

    }
</script>
