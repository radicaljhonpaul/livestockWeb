<template>
  <jet-authentication-card>
    <template #logo>
      <jet-authentication-card-logo />
    </template>

    <div class="card-body">

      <jet-validation-errors class="mb-3" />

      <form @submit.prevent="submit">
        <div class="form-group">
          <jet-label for="name" value="Name" />
          <jet-input id="name" type="text" v-model="form.name" required autofocus autocomplete="name" />
        </div>

        <div class="form-group">
          <jet-label for="email" value="Email" />
          <jet-input id="email" type="email" v-model="form.email" required />
        </div>

        <div class="form-group">
          <jet-label for="role" value="Role" />
          <select id="role" type="text" class="form-control" name="role" v-model="form.role" required>
            <option value="pcc_admin">Admin</option>
            <option value="pcc_hq">PCC HQ</option>
            <option value="pcc_vet_field">PCC Vet/Field</option>
            <option value="pcc_ext_workers">Extension Workers</option>
          </select>
        </div>

        <div class="form-group">
          <jet-label for="password" value="Password" />
          <jet-input id="password" type="password" v-model="form.password" required autocomplete="new-password" />
        </div>

        <div class="form-group">
          <jet-label for="password_confirmation" value="Confirm Password" />
          <jet-input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="form-group" v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
          <div class="custom-control custom-checkbox">
            <jet-checkbox name="terms" id="terms" v-model:checked="form.terms" />

            <label class="custom-control-label" for="terms">
              I agree to the <a target="_blank" :href="route('terms.show')">Terms of Service</a> and <a target="_blank" :href="route('policy.show')">Privacy Policy</a>
            </label>
          </div>
        </div>

        <div class="mb-0">
          <div class="d-flex justify-content-end align-items-baseline">
            <inertia-link :href="route('login')" class="text-muted mr-3 text-decoration-none">
              Already registered?
            </inertia-link>

            <jet-button class="ml-4" :class="{ 'text-white-50': form.processing }" :disabled="form.processing">
              Register
            </jet-button>
          </div>
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
import JetCheckbox from "@/Jetstream/Checkbox";
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

  data() {
    return {
      form: this.$inertia.form({
        name: '',
        email: '',
        password: '', 
        role: null,
        password_confirmation: '',
        terms: false,
      })
    }
  },

  methods: {
    submit() {
      this.form.post(this.route('register'), {
        onFinish: () => this.form.reset('password', 'password_confirmation'),
      })
    }
  }
}
</script>
