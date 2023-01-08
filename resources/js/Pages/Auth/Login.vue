<template>
  <jet-authentication-card>
    <template #logo>
      <jet-authentication-card-logo />
    </template>

    <div class="card-body">

      <jet-validation-errors class="mb-3" />

      <div v-if="status" class="alert alert-success mb-3 rounded-0" role="alert">
        {{ status }}
      </div>

      <form @submit.prevent="submit">
        <div class="form-group">
          <jet-label for="email" class="color-6-red" value="Email" />
          <jet-input class="rounded-pill" id="email" type="email" v-model="form.email" required autofocus />
        </div>

        <div class="form-group">
          <jet-label for="password" class="color-6-red" value="Password" />
          <div class="input-group mb-3">
            <jet-input id="password" type="password" v-model="form.password" required autocomplete="current-password" style="border-top-left-radius:50px;border-bottom-left-radius:50px;border-top:1px solid #ced4da;border-bottom:1px solid #ced4da;border-left:1px solid #ced4da;"/>
              <div class="input-group-append">
                  <span @click="toggleShowPassword()" type="button" class="toggleBtnPass bg-white">
                      <i class="fas" :class="{ 'fa-eye-slash': showPassword == true, 'fa-eye': showPassword == false }" style="margin-top: 50%;margin-left:-50%;"></i>
                  </span>
              </div>
          </div>
        </div>

        <div class="form-group">
          <div class="custom-control custom-checkbox">
            <jet-checkbox id="remember_me" name="remember" v-model:checked="form.remember" />

            <label class="custom-control-label color-6-red" for="remember_me">
              Remember Me
            </label>
          </div>
        </div>

        <div class="mb-0">
          <div class="d-flex justify-content-end align-items-baseline mb-2">
            <jet-button class="form-control rounded-pill" :type="submit" :class="{ 'text-white-50': form.processing }" :disabled="form.processing">
              Log in <i class="fas fa-sign-in-alt"></i>
            </jet-button>          
          </div>

          <inertia-link v-if="canResetPassword" :href="route('password.request')" class="color-5-orange">
            Forgot your password?
          </inertia-link>
        </div>
      </form>
    </div>
  </jet-authentication-card>
</template>

<script>
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from '@/Jetstream/Checkbox'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'

export default {
  components: {
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors
  },

  props: {
    canResetPassword: Boolean,
    status: String
  },

  data() {
    return {
      form: this.$inertia.form({
        email: '',
        password: '',
        remember: false
      }),
      showPassword: false,
    }
  },

  methods: {
    toggleShowPassword() {
      console.log($('#password').attr('type'));
      if($('#password').attr('type') == 'password'){
        $('#password').attr('type', 'text');
        this.showPassword = true;
      }else{
        $('#password').attr('type', 'password');
        this.showPassword = false;
      }
    },
    submit() {
      this.form
          .transform(data => ({
            ... data,
            remember: this.form.remember ? 'on' : ''
          }))
          .post(this.route('login'), {
            onFinish: () => this.form.reset('password'),
          })
    },
  }
}
</script>
