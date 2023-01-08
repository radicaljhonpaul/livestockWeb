<template>
  <div class="container">
    <div class="row justify-content-center mt-3 mb-5">
      <div class="col-sm-12 col-md-8 col-lg-5 my-4">
        <div>
          <slot name="logo" />
        </div>

        <div class="card shadow-none px-3 bg-transparent">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>
