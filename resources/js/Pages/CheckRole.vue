<template>
  <div class="px-5 pt-5" v-if="this.$page.props.flash.error != null">
    <h1>Restricted Area</h1>
    <h4 class="text-secondary"> You are unauthorize to access this page.
      Please be advised that you will be redicrected to your assigned page.
    </h4>
  </div>

  <div class="px-5 pt-5" v-if="this.$page.props.flash.error == null">
    <h1>Verifying your User Access</h1>
    <h4 class="text-secondary">
      Please be advised that you will be redicrected to your assigned page.
    </h4>
  </div>
</template>


<script>
export default {
		props: [ 'UserRoles' ],
		data() {
			return {
        
			};
		},
		methods: {
			redirectToRolePage(){
        setTimeout(() => {
            window.location.href="/"+this.UserRoles[0].role;
        }, 3000);
			},
		},
		created: function(){
      this.redirectToRolePage();
      console.log(this.UserRoles[0].role);
    },
}
</script>
