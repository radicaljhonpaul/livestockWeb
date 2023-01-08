<style scoped>
  .background {
    background-image: url("/images/landingpage/landing_page.jpg");
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
  }

  .color_red {
    color: #78221e;
  }

  .btn-red {
    color: #fff;
    background-color: #78221e;
    border-color: #78221e;
    box-shadow: none;
  }

  .btn-red:hover {
    color: #fff;
    background-color: #792622;
    border-color: #792622;
  }

</style>

<template>
  <div class="background">
    <div class="container-fluid">
      <div class="row justify-content-center" style="min-height:100vh;">
        <div class="col-2"></div>
        <div class="col-6 text-center h-100 ">
          <img src="/images/landingpage/kbgan.png" class="w-100" alt="">
          
          <p class="mt-3" style="font-size:1.2em; color:#000;">Click the button below to Login</p>
          <a v-if="$page.props.user != null" class="btn btn-red font-weight-bold rounded-pill px-5 py-1" :href="route('checkRole')">Go to Dashboard</a>
          <a v-else class="btn btn-red font-weight-bold rounded-pill px-5 py-1" :href="route('login')">LOGIN</a>
        </div>
        <div class="col-2"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
  }
}
</script>
